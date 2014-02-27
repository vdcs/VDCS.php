<?
class PaymentHandle
{
	
	public static function newUa($treePayment)
	{
		$uurc=$treePayment->getItem('uurc');
		$uuid=$treePayment->getItem('uuid');
		if($uurc){
			$ua=Ua::obj($uurc);
			$ua->init();
			$ua->setID($uuid);
		}
		return $ua;
	}
	
	
	
}
