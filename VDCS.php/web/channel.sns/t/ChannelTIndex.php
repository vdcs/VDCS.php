<?
class ChannelTIndex extends ChannelTBase
{
	
	public function doLoad()
	{
		$page='plaza';
		if($this->ua->isLogin()){
			$home=$this->ua->getData('home');
			//dcsLog('home:',$home);
			if(!$home) $home='home';
			go(appURL($home));
		}
		go(appURL($page));
	}
	
}
?>