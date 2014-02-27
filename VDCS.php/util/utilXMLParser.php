<?
class utilXMLParser
{
	var $parser,$_ErrorType;
	var $srcenc,$dstenc;
	
	var $_struct=array();
	var $_index=array();
	var $_tree=null;
	
	const FIELD_VALUE			= '__value';		//???
	
	
	public function __construct($srcenc=null,$dstenc=null)
	{
		$this->srcenc=$srcenc;
		$this->dstenc=$dstenc;

		// initialize the variable.
		$this->parser=null;
		$this->_ErrorType=-1;
	}
	public function __destruct() { unset($this->_struct,$this->_index,$this->_tree); }
	
	
	/**
	* Free the resources
	*
	* @access		public
	* @return		void
	**/
	function free() { if(isset($this->parser) && is_resource($this->parser)) { xml_parser_free($this->parser); unset($this->parser); } }
	
	public function isParse() { return $this->_ErrorType!=0?flase:true; }
	public function getParseError() { return $this->_ErrorType; }
	public function getErrorType() { return $this->_ErrorType; }
	
	
	/**
	* Parses the XML file
	*
	* @access		public
	* @param		string		[$file] the XML file name
	* @return		void
	* @since
	*/
	function parseFile($file) {
		$this->filepath=$file;
		$data=@file_get_contents($file);	// or die("Can't open file $file for reading!");
		$this->parseString($data);
	}
	
	/**
	* Parses a string.
	*
	* @access		public
	* @param		string		[$data] XML data
	* @return		void
	*/
	function parseString($data)
	{
		if(!$data) { $this->_ErrorType=1; return; }
		if($this->srcenc === null) { $this->parser=@xml_parser_create(); }
		else { $this->parser=@xml_parser_create($this->srcenc); }
		if(!$this->parser) { $this->_ErrorType=2; return; }
		
		if($this->dstenc !== null){
			if(!@xml_parser_set_option($this->parser,XML_OPTION_TARGET_ENCODING,$this->dstenc)) { $this->_ErrorType=3; return; }
		}
		xml_parser_set_option($this->parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($this->parser,XML_OPTION_SKIP_WHITE,1);
		//debugx('--'.$data);
		if(!xml_parse_into_struct($this->parser,$data,$this->_struct,$this->_index)){
			$byfile=$this->filepath?' in '.$this->filepath:'';
			$debug_info=sprintf("XML error: %s at line %d".$byfile,
					xml_error_string(xml_get_error_code($this->parser)),
					xml_get_current_line_number($this->parser)
			);
			$debug_log=$debug_info;
			if(!$this->filepath){
				//$debug_log.=NEWLINE.substr($data,0,1000).' ..';
				$log_file='xml_file_'.$_SERVER['REQUEST_TIME_FLOAT'];
				$debug_log.=', file='.$log_file;
				dcsLogSave($log_file,null,utilCoder::toUTF8($data));
			}
			debugx($debug_info);
			//debugx($debug_log);
			dcsLogSave('xml.{date}',null,$debug_log);
			$this->free();
			$this->_count=-1;
			$this->_ErrorType=1;
			return;
		}
		//debugArray($this->_struct);
		$this->_count=count($this->_struct);
		$this->free();
		$this->_ErrorType=0;
	}
	
	/**
	* return the data struction
	*
	* @access		public
	* @return		array
	*/
	function getTree()
	{
		//if(!$this->_tree)
		//{
			$i=0;
			//$this->_tree=array();
			//$this->_tree=$this->addNode($this->_tree,$this->_struct[$i]['tag'],(isset($this->_struct[$i]['value'])) ? $this->_struct[$i]['value'] : '',(isset($this->_struct[$i]['attributes'])) ? $this->_struct[$i]['attributes'] : '',$this->getChild($i));
			$tree=array();
			$tree=$this->addNode(
				$tree,
				$this->_struct[$i]['tag'],
				(isset($this->_struct[$i]['value'])) ? $this->_struct[$i]['value'] : '',
				(isset($this->_struct[$i]['attributes'])) ? $this->toAttr($this->_struct[$i]['attributes']) : '',
				$this->getChild($i)
			);
			return $tree;
			//$this->_tree=$tree;
		//}
		//return($this->_tree);
	}
	
	/**
	* recursion the children node data
	*
	* @access		public
	* @param		integer		[$i] the last struct index
	* @return		array
	*/
	function getChild(&$i)
	{
		// contain node data
		$children=array();

		// loop
		while(++$i<$this->_count) {
			// node tag name
			$tagname=$this->_struct[$i]['tag'];
			$value=isset($this->_struct[$i]['value']) ? $this->_struct[$i]['value'] : '';
			$attributes=isset($this->_struct[$i]['attributes']) ? $this->toAttr($this->_struct[$i]['attributes']) : '';

			switch($this->_struct[$i]['type']) {
				case 'open':
					// node has more children
					$child=$this->getChild($i);
					// append the children data to the current node
					$children=$this->addNode($children,$tagname,$value,$attributes,$child);
					break;
				case 'complete':
					// at end of current branch
					$children=$this->addNode($children,$tagname,$value,$attributes);
					break;
				case 'cdata':
					// node has CDATA after one of it's children
					$children[self::FIELD_VALUE] .= $value;
					break;
				case 'close':
					// end of node,return collected data
					return $children;
					break;
			}

		}
		//return $children;
	}
	
	/**
	* Appends some values to an array
	*
	* @access		public
	* @param		array		[$target]
	* @param		string		[$key]
	* @param		string		[$value]
	* @param		array		[$attributes]
	* @param		array		[$inner] the children
	* @return		void
	* @since
	*/
	function addNode($target,$key,$value='',$attributes='',$child='')
	{
		if(!isset($target[$key][self::FIELD_VALUE]) && !isset($target[$key][0])){
			if($child != ''){
				$target[$key]=$child;
			}
			if($attributes != ''){
				foreach($attributes as $k => $v){
					$target[$key][$k]=$v;
				}
			}

			$target[$key][self::FIELD_VALUE]=$value;
		}
		else{
			if(!isset($target[$key][0])){
				// is string or other
				$oldvalue=$target[$key];
				$target[$key]=array();
				$target[$key][0]=$oldvalue;
				$index=1;
			}
			else{
				// is array
				$index=count($target[$key]);
			}

			if($child != ''){
				$target[$key][$index]=$child;
			}

			if($attributes != ''){
				foreach($attributes as $k => $v){
					$target[$key][$index][$k]=$v;
				}
			}
			$target[$key][$index][self::FIELD_VALUE]=$value;
		}
		return $target;
	}
	
	
	function toAttr($attrs)
	{
		$re=array();
		if($attrs){
			foreach($attrs as $k=>$v){
				$re['@'.$k]=$v;
			}
		}
		return $re;
	}
	
}
?>