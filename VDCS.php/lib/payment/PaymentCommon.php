<?
class PaymentCommon
{
	
	public static function getConfigTree($node=null)
	{
		$treeConfig=VDCSDTML::getConfigTree('common.config/data/payment');
		if($node) $treeConfig->doFilter($node.'.');
		return $treeConfig;
	}
	
	
	public static function toBackURL($treePayment)
	{
		$url='';
		$module=$treePayment->getItem('module');
		$type=$treePayment->getItem('type');
		$value=$treePayment->getItem('value');
		switch($module){
			case 'account':
			default:
				switch($type){
					case 'charge':
					default:
						$url=DCS::linkURL('account','pa','p=assets');
						break;
				}
				break;
		}
		return $url;
	}
	
	
}
