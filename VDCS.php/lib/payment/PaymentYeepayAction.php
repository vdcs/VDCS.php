<?
class PaymentYeepayAction
{
	const SP				= 'yeepay';
	const API_URL				= 'https://www.yeepay.com/app-merchant-proxy/node';
	//const API_URL_DEBUG			= 'http://tech.yeepay.com:8080/robot/debug.action';
	const API_URL_DEBUG			= 'https://www.yeepay.com/app-merchant-proxy/node';
	const PAYMENT_CMD			= 'Buy';		//业务类型: 支付请求，固定值"Buy"
	const PAYMENT_SAF			= '0';			//送货地址 为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为 "0"
	const PAYMENT_CUR			= 'CNY';			//交易币种,固定值"CNY"
	

	protected static $treeConfig=null;
	public static function getConfig($key)
	{
		if(!self::$treeConfig) self::$treeConfig=PaymentCommon::getConfigTree(self::SP);
		//debugTree(self::$treeConfig);
		return self::$treeConfig->getItem($key);
	}
	public static function transport(){return DCS::transport();}
	
	
	public static function buildTransactionForm($params,$struct=true)
	{
		if(!$params) $params=array();
		if(isTree($params)) $params=$params->getArray();

		#	商户订单号,选填.
		##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，易宝支付会自动生成随机的商户订单号.
		$p2_Order					= $params['tradeno'];		//$_REQUEST['p2_Order'];

		#	支付金额,必填.
		##单位:元，精确到分.
		$p3_Amt						= $params['money'];		//$_REQUEST['p3_Amt'];

		#	交易币种,固定值"CNY".
		$p4_Cur						= "CNY";

		#	商品名称
		##用于支付时显示在易宝支付网关左侧的订单产品信息.
		$p5_Pid						= $params['name'];		//$_REQUEST['p5_Pid'];
		$p5_Pid						= 'payment';

		#	商品种类
		$p6_Pcat					= $params['cat'];		//$_REQUEST['p6_Pcat'];

		#	商品描述
		$p7_Pdesc					= $params['desc'];		//$_REQUEST['p7_Pdesc'];
		$p7_Pdesc					= 'online';

		#	商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
		$p8_Url						= $params['return_url'];	//$_REQUEST['p8_Url'];	

		#	商户扩展信息
		##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
		$pa_MP						= $params['mp'];		//$_REQUEST['pa_MP'];

		#	支付通道编码
		##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
		$pd_FrpId					= $params['frpid'];		//$_REQUEST['pd_FrpId'];

		#	应答机制
		##默认为"1": 需要应答机制;
		$pr_NeedResponse	= "1";

		#调用签名函数生成签名串
		$hmac = self::getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
		
		$re='';
		if($struct) $re.=NEWLINE.'<form name="yeepay" action="'.self::getTransactionURL().'" method="post">';
		$re.=NEWLINE.'<input type="hidden" name="p0_Cmd"					value="'.self::PAYMENT_CMD.'">';
		$re.=NEWLINE.'<input type="hidden" name="p1_MerId"				value="'.self::getConfig('pid').'">';
		$re.=NEWLINE.'<input type="hidden" name="p2_Order"				value="'.$p2_Order.'">';
		$re.=NEWLINE.'<input type="hidden" name="p3_Amt"					value="'.$p3_Amt.'">';
		$re.=NEWLINE.'<input type="hidden" name="p4_Cur"					value="'.$p4_Cur.'">';
		$re.=NEWLINE.'<input type="hidden" name="p5_Pid"					value="'.$p5_Pid.'">';
		$re.=NEWLINE.'<input type="hidden" name="p6_Pcat"					value="'.$p6_Pcat.'">';
		$re.=NEWLINE.'<input type="hidden" name="p7_Pdesc"				value="'.$p7_Pdesc.'">';
		$re.=NEWLINE.'<input type="hidden" name="p8_Url"					value="'.$p8_Url.'">';
		$re.=NEWLINE.'<input type="hidden" name="p9_SAF"					value="'.self::PAYMENT_SAF.'">';
		$re.=NEWLINE.'<input type="hidden" name="pa_MP"						value="'.$pa_MP.'">';
		$re.=NEWLINE.'<input type="hidden" name="pd_FrpId"				value="'.$pd_FrpId.'">';
		$re.=NEWLINE.'<input type="hidden" name="pr_NeedResponse"	value="'.$pr_NeedResponse.'">';
		$re.=NEWLINE.'<input type="hidden" name="hmac"						value="'.$hmac.'">';
		if($struct) $re.=NEWLINE.'</form>';

		return $re;
	}
	public static function getTransactionURL(){return DCS::isLocal() ? self::API_URL_DEBUG : self::API_URL;}


