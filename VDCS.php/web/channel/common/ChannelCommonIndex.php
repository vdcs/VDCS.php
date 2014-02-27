<?
class ChannelCommonIndex extends ChannelCommonBase
{
	
	public function doLoad()
	{
		$this->theme->setChannelDir('');
		$this->setTitle('chn','');
		//$this->cfg->setTitle('首页');
		/*
		$_page='index';
		//$_page=queryString();
		$_page=queryx('page');
		if(!$_page) $_page='index';
		$this->theme->setChannelDir('');
		$this->theme->setPage($_page);
		*/
	}

	public function doThemer()
	{
		$this->theme->setWeb('channel','index');
		$this->theme->setWeb('portal','');
		$this->theme->setWeb('module','');
	}

}
?>