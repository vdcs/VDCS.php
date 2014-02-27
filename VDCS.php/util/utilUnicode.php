<?
class utilUnicode
{
	
	/**
	 * ord 方法的unicode 支持
	 *
	 * @param 单字 $c
	 * @return int
	 */
	function uniord($c){
		$h = ord($c{0});
		if ($h <= 0x7F) {
			return $h;
		} else if ($h < 0xC2) {
			return false;
		} else if ($h <= 0xDF) {
			return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
		} else if ($h <= 0xEF) {
			return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
									 | (ord($c{2}) & 0x3F);
		} else if ($h <= 0xF4) {
			return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
									 | (ord($c{2}) & 0x3F) << 6
									 | (ord($c{3}) & 0x3F);
		} else {
			return false;
		}
	}
	
	/**
	 * 将指定编码的字符串分解成数组
	 *
	 * @param STRING $string
	 * @param STRING $encode
	 * @return ARRAY
	 */
	function mbStringToArray($string,$encode="UTF-8"){
		$strlen = mb_strlen($string);
		while ($strlen) {
			$array[] = mb_substr($string,0,1,$encode);
			$string = mb_substr($string,1,$strlen,$encode);
			$strlen = mb_strlen($string);
		}
		return $array;
	}
	
		
	/**
	 * 仅支持PHP5 -- 同上,是icov实现
	 */
//  function iconvStringToArray ($string,$encode="UTF-8") {
//	  $strlen = iconv_strlen($string,$encode);
//	  while ($strlen) {
//		  $array[] = iconv_substr($string,0,1,$encode);
//		  $string = iconv_substr($string,1,$strlen,$encode);
//		  $strlen = iconv_strlen($string,$encode);
//	  }
//	  return $array;
//  }
	
	/**
	 * Unicode 编码表示
	 *
	 * @param 单字 $unichar
	 * @return String
	 */
	function unicharCodeAt($unichar){
		return "&#" . self::uniord($unichar) . ';' ;
	}
	
	/**
	 * chr 函数的unicode实现
	 *
	 * @param mixed[整形数组或者整数] $codes
	 * @return String
	 */
	function uchr($codes){
		if (is_scalar($codes)) 
			$codes= func_get_args();
		$str= '';
		foreach ($codes as $code) 
			$str.= html_entity_decode('&#'.$code.';',ENT_NOQUOTES,'UTF-8');
		return $str;
	}
	
	/*
	 * 同上 基于mb实现
	 */
//  function unichr($codes){
//	  if (is_scalar($codes)) 
//		  $codes= func_get_args();
//	  $str= '';
//	  foreach ($codes as $code) 
//		  $str.= mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');		  
//	  return $str;
//  }
	
	/**
	 * 将unicode编码的字符串解析成正常的数据
	 *
	 * @param String $str
	 * @return String
	 */
	function deUnicode($str){
		$arr = explode (';',str_ireplace('&#','',$str) );
		//去除最后一个空字符
		$arr = array_slice($arr,0,count($arr)-1);
		return self::uchr($arr);
	}
	
	/**
	 * 将字符串解析成unicode编码的字符串
	 *
	 * @param String $str
	 * @return String
	 */
	function encode($str){
		$arr = self::mbStringToArray($str);
		$str = '' ;
		foreach($arr as $a){
			$str .=  self::unicharCodeAt($a) ;
		}
		return $str ;
	}
	
	/**
	 * 输出Unicode字符表
	 *
	 */
	function echoUnicodeTable(){
		for ($i=0;$i<4096;$i++){
			echo '<br/>' . dechex($i). ' **********  ';
			for ($j=0;$j<16;$j++){
				$ch = intval($i * 16 + $j);
				$ch = self::uchr($ch);
				echo $ch . '   --   ';
			}
		}
	}
	
	/**
	 * 输出Ascii字符表
	 *
	 */
	function echoAsciiTable(){
		
		for($i=0;$i<16;$i++){
			echo '<br/>' . ($i). ' **********  ';
			for ($j=0;$j<16;$j++){
				$num = intval($i * 16 + $j);
				$ch = self::uchr($num);
				echo "{$ch} --- ";
			}
		}
	}
	
	function test(){
		$test1 ='我是色色[vdcs.cn] начинается уже в марте bbs.cn #$%^&*()_+!@QADD?><.,,m';
		$test1 = self::encode($test1);
		echo $test1 ;
		echo "\n<br/>\n";
		$test1 = self::deUnicode($test1);
		echo $test1 ;	   
	}
	
}
