<?
class utilMap extends utilMapBase
{
	private $_data=array();
	private $_fields='',$_len=-1,$_n=-1,$_i=-1,$_isNext=false,$_isLast=false;
	
	public function __construct()
	{
		
	}
	public function __destruct() { unsetr($this->_data); }
	
	public function doDestroy() { }
	
	
	/*
	########################################
	########################################
	*/
	protected function init()
	{
		$this->_n=0;
		$this->_len=0;
		$this->_data[0]='key,value,type';
		/*
		if($fields){
			$this->_fields=is_array($fields) ? utilString::toStr($fields) : $fields;
			$this->_data[0]=$this->_fields;
			$this->_col=count(utilString::toAry($this->_fields));
		}
		*/
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isMap() { return $this->_col ? true : false; }
	public function isObj() { return $this->_col ? true : false; }
	public function isData() { return $this->_col ? true : false; }
	
	public function getCount() { return $this->_len; }
	public function getLength() { return $this->_len; }
	public function getI() { return $this->_i; }
	
	
	/*
	########################################
	########################################
	*/
	public function doItemBegin() { $this->_n=1; $this->_i=0; $this->_isLast=false; }
	public function doItemEnd() { $this->_n=$this->_len; $this->_i=$this->_len-1; $this->_isLast=true; }
	public function doItemMove($n=1) { if(($this->_n+$n)<=$this->_len) $this->_n+=$n; }
	
	public function doBegin() { $this->doItemBegin(); }
	public function doMove($n=1) { $this->doItemMove($n); $this->_i+=$n; }
	
	public function isNext()
	{
		if($this->_len>0 && $this->_i<$this->_len){
			if($this->_i>0) $this->doItemMove();
			$this->_i++;
			return true;
		}
		else{
			return false;
		}
	}
	public function isLast() { return $this->_isLast; }
	
	
	/*
	########################################
	########################################
	*/
	public function addItem($key,$value,$type='')
	{
		if(ins($this->_fields,$key)>0) return;
		if($this->_len<0) $this->init();
		$this->_n++;
		$this->_len++;
		$this->_data[$this->_len]=array('key'=>$key,'value'=>$value,'type'=>$type);
		if($this->_fields) $this->_fields.=','.$key;
		else $this->_fields=$key;
	}
	
	public function setItem($key,$value,$type='')
	{
		for($i=0;$i<$this->_len;$i++){
			if($this->_data[$i]['key']==$key){
				$this->_data[$i]['value']=$value;
				if($type) $this->_data[$i]['type']=$type;
			}
		}
	}
	
	public function delItem($key)
	{
		for($i=0;$i<$this->_len;$i++){
			if($this->_data[$i]['key']==$key){
				unset($this->_data[$i]);
				$this->_len--;
			}
		}
	}
	
	
	public function getItem($key,$v='value')
	{
		$re='';
		for($i=1;$i<=$this->_len;$i++){
			if($this->_data[$i]['key']==$key){
				$re=$this->_data[$i][$v];
			}
		}
		return $re;
	}
	
	public function getItemTree($key=null,$v='value'){$re=$key?$this->getItem($key,'value'):$this->getItemValue($v);if(!isTree($re)) $re=newTree();return $re;}
	public function getItemTable($key=null,$v='value'){$re=$key?$this->getItem($key,'value'):$this->getItemValue($v);if(!isTable($re)) $re=newTable();return $re;}
	public function getItemXCML($key=null,$v='value'){$re=$key?$this->getItem($key,'value'):$this->getItemValue($v);if(!isXCML($re)) $re=newXCML();return $re;}
	
	public function getItemValue($v='value')
	{
		return ($this->_n>0) ? $this->_data[$this->_n][$v] : '';
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getFields(){return $this->_fields;}
	
}
?>