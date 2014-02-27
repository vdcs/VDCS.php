<?
class utilCoder
{
	
	/*
	########################################
	########################################
	*/
	public static function toMD5i($re)
	{
		if(len($re)==16)return $re;
		$re=self::toMD5($re);
		return $re;
	}
	public static function toMD5Secret($re,$secret=null)
	{
		$secret=$secret?$secret:(APP_SECRET?APP_SECRET:'');
		return self::toMD5($secret.$re);
	}
	public static function toMD5($re,$type=0)
	{
		if(strlen($re)<1) return '';
		//$tmpstr=md5($re);		//php4.X
		$re=md5($re,false);		//php5.X
		if($type==0) $re=substr($re,8,16);
		return $re;
	}
	
	public static function toBase64Encode($re) { return base64_encode($re); }		//编码
	public static function toBase64Decode($re) { return base64_decode($re); }		//解码
	
	
	/*
	########################################
	########################################
	*/
	public static function toSimilarPercent($s1,$s2,&$plen=0)
	{
		$plen=similar_text($s1,$s2,$re);
		return $re;
	}
	
	public static function toSafeHTML($re)
	{
		$aunsafe = array(
			'/<!--?.*-->/',
			'/<\?|\?'.'>/',
			'/<\s*head[\s\S]*?\/head\s*>/isU',
			'/<\s*style[\s\S]*?\/style\s*>/isU',
			'/<\s*script[\s\S]*?\/script\s*>/isU',
			'/<\s*frame[\s\S]*?\/frame\s*>/isU',
			'/<\s*frameset[\s\S]*?\/frameset\s*>/isU',
			'/<\s*noframes[\s\S]*?\/noframes\s*>/isU',
			'/<\s*object[\s\S]*?\/object\s*>/isU',
			'/<\s*embed[\s\S]*?\/embed\s*>/isU',
			'/<\s*applet[\s\S]*?\/applet\s*>/isU',
			//过滤 script|iframe|object 等可能引入恶意内容或恶意改变显示布局的代码
			"/<(\/?)(!doctype|html|base|basefont|body|title|meta|link|style|script|form|i?frame|frameset|noframes|object|embed|applet|bgsound|\?|\%)([^>]*?)>/isU",
			'/on([a-z]+)\s*=\s*(\'|\")[\s\S]*?(\'|\")/si',
			'/on([a-z]+)\s*=([^\s]*)/si',
			//"/(<[^>]*)(on([a-zA-Z]+\s*)=*)([^\/>]*(\/>|>))/isU",
			"/\r\n\r\n/","/\r\n\r\n/","/\r\n\r\n/","/\r\n\r\n/","/\r\n\r\n/",
		);
		$asafe = array(
			'','','','','','','','','','','',
			'',
			'','',
			//'<\1\5\6>',
			"\r\n","\r\n","\r\n","\r\n","\r\n",
		);
		return preg_replace($aunsafe,$asafe,$re);
	}
	/*
		$content=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i", '<a href="\\1\\3" target="_blank">\\1\\3</a>', ' '.$content);
	
	*/
	

	/*
	########################################
	########################################
	*/
	public static function toUID($prefix='',$more=false)
	{
		$re=uniqid($prefix);
		return $more?md5($re):$re;
	}
	
	// 根据PHP各种类型变量生成唯一标识号
	public static function toGUID($mix)
	{
		if (is_object($mix) && function_exists('spl_object_hash')) {
			return spl_object_hash($mix);
		} elseif (is_resource($mix)) {
			$mix = get_resource_type($mix) . strval($mix);
		} else {
			$mix = serialize($mix);
		}
		return md5($mix);
	}
	
