<?
class PagePortal extends PortalContent
{
	
	public function doInitPos()
	{
		$this->setFieldMode('');
	}
	
	public function doLoadPos()
	{
		$this->theme->setPage($this->_p_);
	}
	
}
?>