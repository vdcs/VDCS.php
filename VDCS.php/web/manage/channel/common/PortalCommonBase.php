<?
class PortalCommonBase extends ManagePortalBase
{
	
	public function doInit()
	{
		$this->ruler->setMode('ignore');
	}
	
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->theme->setSubdir('common');
		//$this->theme->setPage($this->_m_);
	}
	
}
?>