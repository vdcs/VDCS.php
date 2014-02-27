<?
class PaymentAlipayVerify
{
	/**
	 * HTTPS形式消息验证地址
	 */
	const https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * HTTP形式消息验证地址
	 */
	const http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	
	const FILTER_PARAMS = 'router,cp,p,m,mi,ap,am,ami';

	/**
	 * 针对notify_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	/*
	_POST
	[discount] => 0.00
	[payment_type] => 1
	[subject] => 充值
	[trade_no] => 2013091037265791
	[buyer_email] => ranom@qq.com
	[gmt_create] => 2013-09-10 14:00:40
	[notify_type] => trade_status_sync
	[quantity] => 1
	[out_trade_no] => 20130910-140040250
	[seller_id] => 2088301677840930
	[notify_time] => 2013-09-10 14:00:47
	[body] => 充值 0.01 元
	[trade_status] => TRADE_SUCCESS
	[is_total_fee_adjust] => N
	[total_fee] => 0.01
	[gmt_payment] => 2013-09-10 14:00:47
	[seller_email] => alipay@7x24.cn
	[price] => 0.01
	[buyer_id] => 2088001083919911
	[notify_id] => ba1dc98ed3c44e85a6dc3389246d4e4a72
	[use_coupon] => N
	[sign_type] => MD5
	[sign] => 3bbcb852cbeb3ebcfc06fe32bdaff796
	*/
	public static function isNotify()
	{
		if(empty($_POST)) return false;		//判断POST来的数组是否为空
		//\?cp=$1&p=pa&ap=$3&am=
		$ary_data=array();
		while(list($key, $val) = each($_POST)){
			if(inp(self::FILTER_PARAMS,$key)<1) $ary_data[$key]=$val;
		}
		
		//生成签名结果
		$isSign = self::getSignVeryfy($ary_data, $_POST["sign"]);		//$_POST
		//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
		$responseTxt = 'true';
		$notify_id=$_POST["notify_id"];
		if (! empty($notify_id)){
			if(PaymentAlipayNotify::is('notify',$notify_id)){
				$responseTxt='true';
			}
			else{
				$responseTxt = self::getResponse($notify_id);
				if(preg_match("/true$/i",$responseTxt)){
					PaymentAlipayNotify::save('notify',$ary_data);
				}
			}
		}
		
		//写日志记录
		//if ($isSign) {
		//	$isSignStr = 'true';
		//}
		//else {
		//	$isSignStr = 'false';
		//}
		//$log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
		//$log_text = $log_text.createLinkString($_POST);
		//logResult($log_text);
		
		//验证
		//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
		//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
		if (preg_match("/true$/i",$responseTxt) && $isSign) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * 针对return_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	/*
	_GET
	/common/payment/alipay/return.html?
	[body] => 充值 0.01 元
	[buyer_email] => ranom@qq.com
	[buyer_id] => 2088001083919911
	[exterface] => create_direct_pay_by_user
	[is_success] => T
	[notify_id] => RqPnCoPT3K9%2Fvwbh3I72IdXmpwm3V4uUcgF%2FCX2KvYaceKnjaff3KzkiS2iQA0JEaFjS
	[notify_time] => 2013-09-10 14:00:51
	[notify_type] => trade_status_sync
	[out_trade_no] => 20130910-140040250
	[payment_type] => 1
	[seller_email] => alipay@7x24.cn
	[seller_id] => 2088301677840930
	[subject] => 充值
	[total_fee] => 0.01
	[trade_no] => 2013091037265791
	[trade_status] => TRADE_SUCCESS
	[sign] => d7c0c485f55e33fae314beb9a5ada9fb
	[sign_type] => MD5
	*/
	public static function isReturn()
	{
		if(empty($_GET)) return false;		//判断POST来的数组是否为空
		//\?cp=$1&p=pa&ap=$3&am=
		$ary_data=array();
		while(list($key, $val) = each($_GET)){
			if(inp(self::FILTER_PARAMS,$key)<1) $ary_data[$key]=$val;
		}
		//debuga($ary_data);
		//phpinfo();

		//生成签名结果
		$isSign = self::getSignVeryfy($ary_data, $_GET["sign"]);		//$_GET
		//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
		$responseTxt = 'true';
		$notify_id=$_GET["notify_id"];
		if(!empty($notify_id)){
			if(PaymentAlipayNotify::is('return',$notify_id)){
				$responseTxt='true';
			}
			else{
				$responseTxt = self::getResponse($notify_id);
				if(preg_match("/true$/i",$responseTxt)){
					PaymentAlipayNotify::save('return',$ary_data);
				}
			}
		}
		
		//写日志记录
		//if ($isSign) {
		//	$isSignStr = 'true';
		//}
		//else {
		//	$isSignStr = 'false';
		//}
		//$log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
		//$log_text = $log_text.createLinkString($_GET);
		//logResult($log_text);
		
		//验证
		//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
		//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
		if (preg_match("/true$/i",$responseTxt) && $isSign) {
			return true;
		} else {
			//if(!$isSign) debugx('!isSign');
			//if(!$responseTxt) debugx('!responseTxt');
			return false;
		}
	}
	
	
	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	public static function getSignVeryfy($para_temp, $sign)
	{
		//除去待签名参数数组中的空值和签名参数
		$para_filter = PaymentAlipayCore::paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = PaymentAlipayCore::argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = PaymentAlipayCore::createLinkstring($para_sort);
		
		$isSgin = false;
		switch (strtoupper(trim(PaymentAlipayAction::SIGN_TYPE))) {
			case 'MD5' :
				//debugx('md5');
				$isSgin = PaymentAlipayCore::md5Verify($prestr, $sign, PaymentAlipayAction::getConfig('key'));
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}

	/**
	 * 获取远程服务器ATN结果,验证返回URL
	 * @param $notify_id 通知校验ID
	 * @return 服务器ATN结果
	 * 验证结果集：
	 * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
	 * true 返回正确信息
	 * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
	 */
	public static function getResponse($notify_id)
	{
		$transport = strtolower(trim(PaymentAlipayAction::transport()));
		$partner = trim(PaymentAlipayAction::getConfig('pid'));
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = self::https_verify_url;
		}
		else {
			$veryfy_url = self::http_verify_url;
		}
		$veryfy_url = $veryfy_url.'partner=' . $partner . '&notify_id=' . $notify_id;
		//debugx($veryfy_url);
		$responseTxt = PaymentAlipayCore::getHttpResponseGET($veryfy_url, PaymentAlipayAction::cacertPath());
		//debugx('responseTxt:'.$responseTxt);
		return $responseTxt;
	}
	
}
