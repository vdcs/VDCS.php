<?
class ChannelPassportBasic extends WebPortalBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	
	public $tableList,$tableItem;
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->tableList,$this->tableItem);
	}
	
	/*
	public function setVars($k,$v)
	{
		global $ctl;
		$this->setVar($k,$v);
		$ctl->addVar($k,$v);
	}
	
	protected function doLoginCheck()
	{
		$this->ua->doInit();
		$this->ua->doLoginCheck();
		if($this->ua->isLogin()){
			go($this->ua->getURL('referer'));
		}
		else{
			
		}
	}
	
	protected function doLogout()
	{
		$this->ua->doLogout();
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		$this->theme->doCacheFilterLoop('item','cpo.tableItem');
		//$this->theme->doCacheFilterLoop('menu','cpo.tableMenu');
	}
	
}
?>