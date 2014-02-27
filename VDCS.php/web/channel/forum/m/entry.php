<?
class PagePortal extends ManagePortalBase
{
	use PortalForumRef;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	protected function parseAdd()
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
	
	protected function parseView()
	{
		$this->refViewLoad();
	}
	
	
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>