<?
class PagePortal extends ManagePortalBase
{
	use PortalRefBase;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	//####################
	protected function parseList()
	{
		$this->doList();
	}
	
	protected function parseView()
	{
		$this->treeView=CommonComment::getTree($this->id);
	}
	
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>