	public static function isVerifyReturn(&$btype=0,&$tradeno='')
	{
		$re=false;
		#	只有支付成功时易宝支付才会通知商户.
		##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

		#	解析返回参数.
		$return = self::getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

		#	判断返回签名是否正确（True/False）
		$bRet = self::CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
		#	以上代码和变量不需要修改.
			 	
		#	校验码正确.
		if($bRet){
			if($r1_Code=="1"){
				#需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
				#并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.      	  	
				$re=true;
				$btype=$r9_BType;
				$tradeno=$r6_Order;
				if($r9_BType=="1"){
					//echo "交易成功";
					//echo  "<br />在线支付页面返回";
				}elseif($r9_BType=="2"){
					#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
					//echo "success";
					//echo "<br />交易成功";
					//echo  "<br />在线支付服务器返回";      			 
				}
			}
		}
		return $re;
	}



	#签名函数生成签名串
	public static function getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse)
	{
		#进行签名处理，一定按照文档中标明的签名顺序进行
		$sbOld = "";
		#加入业务类型
		$sbOld = $sbOld.self::PAYMENT_CMD;
		#加入商户编号
		$sbOld = $sbOld.self::getConfig('pid');
		#加入商户订单号
		$sbOld = $sbOld.$p2_Order;
		#加入支付金额
		$sbOld = $sbOld.$p3_Amt;
		#加入交易币种
		$sbOld = $sbOld.$p4_Cur;
		#加入商品名称
		$sbOld = $sbOld.$p5_Pid;
		#加入商品分类
		$sbOld = $sbOld.$p6_Pcat;
		#加入商品描述
		$sbOld = $sbOld.$p7_Pdesc;
		#加入商户接收支付成功数据的地址
		$sbOld = $sbOld.$p8_Url;
		#加入送货地址标识
		$sbOld = $sbOld.self::PAYMENT_SAF;
		#加入商户扩展信息
		$sbOld = $sbOld.$pa_MP;
		#加入支付通道编码
		$sbOld = $sbOld.$pd_FrpId;
		#加入是否需要应答机制
		$sbOld = $sbOld.$pr_NeedResponse;
		
		$re=self::HmacMd5($sbOld,self::getConfig('key'));
		//debugx($sbOld);
		//debugx(self::getConfig('key'));
		self::logstr($p2_Order,$sbOld,$re);

		return $re;

	} 

	public static function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType)
	{
		#取得加密前的字符串
		$sbOld = "";
		#加入商家ID
		$sbOld = $sbOld.self::getConfig('pid');
		#加入消息类型
		$sbOld = $sbOld.self::PAYMENT_CMD;
		#加入业务返回码
		$sbOld = $sbOld.$r1_Code;
		#加入交易ID
		$sbOld = $sbOld.$r2_TrxId;
		#加入交易金额
		$sbOld = $sbOld.$r3_Amt;
		#加入货币单位
		$sbOld = $sbOld.$r4_Cur;
		#加入产品Id
		$sbOld = $sbOld.$r5_Pid;
		#加入订单ID
		$sbOld = $sbOld.$r6_Order;
		#加入用户ID
		$sbOld = $sbOld.$r7_Uid;
		#加入商家扩展信息
		$sbOld = $sbOld.$r8_MP;
		#加入交易结果返回类型
		$sbOld = $sbOld.$r9_BType;

		self::logstr($r6_Order,$sbOld,self::HmacMd5($sbOld,self::getConfig('key')));
		return self::HmacMd5($sbOld,self::getConfig('key'));

	}

	#	取得返回串中的所有参数
	public static function getCallBackValue(&$r0_Cmd,&$r1_Code,&$r2_TrxId,&$r3_Amt,&$r4_Cur,&$r5_Pid,&$r6_Order,&$r7_Uid,&$r8_MP,&$r9_BType,&$hmac)
	{  
		$r0_Cmd		= $_REQUEST['r0_Cmd'];
		$r1_Code	= $_REQUEST['r1_Code'];
		$r2_TrxId	= $_REQUEST['r2_TrxId'];
		$r3_Amt		= $_REQUEST['r3_Amt'];
		$r4_Cur		= $_REQUEST['r4_Cur'];
		$r5_Pid		= $_REQUEST['r5_Pid'];
		$r6_Order	= $_REQUEST['r6_Order'];
		$r7_Uid		= $_REQUEST['r7_Uid'];
		$r8_MP		= $_REQUEST['r8_MP'];
		$r9_BType	= $_REQUEST['r9_BType']; 
		$hmac			= $_REQUEST['hmac'];
		return null;
	}

	public static function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac)
	{
		if($hmac==self::getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType))
			return true;
		else
			return false;
	}
			
	  
	public static function HmacMd5($data,$key)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing(NOTE: Hacked means written)

		//需要配置环境支持iconv，否则中文参数不能正常处理
		//$key = iconv("GB2312","UTF-8",$key);
		//$data = iconv("GB2312","UTF-8",$data);

		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;

		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}

	public static function logstr($orderid,$str,$hmac)
	{
		return;
		$james=fopen($logName,"a+");
		fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]");
		fclose($james);
	}

}
