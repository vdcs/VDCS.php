<?
class PageLoginPortal extends ManagePortalBase
{
	
	public function doAuth()
	{
		$this->doAuther();
		$this->ip=DCS::ip();
		$this->addVar('ip',$this->ip);
		$this->isban=false;
		$safeip=$this->config('common.safeip');
		//debugx($safeip);
		if($safeip){
			if(!$this->isBanIP($safeip)){
				//debugx('BAN IP: '.$this->ip);
				$this->isban=true;
			}
		}
		//debugx('<!--'.$this->ip.'-->');
	}
	public function isBanIP($safeip)
	{
		$re=false;
		$ip = $this->ip;
		$arys = toSplit($safeip,',');
		foreach($arys as $_ip){
			if(ins(','.$ip.'.', ','.$_ip.'.') > 0){
				$re=true;
				break;
			}
		}
		return $re;
	}

	public function doInit()
	{
		$this->theme->setMode('common');
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setAuth(false);
		$this->setDebug(false);
	}
	
	public function doLoad()
	{
		$this->theme->setDir('login');
		$this->theme->setPage('login');
		if($this->isban){
			$this->theme->setPage('banip');
		}
	}
	
	public function doParse()
	{
		if($this->isban) return;
		if($this->action=='logout'){
			$this->doLogout();
		}
		if($this->ma->isLogin()){
			mgo(appURL('manage.welcome'));
		}
	}
	
	public function doLogout()
	{
		$this->ma->doDataClear();
		$this->ma->setAuth(1);
		$this->ma->doAuth();
	}
}
?>