<?
class ChannelAccountIndex extends ChannelAccountMy
{
	
	public function doLoad()
	{
		$page=queryx('page');
		if($page) $this->theme->setAction($page);
	}
	
}
?>