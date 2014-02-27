<?
class UuPivotal
{
	const SAFE_SECOND			= 100;
	
	public static function isLogin($ua,$pwd,$encrypt_timer=0,&$passwordo='')
	{
		$upivotal=new UcPivotal();
		$upivotal->setURC($ua->rc);
		$upivotal->setUID($ua->id);
		$upivotal->init();
		$upivotal->loadData();
		$_passwordo=$upivotal->getData('password');
		$_password=$_passwordo;
		//debugx($pwd.','.$encrypt_timer.','.$_password);
		$pwd=utilCoder::toMD5i($pwd);
		if($encrypt_timer && (DCS::timer()-$encrypt_timer)<self::SAFE_SECOND){
			$_password=utilCoder::toMD5i($_password.','.$encrypt_timer);
		}
		$re=($pwd==$_password);
		$passwordo=$re?$_passwordo:'';
		unset($upivotal);
		return $re;
	}
	
	public static function getInstance($ua)
	{
		$upivotal=new UcPivotal();
		$upivotal->setURC($ua->rc);
		$upivotal->setUID($ua->id);
		$upivotal->init();
		if($ua->id>0) $upivotal->loadData();
		return $upivotal;
	}

}
?>