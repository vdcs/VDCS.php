<?
class PaymentAlipayAction
{
	const SP				= 'alipay';
	const API_URL				= 'https://mapi.alipay.com/gateway.do';
	const SIGN_TYPE				= 'MD5';
	const INPUT_CHARSET			= 'utf-8';
	const CACERT_FILENAME			= 'alipay-cacert.pem';
	const PAYMENT_TYPE			= 1;		//1,47
	
	protected static $treeConfig=null;
	public static function getConfig($key)
	{
		if(!self::$treeConfig) self::$treeConfig=PaymentCommon::getConfigTree(self::SP);
		//debugTree(self::$treeConfig);
		return self::$treeConfig->getItem($key);
	}
	public static function transport(){return DCS::transport();}
	public static function cacertPath()
	{
		return appDirPath('common.config').'dat/'.self::CACERT_FILENAME;
	}
	
	
	public static function buildTransactionURL($params)
	{
		if(!$params) $params=array();
		if(isTree($params)) $params=$params->getArray();
		switch(self::getConfig('module')){
			case 'dual':		$parameter=self::buildTransactionParamsDual($params);break;
			default:		$parameter=self::buildTransactionParamsDirect($params);break;
		}
		$url=self::buildRequestURL($parameter);
		return $url;
	}
	public static function buildTransactionParamsDirect($params)
	{
		return array(
				"service"		=> "create_direct_pay_by_user",
				"partner"		=> trim(self::getConfig('pid')),
				"payment_type"		=> self::PAYMENT_TYPE,			//支付类型
				"notify_url"		=> $params['notify_url'],		//服务器异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
				"return_url"		=> $params['return_url'],		//页面跳转同步通知页面路径
				"seller_email"		=> self::getConfig('email'),		//卖家支付宝帐户
				"out_trade_no"		=> $params['tradeno'],			//商户订单号，唯一订单号
				"subject"		=> $params['name'],			//订单名称

				"total_fee"		=> $params['money'],			//付款金额

				"body"			=> $params['desc'],			//订单描述
				"show_url"		=> $params['linkurl'],			//商品展示地址

				"anti_phishing_key"	=> $anti_phishing_key,			//防钓鱼时间戳，若要使用请调用类文件submit中的query_timestamp函数
				"exter_invoke_ip"	=> $exter_invoke_ip,			//客户端的IP地址，非局域网的外网IP地址，如：221.0.0.1

				"_input_charset"	=> trim(strtolower(self::INPUT_CHARSET))
		);
	}
	//构造要请求的参数数组，无需改动
	public static function buildTransactionParamsDual($params)
	{
		$quantity=$params['quantity'];if(!$quantity) $quantity='1';
		$logistics_fee=$params['logistics_fee'];if(!$logistics_fee) $logistics_fee='0.00';
		$logistics_type=$params['logistics_type'];if(!$logistics_type) $logistics_type='EXPRESS';
		$logistics_payment=$params['logistics_payment'];if(!$logistics_payment) $logistics_payment='SELLER_PAY';
		return array(
				"service"		=> "trade_create_by_buyer",
				"partner"		=> trim(self::getConfig('pid')),
				"payment_type"		=> self::PAYMENT_TYPE,			//支付类型
				"notify_url"		=> $params['notify_url'],		//服务器异步通知页面路径，需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
				"return_url"		=> $params['return_url'],		//页面跳转同步通知页面路径
				"seller_email"		=> self::getConfig('email'),		//卖家支付宝帐户
				"out_trade_no"		=> $params['tradeno'],			//商户订单号，唯一订单号
				"subject"		=> $params['name'],			//订单名称

				"price"			=> $params['money'],			//付款金额//必填

				"quantity"		=> $quantity,				//商品数量"1"//必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
				"logistics_fee"		=> $logistics_fee,			//物流费用"0.00"//必填，即运费
				"logistics_type"	=> $logistics_type,			//物流类型//必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
				"logistics_payment"	=> $logistics_payment,			//物流支付方式//必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

				"body"			=> $params['desc'],			//订单描述
				"show_url"		=> $params['linkurl'],			//商品展示地址

				"receive_name"		=> $receive_name,			//收货人姓名
				"receive_address"	=> $receive_address,			//收货人地址
				"receive_zip"		=> $receive_zip,			//收货人邮编
				"receive_phone"		=> $receive_phone,			//收货人电话号码
				"receive_mobile"	=> $receive_mobile,			//收货人手机号码

				"_input_charset"	=> trim(strtolower(self::INPUT_CHARSET))
		);
	}
	
