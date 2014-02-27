<?
class UcaBillHandle
{	
	public static function parserReturn($id)
	{
		$_status=0;
		$treeBill=newTree();
		$treeBill=UcaBill::getTree($id);
		if($treeBill->getCount()<1) return $_status;
		
		$module=$treeBill->getItem('module');
		$ispay=$treeBill->getItemInt('ispay');
		if($ispay==1) $payment='payed';
		if($ispay==2) $payment='inpay';
		if($ispay==0) $payment='unpay';
		
		$objectpa='UcaBillHandle'.ucfirst($module);
		if(_autoload_::isReal($objectpa)){
			$funcname='parser'.ucfirst($payment);
			if(method_exists($objectpa,$funcname)){
				$_status=$objectpa::$funcname($treeBill);
			}
		}
		return $_status;
	}
		
}
