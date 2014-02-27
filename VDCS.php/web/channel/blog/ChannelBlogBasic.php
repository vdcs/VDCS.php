<?
class ChannelBlogBasic extends WebPortalBase
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
		//$cfg->doSpecialInit();
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
	}
	
}
?>