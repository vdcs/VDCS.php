<?
class UaE
{
	
	public static function autoLogin(&$ua,$uid,$isgo=false,&$gourl='')
	{
		$re='init';
		if(!$uid) return $re;
		$re='nodata';
		$mpivotal=new UcPivotalManage();
		$mpivotal->setURC($ua->rc);
		$mpivotal->setUID($uid);
		$mpivotal->loadData();
		if($mpivotal->isData()){
			$re='nologin';
			$_password=$mpivotal->getData('password');
			//debugx($uid.','.$_password);
			$ua->setID($uid);
			$ua->setData('_password',$_password);
			$ua->setData('_remember','');
			$ua->doLoginCheck();
			$ua->save();
			if($ua->isLogin()){
				$gourl=$ua->getURL('referer');
				if($isgo) go($gourl);
				$re='succeed';
			}
		}
		return $re;
	}
	
	
}
?>