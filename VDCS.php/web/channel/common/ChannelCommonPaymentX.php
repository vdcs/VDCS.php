<?
class ChannelCommonPaymentX extends ChannelCommonBaseX
{
	
	public function parseStatus()
	{
		$tradeno=queryx('tradeno');
		if(!$tradeno){
			$this->setStatus('tradeno');
			return;
		}
		$this->treePayment=PaymentAction::getTree($tradeno);
		if($this->treePayment->getCount()<1){
			$this->setStatus('tradeno');
			return;
		}
		
		$ishandle=$this->treePayment->getItemInt('ishandle');
		$ispay=$this->treePayment->getItemInt('ispay');
		$this->addVar('name',$this->treePayment->getItem('name'));
		$this->addVar('desc',$this->treePayment->getItem('desc'));
		$this->addVar('ishandle',$ishandle);
		$this->addVar('ispay',$ispay);
		$this->addVar('pay_tim',$this->treePayment->getItem('pay_tim'));
		
		if($ispay<1){
			$this->setStatus('nopay');
			return;
		}
		if($ishandle<1){
			$this->setStatus('nohandle');
			return;
		}
		
		$this->setSucceed();
	}
	
}
?>