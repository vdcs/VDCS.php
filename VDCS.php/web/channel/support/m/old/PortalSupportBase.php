<?
class PortalSupportBase extends ManagePortalBase
{
	
	public function doInit()
	{
		//$this->_m_=$this->_p_;
	}
	
	public function doLoadPre()
	{
		$this->theme->setSubdir('support');
		$this->theme->setPage($this->_m_);
	}
	
}
