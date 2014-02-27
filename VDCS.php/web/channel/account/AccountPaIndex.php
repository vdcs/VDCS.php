<?
class AccountPaIndex extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$page=queryx('page');
		if($page) $this->theme->setAction($page);
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
