<?
//$dcsSession=new VDCS_Session();
/*
	public function getSession($strer) { global $dcsSession; return $dcsSession->getValue($strer); }
	public function setSession($strer,$strvalue) { global $dcsSession; $dcsSession->setValue($strer,$strvalue); }
	public function delSession($strer) { global $dcsSession; $dcsSession->delValue($strer); }
	
	public function getSessionAry() { global $dcsSession; return $dcsSession->getArray(); }
	public function getSessionTree()
	{
		$reTree=new utilTree();
		$reTree->setArray($this->getSessionAry());
		return $reTree;
	}
*/
class DBSession
{
	private $_SessionID=0;
	private $_data=array();
	private $_isread=false;
	private $_table_name='dbs_session';
	
	public function __construct()
	{
		$this->_SessionID=$_COOKIE['PHPSESSID'];
	}
	public function __destruct() { }
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		if(!$this->_isread){
			$tmpSQL="select `key`,`value` from ".$this->_table_name." where sessionid='".$this->_SessionID."'";
			$tmpQuery=DB::query($tmpSQL);
			while($tmpAry=DB::queryArray($tmpQuery)){
				$this->_data[$tmpAry['key']]=$this->toDecodeValue($tmpAry['value']);
			}
			$this->_isread=true;
		}
	}
	
	public function getArray() { $this->doLoad(); $this->_data; }
	
	public function getValue($strKey) { $this->doLoad(); return $this->_data[$strKey]; }
	
	public function setValue($strKey,$strValue)
	{
		$this->_data[$strKey]=$strValue;
		$tmpSQL="delete from ".$this->_table_name." where sessionid='".$this->_SessionID."' and `key`='".$strKey."'";
		DB::exec($tmpSQL);
		$strValue=$this->toEncodeValue($strValue);
		$strValue=str_replace('\'','\\\'',$strValue);
		$tmpSQL="insert into ".$this->_table_name."(sessionid,`key`,`value`,expires,tim) ";
		$tmpSQL.="values('".$this->_SessionID."','".$strKey."','".$strValue."',0,0)";
		//echo $tmpSQL.'<br>';
		DB::exec($tmpSQL);
	}
	
	public function delValue($strKey) { $this->delValue($strKey); }
	public function doDel($strKey)
	{
		$tmpSQL="delete from ".$this->_table_name." where sessionid='".$this->_SessionID."' and `key`='".$strKey."'";
		DB::exec($tmpSQL);
		$this->_data[$strKey]='';
	}
	
	public function doClear()
	{
		$tmpSQL="delete from ".$this->_table_name." where sessionid='".$this->_SessionID."'";
		DB::exec($tmpSQL);
		$this->_data=array();
	}
	
	
	/*
	########################################
	########################################
	*/
	private function toEncodeValue($re)
	{
		if(is_array($re)) $re='[ary]:'.utilString::toStringByArray($re,';;;;;','=====');
		else if(!is_scalar($re)) $re='';
		return $re;
	}
	
	private function toDecodeValue($re)
	{
		if(toSubstr($re,1,6)=='[ary]:') $re=utilString::toArray(toSubstr($re,7),';;;;;','=====');
		return $re;
	}
}
?>