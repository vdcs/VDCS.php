<?
class CommonPaPaymentAlipayReturn extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$islog=DCS::isLocal();
		$isdebug=DCS::isLocal();
		
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		if($islog) ResTest::logAction('alipay_return');
		
		$verify_result=PaymentAlipayVerify::isReturn();
		if(!$verify_result){
			$this->theme->setModule('');
			$this->theme->setModulei('return');
			$this->theme->setStatus('fail');
			//$this->addVar('message','验证错误');
			//debugx('fail');
			return;
		}
		$out_trade_no = $_GET['out_trade_no'];			//商户订单号
		//$trade_no = $_GET['trade_no'];				//支付宝交易号
		$trade_status = $_GET['trade_status'];			//交易状态
		
		if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
			//debugx('traded');
			
		}
		
		$tradeno=$out_trade_no;
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