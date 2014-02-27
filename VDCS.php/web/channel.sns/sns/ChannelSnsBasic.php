<?
class ChannelSnsBasic extends WebPortalBase
{
	public function __construct(){}
	public function __destruct()
	{
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		global $cfg;
		$cfg->setTitle('chn',$cfg->v('title'));
		
		
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
	}
	
}
?>