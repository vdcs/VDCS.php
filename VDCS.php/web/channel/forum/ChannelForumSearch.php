<?
class ChannelForumSearch extends ChannelForumBase
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
		$this->cfg->setTitle('now',$this->cfg->v('title.search'));
		$this->cfg->setTitle('sub',$this->cfg->v('title.search.'.$this->_m_));
	}
	
	##############################################
	##############################################
	public function doParse()
	{
		
	}
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		
	}
	
	
}
?>