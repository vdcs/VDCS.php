<?
require_once(VDCS_CD_PATH.'ApiServeXCML.php');
class ApiEntryBase extends ApiServeXCML
{
	
	/*
	########################################
	########################################
	*/
	public function serveInit()
	{
		define('DEBUG_TYPE',1);
		if(inp('j,json,xj',PAGE_X)>0){
			if(queryx('debug')!='show') debugSet(false,false);
			require_once(VDCS_CD_PATH.'WebServeJSON.php');
			$this->serve=new WebServeJSON();
		}
		else{
			require_once(VDCS_CD_PATH.'WebServeXCML.php');
			$this->serve=new WebServeXCML();
		}
		$this->serve->putHead();
		$this->_var['_stat.begin']=timer();
	}
	public function serveParse()
	{
		$this->putStat();
		$this->doOutput();
		$this->putDebug();
	}
	
	public function putStat()
	{
		if($this->_var['_stat.is']){
			//$this->serve->addVar('stat.exec.tim',microtime(1)-$this->_var['_stat.begin']);
			$this->serve->addVar('stat.exec',$this->getExec());
		}
	}
	public function putDebug()
	{
		if(query('debug')){
			debugx('REQUEST URI = '.$_SERVER['REQUEST_URI']);
			debugx('Script Info = '.$_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING']);
			debugx('BROWSER URL = '.DCS::browseURL(true));
			debugx('Processed in '.dcsExecTime().' s, '.DB::getTotal().' queries. Gzip '.dcsGzipStatus().', Memory usage '.dcsMemoryUsage().'.');
		}
	}
	
	protected function doOutput()
	{
		$this->serve->putData();
	}
	
	
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
