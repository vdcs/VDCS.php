<?
class PagePortal extends ManagePortalBase
{
	use PortalUaRefAuthCertif;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}

	protected function parseList()
	{
		$this->doList();
	}
	
}
?>