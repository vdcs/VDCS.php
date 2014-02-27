<?
class ChannelForumModerator extends ChannelForumBase
{
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		$this->setForumid();
		
		$this->cfg->setTitle('now',$this->cfg->v('title.moderator'));
		$this->cfg->setTitle('sub',$this->cfg->v('title.moderator.'.$this->_m_));
	}
	
	##############################################
	##############################################
	public function doParse()
	{
		$this->doLoadDTMLAtt();
	}

	public function doThemeCache()
	{
		parent::doThemeCache();
		
	}
	
	
}
?>