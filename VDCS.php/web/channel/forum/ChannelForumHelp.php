<?
class ChannelForumHelp extends ChannelForumBase
{
	public $faq=null;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->faq);
	}
	
	public function doLoad()
	{
		$this->faq=new WebPageFaq;
		$this->faq->setChannel($this->cfg->getChannel());
		$this->faq->setPage($this->_p_);
		$this->faq->doLoad();
	}
	
	public function doParse()
	{
		parent::doParse();
		$this->faq->doParse();
	}
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		$this->theme->output=$this->faq->toDTMLCache($this->theme->output,'cpo.faq');
	}
	
}
?>