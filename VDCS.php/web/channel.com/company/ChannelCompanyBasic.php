<?
class ChannelCompanyBasic extends WebPortalBase
{
	public $tableYear=null;
	public function __destruct()
	{
		unset($this->tableYear);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->cfg->doClassInit();
	}
	
	public function doThemeCacheBasic()
	{
		//$this->theme->doCacheFilterLoop('year','cpo.tableYear');
	}
	
}
?>