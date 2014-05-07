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
		$this->cfg->setTitle('chn',$this->cfg->v('title'));
		
		$this->cfg->doClassInit();
		$this->cfg->doSpecialInit();
		$this->tableYear=CommonChannelExtend::getYearTable($this->cfg->getChannel(),$this->cfg->chn->getSQLStruct('table.name'),$this->cfg->chn->getSQLStruct('table.px').'tim');
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('year','cpo.tableYear');
	}
	
}
