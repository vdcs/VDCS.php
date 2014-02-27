<?
class PortalSystemBase extends ManagePageBase
{
	public function doInit()
	{
		$this->ruler->setMode('kernel');
		//$this->_m_=$this->_p_;
	}
	
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->theme->setSubdir('system');
		$this->theme->setPage($this->_m_);
	}
}
?>