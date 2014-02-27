<?
class ChannelTBasic extends WebPortalBase
{
	//public $tableYear=null;
	public function __construct(){}
	public function __destruct()
	{
		//unsetr($this->tableYear);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		//$this->cfg->setTitle('chn',$$this->cfg->v('title'));
		$this->action=queryx('action');
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
	}
	
}
?>