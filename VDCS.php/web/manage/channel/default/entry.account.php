<?
class PagePortal extends PortalDefaultBase
{
	
	protected function parsePassword()
	{
		$this->loadPages();
		$this->pages->setFormFile($this->action);
		$this->loadPagesForm();
		$this->doPagesFormParse();
	}
	
}
?>