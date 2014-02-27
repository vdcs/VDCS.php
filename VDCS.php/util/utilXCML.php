<?
class utilXCML extends XCML
{
	private $_data=array();
	public $_dataXML,$_dataArrays,$_dataArray;
	
	const FIELD_VALUE			= '__value';		//???
	
	
	public function __construct()
	{
		$this->_data['isparse']=false;
		$this->_data['error.type']='';
		$this->_data['node.item.name']='';
		$this->_data['n']=1;
	}
	
	public function __destruct()
	{
		$this->doDestroy();
	}
	
	public function doDestroy(){unset($this->_dataXML,$this->_dataArrays,$this->_dataArray);}
	
	
	/*
	########################################
	########################################
	*/
	public function loadXML($re) { $this->loadParse('xml',$re); }
	public function loadFile($re) { $this->loadParse('file',$re); }
	
	private function loadParse($t,$s)
	{
		$xmlParser=new utilXMLParser();
		if($t=='file') $this->_dataXML=@file_get_contents($s);
		else $this->_dataXML=$s;
		$xmlParser->parseString($this->_dataXML);
		$this->_data['isparse']=$xmlParser->isParse();
		$this->_data['error.type']=$xmlParser->getParseError();
		if(!$this->_data['isparse']) return false;
		$this->_dataArrays=$xmlParser->getTree();
		$this->_dataArray=$this->_dataArrays[XCML_ROOT];
		//debugAry($this->_dataArray);
		$this->_data['configures']=$this->_dataArray['configure'];
		$this->_data['configure']['node']=$this->_dataArray['configure']['node'][self::FIELD_VALUE];
		$this->_data['configure']['field']=$this->_dataArray['configure']['field'][self::FIELD_VALUE];
		$this->_data['node.item.name']=$this->_data['configure']['node'];
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isObj() { return $this->_data['isparse']; }
	public function isParse() { return $this->_data['isparse']; }
	public function getErrorType() { return $this->_data['error.type']; }
	
	public function getXML() { return $this->_dataXML; }
	public function getArray() { return $this->_dataArray; }
	
	public function getBasicXCML($k) { return $this->_dataArray[$k]; }
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		
	}
	
	public function doParseNode($n)
	{
		$this->_data['node.item.name']=$n;
		$this->_data['n']=1;
	}
	
