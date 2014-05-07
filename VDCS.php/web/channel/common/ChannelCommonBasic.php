<?
class ChannelCommonBasic extends WebPortalBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	
	public $tableMenu=null;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->tableMenu);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->loadPages();
		
		//$this->theme->setPage($this->_m_);
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->output=CommonTheme::toCacheFilterLoop($this->theme->output,'menu','cpo.tableMenu');
	}
	
}
