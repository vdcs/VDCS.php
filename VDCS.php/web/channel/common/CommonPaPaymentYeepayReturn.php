<?
class CommonPaPaymentYeepayReturn extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$islog=DCS::isLocal();
		$isdebug=DCS::isLocal();
		
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		if($islog) ResTest::logAction('yeepay_return');
		
		$verify_result=PaymentYeepayAction::isVerifyReturn($btype,$tradeno);
		if(!$verify_result){
			$this->theme->setModule('');
			$this->theme->setModulei('return');
			$this->theme->setStatus('fail');
			$this->addVar('message','验证错误');
			//debugx('fail');
			return;
		}
		
		debugx($tradeno);

		$this->treeVar->addItem('tradeno',$tradeno);
		//if($isdebug) debugx("trade_status=".$trade_status);
		PaymentAction::parserReturn($tradeno);
		
		$url=$this->cfg->toLinkURL('pam','p=payment&m=return');
		//debugx($url);
		$url=DCS::urlLink($url,'tradeno='.$tradeno);
		$this->treeVar->addItem('url_return',$url);
		go($url);
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
?>