<?
require_once(VDCS_CD_PATH.'ApiServeX.php');
class ApiEntryBase extends ApiServeX
{
	

	/*
	########################################
	########################################
	*/
	public function isStat($status=null){if(!is_null($status))$this->_var['_stat.is']=$status;return $this->_var['_stat.is'];}
	protected function getExec($t=1,$len=4)
	{
		$_time=microtime(1)-$this->_var['_stat.begin'];
		return($t==1) ? number_format($_time,$len) : number_format($_time*1000,$len);
	}
	
	public function isLocali()
	{
		if(!DCS::isLocali()){
			$this->setStatus('nolocal');
			return false;
		}
		return true;
	}
	
}