	/**
	 * 生成符合VM规范的UUID
	 * @return string $uuid
	 */
	public static function toUUID()
	{
		$uuid = sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x',
			mt_rand(0, 65535), mt_rand(0, 65535), // 32 bits for "time_low"
			mt_rand(0, 65535), // 16 bits for "time_mid"
			mt_rand(0, 4095),  // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
		bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),
			// 8 bits, the last two of which (positions 6 and 7) are 01, for "clk_seq_hi_res"
			// (hence, the 2nd hex digit after the 3rd hyphen can only be 1, 5, 9 or d)
			// 8 bits for "clk_seq_low"
			mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bits for "node"
		);
		return $uuid;
	}
	
	/**
	 * 生成随机密码
	 * @param int $length
	 * @param boole $special_chars
	 * @param boole $extra_special_chars
	 * @return string $password
	 */
	public static function generate_password($length = 12, $special_chars = true, $extra_special_chars = false )
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if ( $special_chars ) $chars .= '!@#$%^&*()';
		if ( $extra_special_chars ) $chars .= '-_ []{}<>~`+=,.;:/?|';
		$password = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}
		return $password;
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public static function toConvert($re,$fromec,$toec=CHARSET)
	{
		//return iconv($fromec,$toec,$re);
		return mb_convert_encoding($re,$toec,$fromec);
	}
	
	public static function toUTF8($re,$by='GB2312')
	{
		//return iconv('GB2312','UTF-8',$re);
		return mb_convert_encoding($re,'UTF-8',$by);
	}
	public static function toGB2312($re)
	{
		//return iconv('UTF-8','GB2312',$re);
		return mb_convert_encoding($re,'GB2312','UTF-8');
	}
	
	// Convert a binary expression (e.g., "100111") into a binary-string
	function bin2bstr($input)
	{
		if (!is_string($input)) return null; // Sanity check
		// Pack into a string
		$input = str_split($input, 2);
		$str = '';
		foreach ($input as $v){
			$str .= base_convert($v, 2, 16);
		}
		$str =  pack('H*', $str);
		return $str;
	}
	// Binary representation of a binary-string
	function bstr2bin($input)
	{
		if (!is_string($input)) return null; // Sanity check
		// Unpack as a hexadecimal string
		$value = unpack('H*', $input);
		// Output binary representation
		$value = str_split($value[1], 1);
		$bin = '';
		foreach ($value as $v){
			$b = str_pad(base_convert($v, 16, 2), 4, '0', STR_PAD_LEFT);
			$bin .= $b;
		}
		return $bin;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function msubstr($str, $start=0, $length, $charset="utf-8", $suffix='..')
	{
		if(function_exists("mb_substr")){
			return mb_substr($str, $start, $length, $charset);
		}
		elseif(function_exists('iconv_substr')) {
			return iconv_substr($str,$start,$length,$charset);
		}
		$arc=array(
			'utf-8'		=> "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/",
			'gb2312'	=> "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/",
			'gbk'		=> "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/",
			'big5'		=> "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/",
		);
		preg_match_all($arc[$charset], $str, $match);
		$slice=join('',array_slice($match[0], $start, $length));
		if($suffix) return $slice.$suffix;
		return $slice;
	}
	
	
	/**
	  +----------------------------------------------------------
	 * 字符串命名风格转换
	 * type
	 * =0 将Java风格转换为C的风格
	 * =1 将C风格转换为Java的风格
	  +----------------------------------------------------------
	 * @access protected
	  +----------------------------------------------------------
	 * @param string $name 字符串
	 * @param integer $type 转换类型
	  +----------------------------------------------------------
	 * @return string
	  +----------------------------------------------------------
	 */
	public static function cjava($name, $type=0)
	{
		if($type){
			return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
		}
		else{
			$name=preg_replace("/[A-Z]/", "_\\0", $name);
			return strtolower(trim($name, "_"));
		}
	}
	
	
	
	
	/*
	########################################
	########################################
	*/
	/*
Public Function isValidHex($re) As Boolean
	Dim cc
	isValidHex=True
	re=UCase(re)
	If Len(re) <> 3 Then isValidHex=False: Exit Function
	If Left(re,1) <> "%" Then isValidHex=False: Exit Function
	cc=Mid(re,2,1)
	If Not (((cc >= "0") And (cc <= "9")) Or ((cc >= "A") And (cc <= "Z"))) Then isValidHex=False: Exit Function
	cc=Mid(re,3,1)
	If Not (((cc >= "0") And (cc <= "9")) Or ((cc >= "A") And (cc <= "Z"))) Then isValidHex=False: Exit Function
End Function



'########################################
'################## ABC #################
'########################################
Public Function toABC($re)
	'65-90 A-Z 97-122 a-z 48-58 0-9 45 -
	Dim tmp As Double,tmpstr
	tmpstr=Trim(re)
	If tmpstr="" Then toABC="-": Exit Function
	tmpstr=Left(tmpstr,1)
	tmp=Int(Asc(tmpstr))
	If tmp >= 48 And tmp <= 57 Then toABC=tmpstr: Exit Function
	If (tmp >= 65 And tmp <= 90) Or (tmp >= 97 And tmp <= 122) Then toABC=UCase(tmpstr): Exit Function
	tmp=tmp + 65536
	If (tmp >= 45217 And tmp <= 45252) Then toABC="A": Exit Function
	If (tmp >= 45253 And tmp <= 45760) Then toABC="B": Exit Function
	If (tmp >= 45761 And tmp <= 46317) Then toABC="C": Exit Function
	If (tmp >= 46318 And tmp <= 46825) Then toABC="D": Exit Function
	If (tmp >= 46826 And tmp <= 47009) Then toABC="E": Exit Function
	If (tmp >= 47010 And tmp <= 47296) Then toABC="F": Exit Function
	If (tmp >= 47297 And tmp <= 47613) Then toABC="G": Exit Function
	If (tmp >= 47614 And tmp <= 48118) Then toABC="H": Exit Function
	If (tmp >= 48119 And tmp <= 49061) Then toABC="J": Exit Function
	If (tmp >= 49062 And tmp <= 49323) Then toABC="K": Exit Function
	If (tmp >= 49324 And tmp <= 49895) Then toABC="L": Exit Function
	If (tmp >= 49896 And tmp <= 50370) Then toABC="M": Exit Function
	If (tmp >= 50371 And tmp <= 50613) Then toABC="N": Exit Function
	If (tmp >= 50614 And tmp <= 50621) Then toABC="O": Exit Function
	If (tmp >= 50622 And tmp <= 50905) Then toABC="P": Exit Function
	If (tmp >= 50906 And tmp <= 51386) Then toABC="Q": Exit Function
	If (tmp >= 51387 And tmp <= 51445) Then toABC="R": Exit Function
	If (tmp >= 51446 And tmp <= 52217) Then toABC="S": Exit Function
	If (tmp >= 52218 And tmp <= 52697) Then toABC="T": Exit Function
	If (tmp >= 52698 And tmp <= 52979) Then toABC="W": Exit Function
	If (tmp >= 52980 And tmp <= 53640) Then toABC="X": Exit Function
	If (tmp >= 53641 And tmp <= 54480) Then toABC="Y": Exit Function
	If (tmp >= 54481 And tmp <= 62289) Then toABC="Z": Exit Function
	toABC="-"
End Function
	*/
	
}
?>