	public function doParseItem($item=null)
	{
		if(!$item) $item=$this->_data['configure']['node'];
		$this->_data['node.item.name']=$item;
		if($this->_dataArray[$item]&&!$this->_dataArray[$item][0]){
			$ary=$this->_dataArray[$item];
			$this->_dataArray[$item]=array($ary);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getConfigure($k)
	{
		$ary=explode('/',$k);
		$ar=$this->_dataArray['configure'];
		for($a=0;$a<count($ary);$a++){$ar=$ar[$ary[$a]];if(!is_array($ar)) break;}
		unset($ary);
		return $ar[self::FIELD_VALUE];	//$this->_dataArray['configure'][$k][self::FIELD_VALUE];
	}
	
	public function getConfigureItem() { return $this->_data['configure']['node']; }
	public function getConfigureField() { return $this->_data['configure']['field']; }
	public function getConfigureValue($key) { return $this->_dataArray['configure'][$key][self::FIELD_VALUE]; }
	
	public function getConfigureArray()
	{
		$re=array();
		if($this->_data['configures']){
			//debuga($this->_data['configures']);
			reset($this->_data['configures']);
			for($c=0;$c<count($this->_data['configures']);$c++){
				$k=key($this->_data['configures']);
				if($k!=self::FIELD_VALUE) $re[$k]=$this->_data['configures'][$k][self::FIELD_VALUE];
				next($this->_data['configures']);
			}
		}
		return $re;
	}
	public function getConfigureTree() { $re=new utilTree(); $re->setArray($this->getConfigureArray()); return $re; }
	/*
	public function getNode($re='',$n=1)
	{
		if($re){
			if(!(strpos($re,'@')===false)){
				$fieldatt=substr($re,strpos($re,'@')+1);
				$re=$this->_dataArray[$this->_data['configure']['node'].'__'.$n]['__att__'][$fieldatt];
			}
		}
		else{
			$re=$this->_data['configure']['node'];
		}
		return $re;
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function getItemField() { return $this->_data['node.item.name']; }
	public function getItemCount() { return $this->getItemLength(); }
	public function getItemLength() { return $this->getNodeCount($this->_data['node.item.name']); }
	
	public function getItem($k) { return $this->getItemValues($k,$this->_data['n']); }
	
	public function doItemBegin($n=1) { $this->_data['n']=$n; }
	public function doItemMove($p=1) { $this->_data['n']=$this->_data['n']+$p; }
	
	public function getItemArray()
	{
		$node_=$this->_data['node.item.name'];
		if(!$node_) $node_=$this->_data['configure']['node'];
		return $this->getNodeArray($node_,$this->_data['n']);
	}
	public function getItemTree() { $re=new utilTree(); $re->setArray($this->getItemArray()); return $re; }
	
	public function getItemValues($s,$n=1)
	{
		if(!(strpos($s,'@')===false)){
			$k=substr($s,0,strpos($s,'@'));
			$ka=substr($s,strpos($s,'@')+1);
		}
		else{ $k=$s; }
		$re='';
		$ar=$this->_dataArray[$this->_data['node.item.name']][$n-1];
		if($ka) { $re=$ar[$ka]; } else{ $re=$ar[$k][self::FIELD_VALUE]; }
		return $re;
	}
	
	
	//################ Att #################
	public function getItemAttArray()
	{
		$node_=$this->_data['node.item.name'];
		if(!$node_) $node_=$this->_data['configure']['node'];
		//debugx('=='.$node_.'-'.$this->_data['n']);
		return $this->getNodeAttArray($node_,$this->_data['n']);
	}
	public function getItemAttTree() { $re=new utilTree(); $re->setArray($this->getItemAttArray()); return $re; }
	
	
	/*
	########################################
	########################################
	*/
	public function getNodeArray($node_,$n=1)
	{
		$re=array();
		$ar=$this->_dataArray[$node_][$n-1];
		if($ar==null) $ar=$this->_dataArray[$node_];
		//debugAry($ar);
		if($ar!=null){
			/*
			if(!@reset($ar)){
				debugx($node_);
				print_r($this->_dataArrays);
				debugx($this->_dataXML);
			}
			*/
			reset($ar);
			for($c=0;$c<count($ar);$c++){
				$k=key($ar);
				//debugx(($k.'-'.self::FIELD_VALUE);
				if($k!=self::FIELD_VALUE && left($k,1)!='@') $re[$k]=is_array($ar[$k])?$ar[$k][self::FIELD_VALUE]:$ar[$k];
				next($ar);
			}
		}
		return $re;
	}
	public function getNodeTree() { $re=new utilTree(); $re->setArray($this->getNodeArray()); return $re; }
	
	public function getNodeCount($strNode) { $re=0; if($strNode) { $re=($this->_dataArray[$strNode][0])?count($this->_dataArray[$strNode]):0; } return $re; }
	
	
	public function getNodeAttArray($node_,$n=1)
	{
		$re=array();
		$ar=$this->_dataArray[$node_][$n-1];
		if($ar==null) $ar=$this->_dataArray[$node_];
		//debugAry($ar);
		if($ar!=null){
			reset($ar);
			for($c=0;$c<count($ar);$c++){
				$k=key($ar);
				if(left($k,1)=='@') $re[toSubstr($k,2)]=$ar[$k];
				next($ar);
			}
		}
		return $re;
	}
	public function getNodeAttTree($node_,$n=1) { $re=new utilTree(); $re->setArray($this->getNodeAttArray($node_,$n)); return $re; }
	
	
	/*
	########################################
	########################################
	*/
	public static function toTrimArray($ar) { foreach($ar as $k=>$v) { if(is_array($v)) { $strary[$k]=$this->toTrimArray($v); } else { if($v=="\n\t\t") $ar[$k]=''; } } return $ar; }
	public static function toTrim($s) { $re=$s; if($re=="\n\t\t") $re=''; return $re; }
}
?>