<?
class utilError
{
	protected $_data=array(),$_field=array(),$_code=array();
	protected $_count=0,$_goback=true,$_iserror=false;
	
	public function __construct(){}
	public function __destruct(){unset($this->_data);}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit(){$this->doClear();}
	public function doClear()
	{
		$this->_data=array();$this->_field=array();$this->_code=array();
		$this->_count=0;
		$this->_goback=true;
		$this->_iserror=false;
	}
	
	public function isCheck(){return $this->_iserror;}
	
	public function toString($smb=NEWLINE){return implode($smb,$this->_data);}
	
	public function getField(){return $this->_field[0];}
	public function getFields($smb=','){return implode($smb,$this->_field);}
	public function getCode(){return $this->_code[0];}
	public function getCodes($smb=','){return implode($smb,$this->_code);}
	public function getDatas($spt='$$$',$smb=',')
	{
		$re='';
		if($this->isCheck()){
			$re=implode($smb,$this->_field).$spt.implode($smb,$this->_code).$spt.implode($smb,$this->_data);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function addItem($msg,$field=null,$code=null)
	{
		if(!$msg && !$status) return;
		!is_null($msg) && array_push($this->_data,$msg);
		!is_null($field) && array_push($this->_field,$field);
		!is_null($code) && array_push($this->_code,$code);
		$this->_count++;
		$this->_iserror=true;
	}
	
	public function addItems($s)
	{
		if($s){
			$ar=$s; if(!is_array($ar)) $ar=explode(NEWLINE,$ar);
			for($i=0;$i<count($ar);$i++){$this->addItem($ar[$i]);}
		}
	}
	
	public function toList($cname='error')
	{
		if($cname) $cnames=' class="'.$cname.'"';
		$re='<ul'.$cnames.'>';
		for($i=0;$i<$this->_count;$i++){
			$re.=NEWLINE.'<li>'.$this->_data[$i].'</li>';
		}
		$re.=NEWLINE.'</ul>';
		return $re;
	}
	
	public function toJS()
	{
		$re='';
		for($i=0;$i<$this->_count;$i++){
			$re.=$this->_data[$i].'\\n';
		}
		$re=rtrim($re,'\\n');
		return $re;
	}
	public function toJSString()
	{
		$re='';
		for($i=0;$i<$this->_count;$i++){
			$re.='$$$'.$this->_data[$i];
		}
		if($re) $re=substr($re,3);
		$re=r($re,"\n",'\n');
		$re=r($re,"\r",'');
		return $re;
	}
	
	
}
