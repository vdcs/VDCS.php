<?
class DcodeSMS
{
	const TYPES			= 'sms';
	const VALID_EXPIRE_SEND		= 60;
	const VALID_EXPIRE_USE		= 600;
	
	
	public static function create($module,$code,$value,$params=null)
	{
		return Dcode::create(self::TYPES,$module,$code,$value,$params);
	}
	
	public static function isValid($module,$code,$value='',$params=null,&$treeCode=null)
	{
		$re=0;
		$treeCode=Dcode::getTree(self::TYPES,$module,$code,$value,$params);
		if($treeCode->getCount()<1) return $re;
		//debugTree($treeCode);
		$status=$treeCode->getItemInt('status');
		if($status!=1) return $status;
		$re=1;
		return $re;
	}
	public static function isValidSend($module,$code,$value='',$params=null)
	{
		$treeCode=null;
		$re=self::isValid($module,$code,$value,$params,$treeCode);
		$tim=$treeCode->getItemInt('tim');
		//debugx($tim.'-'.(DCS::timer()-$tim).'-'.self::VALID_EXPIRE_SEND);
		if((DCS::timer()-$tim)<self::VALID_EXPIRE_SEND) $re=3;		//发送期过短
		//debugx('isValidSend='.$re);
		return $re;
	}
	public static function isValidUse($module,$code,$value='',$params=null)
	{
		$treeCode=null;
		$re=self::isValid($module,$code,$value,$params,$treeCode);
		if($re<1) return $re;
		$tim=$treeCode->getItemInt('tim');
		//debugx($tim.'-'.(DCS::timer()-$tim).'-'.self::VALID_EXPIRE_USE);
		if((DCS::timer()-$tim)>self::VALID_EXPIRE_USE) $re=2;		//过期
		//debugx('isValidUse='.$re);
		return $re;
	}
	
	public static function used($module,$code,$value='',$params=null)
	{
		return Dcode::used(self::TYPES,$module,$code,$value,$params);
	}
	
}
?>