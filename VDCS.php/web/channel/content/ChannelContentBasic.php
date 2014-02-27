<?
class ChannelContentBasic extends WebPortalBase
{
	public $tableYear=null;
	public function __construct(){}
	public function __destruct()
	{
		unsetr($this->tableYear);
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
		$this->theme->output=CommonTheme::toCacheFilterLoop($this->theme->output,'year','cpo.tableYear');
	}
	
}
?>