<?
class PaymentHandleAccount extends PaymentHandle
{
	
	public static function parserRecharge($treePayment)
	{
		$money=$treePayment->getItemNum('money');
		
		$ua=self::newUa($treePayment);
		//debuga($ua->_data);
		//$ua->update('money=money+'.$money);
		$params=array();
		$params['payment']='online';
		UcaMoney::recharge($ua,$money,$params);
	}
	
}
