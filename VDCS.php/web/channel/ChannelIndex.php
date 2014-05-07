<?
class ChannelIndex extends WebPortalBase
{
	
	public function doLoad()
	{
		//$this->cfg->setTitle('首页');
		$_page='index';
		$_page=queryString();
		if(!isx($_page)) $_page='';
		if(!$_page) $_page='index';
		$this->theme->setPage($_page);
		
	}
}
