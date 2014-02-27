<?
defined('THEME_APP')||define('THEME_APP','mm');
class ChannelMmBasic extends WebPortalBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	
	public $tableList,$tableItem,$treeView;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableList,$this->tableItem,$this->treeView);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initer()
	{
		
	}
	
	public function doAuth()
	{
		$this->doAuthed();
	}
	
	public function uInit()
	{
		if($this->isuInit)return;$this->isuInit=true;
		$this->ua->dataLoader();
	}
	public function uInfo($k)
	{
		if(!$this->isuInit)$this->uInit();
		return $this->ua->getData($k);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->initPages();
		$this->theme->setTheme('mm');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		$this->theme->doCacheFilterLoop('item','cpo.tableItem');
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterTree('info','cpo.','uInfo');
	}
	
	
}
?>