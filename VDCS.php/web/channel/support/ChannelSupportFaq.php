<?
class ChannelSupportFaq extends ChannelSupportBase
{
	public $faq=null;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->faq);
	}
	
	public function doLoad()
	{
		//$this->_p_='faq';
		//$this->setPortal($this->_p_);
		
		$this->faq=new WebPageFaq;
		$this->faq->setChannel($this->cfg->getChannel());
		$this->faq->setPage(queryx('page'));
		$this->faq->setKey(queryx('key'));
		
		$title=$this->cfg->v('title.'.$this->faq->getPage());
		if(len($title)<1){
			$this->faq->setPage('about');
			$title=$this->cfg->v('title.'.$this->faq->getPage());
		}
		$this->cfg->setTitle($title);
		$this->theme->setPage('faq');
		switch($this->faq->getPage()){
			case 'about':
				$this->theme->setModule('about');
				break;
		}
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