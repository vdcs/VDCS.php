<?
class ChannelArticleBasic extends WebPortalBase
{
	public $tableYear=null;
	public function __construct(){}
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
		global $cfg;
		
		$cfg->setTitle('chn',$cfg->v('title'));
		
		$cfg->doClassInit();
		$cfg->doSpecialInit();
		$this->tableYear=CommonChannelExtend::getYearTable($cfg->getChannel(),$cfg->chn->getSQLStruct('table.name'),$cfg->chn->getSQLStruct('table.px').'tim');
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('year','cpo.tableYear');
	}
	
}
?>