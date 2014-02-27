<?
class ChannelCommonTest extends ChannelCommonBase
{
	
	public function doLoad()
	{
		$this->cfg->setTitle('测试页');
		
		$_page=query('page');
		$this->theme->setChannelDir('_page');
		$this->theme->setSubDir('test');
		$this->theme->setPage('test');
		if($_page) $this->theme->setModule($_page);
		
	}
}
