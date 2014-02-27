<?
class PagePortal extends ManagePortalBase
{
	use PortalRefBase,PortalRefAction;
	use PortalManagerRefPopedom;

	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	//####################
	protected function refFormLoad()
	{
		//$this->theme->setPrefix($this->modules.'.');
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
	
	//####################
	protected function parsePopedom()
	{
		if(!$this->refPopedomLoad()) return;
		$this->doPagesFormParse();
	}
	
	//####################
	protected function parseList()
	{
		$this->doList();
	}
	
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
