<?
class PortalPassportBase extends ManagePageBase
{
	
	public function doInit()
	{
		
	}
	
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->theme->setSubdir('passport');
		$this->theme->setPage($this->_m_);
	}
	
}
?>