<?
class CommonPaPaymentAlipayNotify extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$islog=true;
		
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		if($islog) ResTest::logAction('alipay_notify');
		
		$verify_result=PaymentAlipayVerify::isNotify();
		if(!$verify_result){
			if($islog) dcsLog('alipay_notify:'.$_POST['trade_no'],'fail');
			$this->treeVar->addItem('notify_status','fail');			//验证失败
			return;
		}
		
		$out_trade_no = $_POST['out_trade_no'];			//商户订单号
		$trade_no = $_POST['trade_no'];				//支付宝交易号
		$trade_status = $_POST['trade_status'];			//交易状态
		if($islog) dcsLog('alipay_notify:'.$_POST['trade_no'],$trade_status);
		
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		
		//注意：
		//该种交易状态只在两种情况下出现
		//1、开通了普通即时到账，买家付款成功后。
		//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
		if($trade_status == 'TRADE_FINISHED'){
			
		}
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
		
		//注意：
		//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
		else if($trade_status == 'TRADE_SUCCESS'){
			
		}
		
		//请根据业务逻辑来处理
		PaymentAction::parserReturn($out_trade_no);
		
		if($islog) dcsLog('alipay_notify:'.$_POST['trade_no'],'success');
		$this->treeVar->addItem('notify_status','success');		//验证成功,请不要修改或删除
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
?>