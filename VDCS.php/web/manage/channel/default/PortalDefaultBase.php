<?
class PortalDefaultBase extends ManagePortalBase
{
	
	public function doInit()
	{
		$this->ruler->setMode('ignore');
	}
	
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->theme->setSubdir('default');
		//$this->theme->setPage($this->_m_);
	}
	
}
?>