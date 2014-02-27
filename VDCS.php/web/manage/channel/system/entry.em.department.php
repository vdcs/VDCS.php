<?
class PagePortal extends ManagePortalBase
{
	use PortalEmDepartmentRef;
	
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
	
	
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>