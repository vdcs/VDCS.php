<?
class PagePortal extends ManagePortalBase
{
	use PortalEmStaffRef;
	
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
		
	}
	
	
	//####################
	protected function parsePopedom()
	{
		if(!$this->refPopedomLoad()) return;
		$this->doPagesFormParse();
	}
	
	
	protected function parseViewpopedom()
	{
		if(!$this->refPopedomLoad('view')) return;
		$this->doPagesFormParse();
	}
	
	
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>