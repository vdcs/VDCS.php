<?
class PagePortal extends ManagePortalBase
{
	use PortalSystemCommentRef;
	
	
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
		$this->refViewLoad();
	}
	
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>