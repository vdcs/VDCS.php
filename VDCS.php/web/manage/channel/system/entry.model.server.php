<?
class PagePortal extends ManagePortalBase
{
	use PortalSystemModelServerRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseList()
	{
		if(!$this->refListLoad()) return;
		$this->doList();
	}
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseDel()
	{
		
	}
	
}
?>