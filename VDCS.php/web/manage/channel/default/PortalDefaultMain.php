<?
class PortalDefaultMain extends PortalDefaultBase
{
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setDebug(false);
	}
	
	public function doLoad()
	{
		$this->theme->setChannel('default');
		$this->theme->setDir('channel');
		$this->theme->setPage('main');
	}
	
	public function doParse()
	{
		
	}
	
	public function doThemeCache()
	{
		
	}
	
}
?>