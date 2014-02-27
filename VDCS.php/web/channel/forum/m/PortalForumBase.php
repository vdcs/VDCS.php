<?
class PortalForumBase extends ManagePageBase
{
	
	public function doInit()
	{
		//$this->_m_=$this->_p_;
	}
	
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->theme->setSubdir('forum');
		$this->theme->setPage($this->_m_);
	}
	
}
?>