	public static function buildRequestURL($para_temp)
	{
		//待请求参数数组
		$para = self::buildRequestPara($para_temp);
		$url=self::API_URL;
		//$url=DCS::urlLink($url,"_input_charset=".trim(strtolower(self::INPUT_CHARSET)));
		$url=DCS::urlLink($url,$para);
		return $url;
	}
	public static function buildRequestPara($para_temp)
	{
		//除去待签名参数数组中的空值和签名参数
		$para_filter = PaymentAlipayCore::paraFilter($para_temp);
		//对待签名参数数组排序
		$para_sort = PaymentAlipayCore::argSort($para_filter);
		//生成签名结果
		$mysign = self::buildRequestMysign($para_sort);
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim(self::SIGN_TYPE));
		return $para_sort;
	}
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	public static function buildRequestMysign($para_sort)
	{
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = PaymentAlipayCore::createLinkstring($para_sort);
		$mysign = "";
		switch (strtoupper(trim(self::SIGN_TYPE))) {
			case "MD5" :
				$mysign = PaymentAlipayCore::md5Sign($prestr, self::getConfig('key'));
				break;
			default :
				$mysign = "";
		}
		return $mysign;
	}
	
	
	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
	 * @param $para_temp 请求参数数组
	 * @return 支付宝处理结果
	 */
	public static function buildRequestHttp($para_temp)
	{
		$sResult = '';
		//待请求参数数组字符串
		$request_data = self::buildRequestPara($para_temp);
		//远程获取数据
		$sResult = PaymentAlipayCore::getHttpResponsePOST(self::API_URL, self::cacertPath(),$request_data,trim(strtolower(self::INPUT_CHARSET)));
		return $sResult;
	}
	
	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果，带文件上传功能
	 * @param $para_temp 请求参数数组
	 * @param $file_para_name 文件类型的参数名
	 * @param $file_name 文件完整绝对路径
	 * @return 支付宝返回处理结果
	 */
	public static function buildRequestHttpInFile($para_temp, $file_para_name, $file_name)
	{
		//待请求参数数组
		$para = self::buildRequestPara($para_temp);
		$para[$file_para_name] = "@".$file_name;
		//远程获取数据
		$sResult = PaymentAlipayCore::getHttpResponsePOST(self::API_URL, self::cacertPath(),$para,trim(strtolower(self::INPUT_CHARSET)));
		return $sResult;
	}
	
	/**
	 * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
	 * return 时间戳字符串
	 */
	public static function query_timestamp()
	{
		$url = self::API_URL."?service=query_timestamp&partner=".trim(strtolower(self::getConfig('pid')))."&_input_charset=".trim(strtolower(self::INPUT_CHARSET));
		$encrypt_key = "";
		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
		return $encrypt_key;
	}
	
}

	/*
	<input type='hidden' name='_input_charset' value='utf-8'/>
	<input type='hidden' name='body' value='订单描述1'/>
	<input type='hidden' name='notify_url' value='http://local.dev.com/alipaydirect/demo/notify_url.php'/>
	<input type='hidden' name='out_trade_no' value='12345'/>
	<input type='hidden' name='partner' value='2088301677840930'/>
	<input type='hidden' name='payment_type' value='1'/>
	<input type='hidden' name='return_url' value='http://local.dev.com/alipaydirect/demo/return_url.php'/>
	<input type='hidden' name='seller_email' value='alipay@7x24.cn'/>
	<input type='hidden' name='service' value='create_direct_pay_by_user'/>
	<input type='hidden' name='show_url' value='http://www.xxx.com/open_view.html'/>
	<input type='hidden' name='subject' value='名称1'/>
	<input type='hidden' name='total_fee' value='500'/>
	<input type='hidden' name='sign' value='889895cc2edb208701e4f5b69ce84a27'/>
	<input type='hidden' name='sign_type' value='MD5'/>
	*/
