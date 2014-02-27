<?
class PagePortal extends ManagePortalBase
{
	use PortalContentDataRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	protected function parseAdd()
	{
		if(!$this->isRoot()) return false;
		if(!$this->refAddLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseEdit()
	{
		if(!$this->isRoot()) return false;
		if(!$this->refEditLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseList()
	{
		if(!$this->refListLoad()) return;
		$this->doList();
	}
	
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
}
?>