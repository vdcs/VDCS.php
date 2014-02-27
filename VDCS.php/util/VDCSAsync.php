<?
class VDCSAsync
{
	
	public static function exec($url)
	{
		$parts = parse_url($url);
	 
		$fp = fsockopen(
			$parts['host'],
			isset($parts['port']) ? $parts['port'] : 80,
			$errno, $errstr, 3
		);

		$out = "GET " . $parts['path'] . " HTTP/1.1\r\n";
		$out .= "Host: " . $parts['host'] . "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		usleep(10000);		//10毫秒
		fclose($fp);		//fwrite之后马上执行fclose，nginx会直接返回499。
	}

	/*
$ch = curl_init();
$curl_opt = array(CURLOPT_URL, 'http://www.example.com/backend.php',
                            CURLOPT_RETURNTRANSFER, 1,
                            CURLOPT_TIMEOUT, 1,);
curl_setopt_array($ch, $curl_opt);
curl_exec($ch);
curl_close($ch);


ignore_user_abort(TRUE);
	*/


}
