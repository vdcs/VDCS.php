<?
class CommonPaPaymentReturn extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$tradeno=queryx('tradeno');
		if(!$tradeno){
			$this->setStatus('tradeno');
			return;
		}
		$that->treePayment=PaymentAction::getTree($tradeno);
		if($that->treePayment->getCount()<1){
			$that->theme->setStatus('fail');
			return;
		}
		
		$url_back=PaymentCommon::toBackURL($that->treePayment);
		$this->treeVar->addItem('url_back',$url_back);
		
		//debugTree($that->treePayment);
	}
	
	public function doThemeCache(&$that)
	{
		$this->theme->doCacheFilterTree('payment','cpo.treePayment');
	}
	
}
?>