<?
class PagePortal extends ManagePortalBase
{
	use PortalTMlogRef;
	
	
	public function doLoad()
	{
		//$this->theme->setPage($this->channel);
		$this->refLoad();
	}
	
	public function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseList()
	{
		$this->doList();
	}
	protected function parseQdomain()
	{
		
	}
	protected function parseView()
	{
		//$this->doList();
		$this->refViewLoad();
	}
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>