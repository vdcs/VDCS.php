<?
class PagePortal extends ManagePageBase
{
	use PortalRefUaCorp;
	
	public function initSet()
	{
		$this->UURC=$this->_chn_;
	}
	
}
?>