<?
class ShopExtend
{
	
	/*
	########################################
	########################################
	*/
	public static function getCartSessionName(){$cfg->getChannel().'.cart';}
	
	public static function toProductPrice($price,$discount=0)
	{
		$re=$price;
		if(!$discount) $discount=100;
		if($discount>0 && $discount<100) $re=$price*$discount/100;
		return utilCode::toPrice($re);
	}
	
}
?>