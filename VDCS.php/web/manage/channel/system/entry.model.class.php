<?
class PagePortal extends ManagePortalBase
{
	use PortalSystemModelClassRef;

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
	
	protected function parseDel()
	{
		
	}
	
	protected function parseView()
	{
		if(!$this->refViewLoad()) return;
		$this->refViewLoad();
	}
	
	protected function parseList()
	{
		if(!$this->refListLoad()) return;
		$this->doList();
	}
	
}
?>