<?
class ChannelShopBasic extends WebPortalBase
{
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->cfg->setTitle('chn',$this->cfg->v('title'));
		
		$this->cfg->doClassInit();
	}
	
	public function doThemeCacheBasic()
	{
		//$this->theme->output=CommonTheme::toCacheFilterLoop($this->theme->output,'list','cpo.tableList');
	}
	
}
?>