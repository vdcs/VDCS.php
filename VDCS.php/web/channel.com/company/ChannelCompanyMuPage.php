<?
class ChannelCompanyMuPage extends ChannelCompanyMuBase
{
	protected $PAGE_DEFAULT		= 'about';
	
	public function doLoad()
	{
		
	}
	
	public function doParse()
	{
		global $cfg;
		
		$page=queryx('page');
		$_title=$this->getTitle('mu.'.$page,false);
		if(!$_title){
			$page=$this->PAGE_DEFAULT;
			$_title=$this->getTitle('mu.'.$page);
		}
		$this->theme->setPage('page');
		$this->theme->setModule($page);
		$cfg->setTitle($_title);
		$this->setVar('page',$page);
	}
	
}
?>