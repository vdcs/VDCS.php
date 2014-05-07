<?
class VDCSHTTP
{
	
	public function request($url,$params=array(),$posts=null,$headers=null)
	{
		if(isTree($params)) $params=$params->getArray();
		if(!isTree($headers)) $headers=newTree();
		$headers->addItem('API-RemoteIP',DCS::ip());
		if($params['method']=='GET' && $posts){
			$url=DCS::urlLink($url,$posts);
			$posts＝null;
		}
		$method='GET';
		if($posts){
			$method='POST';
			$params['posts']=$posts;
		}
		$params['method']=$method;
		$params['headers']=$headers;
		//debugx($url);
		//debuga($params);
		//debugTree($headers);
		//dcsLog('par',$params);
		return VDCSHTTP::curl($url,$params);
	}
	
	//URL连接&编码
	//public static function urlQuery($url,$params){return DCS::urlLink($url,$params);}
	//public static function urlEncode($re){return DCS::urlEncode($re);}
	
	
	
	public static function curl($url,$params=null,&$headers=null)
	{
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_HEADER, 0);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
		//curl_setopt($ci, CURLINFO_HEADER_OUT, 1);
		self::curlParams($ci,$url,$params);
		$re = curl_exec($ci);
		//debuga(curl_getinfo($ci));
		if(!isn($headers)){
			$headers=newTree();
			foreach(curl_getinfo($ci) as $key => $value){
				$headers->addItem($key,$value);
			}
		}
		curl_close($ci);unset($ci);
		return $re;
	}
	
	public static function curlHeader($url,$params=null)
	{
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_HEADER, 1);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ci, CURLOPT_NOBODY, 1);
		self::curlParams($ci,$url,$params);
		//debuga(curl_getinfo($ci));
		$re = curl_exec($ci);
		curl_close($ci);
		return $re;
	}
	
	public static function curlSave($url,$params=null,$path=null)
	{
		$path=toFilePath($path);
		//debugx($path);
		$fp=fopen($path,'w');
		if(!$fp) return false;
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $url);
		curl_setopt($ci, CURLOPT_HEADER, 0);
		//curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ci, CURLOPT_NOBODY, 1);
		self::curlParams($ci,$url,$params);
		curl_setopt($ci, CURLOPT_FILE, $fp);
		curl_exec($ci);
		//debuga(curl_getinfo($ci));
		curl_close($ci);
		fclose($fp);
		return true;
	}
	
	
	public static function curlParams(&$ci,&$url,&$params)
	{
		//debuga($params);
		$uri=parse_url($url);
		$method=$params['method'];
		if(!$method) $method='GET';
		$method=toUpper($method);
		//##########
		$posts='';
		if($params['posts']){
			if(isTree($params['posts'])) $params['posts']=$params['posts']->getArray();
			if(isa($params['posts'])){
				foreach($params['posts'] as $key=>$value){
					$posts.=$key.'='.DCS::urlEncode($value).'&';
				}
				$posts=rtrim($posts,'&');
			}
			else{
				$posts=$params['posts'];
			}
			//debugvc($posts);
			$method='POST';
		}
		//debugx($method);
		//##########
		//if($posts) $url=urlLink($url,$posts);
		//debugx($url);
		curl_setopt($ci, CURLOPT_URL, $url);
		//##########
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		if(left($url,8)=='https://'){
			curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
		}
		//##########
		if($posts){
			curl_setopt($ci, CURLOPT_POST, true);
			curl_setopt($ci, CURLOPT_POSTFIELDS, $posts);
		}
		//##########
		if($params['proxy']){
			curl_setopt($ci, CURLOPT_HTTPPROXYTUNNEL, 1);
			curl_setopt($ci, CURLOPT_PROXY, $params['proxy.server'].':'.($params['proxy.port']?$params['proxy.port']:'1080'));
			curl_setopt($ci, CURLOPT_PROXYUSERPWD, $params['proxy.user'].':'.$params['proxy.passsword']);
		}
		//##########
		if($params['referer']) curl_setopt($ci, CURLOPT_REFERER,$params['referer']);
		else curl_setopt($ci, CURLOPT_AUTOREFERER,true);
		//##########
		if($params['cookie']) curl_setopt($ci, CURLOPT_COOKIE,$params['cookie']);
		if($params['cookie.file']){
			curl_setopt($ci, CURLOPT_COOKIEFILE, $params['cookie.file']);
			curl_setopt($ci, CURLOPT_COOKIEJAR, $params['cookie.file']);
		}
		if($params['cookie.save']){
			curl_setopt($ci, CURLOPT_COOKIESESSION, true);
			curl_setopt($ci, CURLOPT_COOKIEJAR, $params['cookie.save']);
		}
		//##########
		if($params['client.agent']) curl_setopt($ci, CURLOPT_USERAGENT, $params['client.agent']=='now'?$_SERVER['HTTP_USER_AGENT']:$params['client.agent']);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $params['ctimeout']?$params['ctimeout']:30);	//设置连接等待时间
		curl_setopt($ci, CURLOPT_TIMEOUT, $params['timeout']?$params['timeout']:30);		///设置允许执行的最长秒数
		/*
		curl_setopt($ci, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ci, CURLOPT_USERPWD, '[username]:[password]');
		*/
		//##########
		$headers=$params['headers'];
		if(isTree($headers)){
			$_headers=array();
			$headers->doBegin();
			for($t=0;$t<$headers->getCount();$t++){
				array_push($_headers,$headers->getItemKey().': '.$headers->getItemValue());
				$headers->doMove();
			}
			$headers=$_headers;
		}
		if(!$headers) $headers=array();
		array_push($headers,''.$method.' '.$uri['path'].' HTTP/1.1');
		//array_push($headers,'Accept: text/html');
		//array_push($headers,'Expect: ');
		if($params['access.token']){
			array_push($headers,'Authorization: OAuth2 '.$params['access.token']);
		}
		else{
			array_push($headers,'Authorization: Basic '.base64_encode($params['auth.user']?($params['auth.user'].':'.$params['auth.password']):'guest'));
		}
		if($params['nocache']){
			array_push($headers,'Cache-Control: no-cache');
			array_push($headers,'Pragma: no-cache');
		}
		if($params['client.ip']){
			array_push($headers,'CLIENT-IP: '.$params['client.ip']);
			array_push($headers,'X-FORWARDED-FOR: '.$params['client.ip']);
		}
		//debuga($headers);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
	}
	
	
	public static function parseHeader($headers,$fields)
	{
		$re=array();
		if(inp($fields,'filename')>0){
			$pattern='Content-Disposition: attachment;filename=(.+?)\r\n';
			preg_match(utilRegex::toPattern($pattern),$headers,$_matches);
			//debuga($_matches);
			$re['filename']=$_matches[1];
			$re['filetype']=toSubstr($re['filename'],insr($re['filename'],'.')+1);
		}
		if(inp($fields,'size')>0){
			$pattern='Content-Length: (.+?)\r\n';
			preg_match(utilRegex::toPattern($pattern),$headers,$_matches);
			$re['size']=$_matches[1];
		}
		if(inp($fields,'type')>0){
			$pattern='Content-Type: (.+?)\r\n';
			preg_match(utilRegex::toPattern($pattern),$headers,$_matches);
			$re['type']=$_matches[1];
		}
		return $re;
	}
	
	
	
	public static function socket($hosts,$data,$timeout=5)
	{
		if(!isa($hosts)) $parse = parse_url($hosts);
		else $parse=$hosts;
		$host=$parse['host'];
		$port=$parse['port']?$parse['port']:80;
		//debuga($parse);
		if(!$host || !$port || !$data) return false;
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false) {
			//echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
			return false;
		} else {
			//echo "OK. \n";
		}
		
		//echo "Attempting to connect to '$address' on port '$service_port'...";
		$result = socket_connect($socket, $host, $port);
		if($result === false) {
			//echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
			return false;
		} else {
			//echo "OK \n";
		}
		if($data=='test'){
			$data = "HEAD / http/1.1\r\n";
			$data .= "HOST: localhost \r\n";
			$data .= "Connection: close\r\n\r\n";
		}
		//echo "sending http head request ...";
		socket_write($socket, $data, strlen($data));
		//echo  "OK\n";
		
		$results='';
		//echo "Reading response:\n\n";
		while ($out = socket_read($socket, 20480)) {
			//echo NEWLINE.'---'.NEWLINE.$out;
			$results.=$out;
		}
		//debugs($results);
		//echo "closeing socket..";
		socket_close($socket);
		//echo "ok .\n\n";
		return $results;
	}
	
	
	
	public static function socket_get($host, $data, $timeout = 5)
	{
		return self::socket_request($host, $data, 'GET', $timeout);
	}
	public static function socket_post($host, $data, $timeout = 5)
	{
		return self::socket_request($host, $data, 'POST', $timeout);
	}
	
	public static function socket_request($host, $data, $method = 'GET', $timeout = 5)
	{
		$parse = parse_url($host);
		$method = strtoupper($method);
		print_r($parse);
		if (empty($parse)) return null;
		if (!isset($parse['port']) || !$parse['port']) $parse['port'] = '80';
		if (!in_array($method, array('POST', 'GET'))) return null;
		
		$parse['host'] = str_replace(array('http://', 'https://'), array('', 'ssl://'), $parse['scheme'] . "://") . $parse['host'];
		if (!$fp = fsockopen($parse['host'], $parse['port'], $errnum, $errstr, $timeout)) return null;
		
		$contentLength = '';
		$postContent = '';
		$query = isset($parse['query']) ? $parse['query'] : '';
		$parse['path'] = str_replace(array('\\', '//'), '/', $parse['path']) . "?" . $query;
		if ($method == 'GET') {
			substr($data, 0, 1) == '&' && $data = substr($data, 1);
			$parse['path'] .= ($query ? '&' : '') . $data;
		} elseif ($method == 'POST') {
			$contentLength = "Content-length: " . strlen($data) . "\r\n";
			$postContent = $data;
		}
		$write = $method . " " . $parse['path'] . " HTTP/1.0\r\n";
		$write .= "Host: " . $parse['host'] . "\r\n";
		$write .= "Content-type: application/x-www-form-urlencoded\r\n";
		$write .= $contentLength;
		$write .= "Connection: close\r\n\r\n";
		$write .= $postContent;
		@fwrite($fp, $write);
		
		$responseText = '';
		while ($data = fread($fp, 4096)) {
			$responseText .= $data;
		}
		@fclose($fp);
		$responseText = trim(stristr($responseText, "\r\n\r\n"), "\r\n");
		return $responseText;
	}
	
	
	//发送Http状态信息
	public static function sendStatus($code)
	{
		$_status=self::toStatus($code);
		if($_status){
			header('HTTP/1.1 '.$code.' '.$_status);
		}
	}
	
	public static function toStatus($code) {
		static $_status = array(
			// Informational 1xx
			100 => 'Continue',
			101 => 'Switching Protocols',
			// Success 2xx
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			// Redirection 3xx
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Moved Temporarily ',  // 1.1
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			// 306 is deprecated but reserved
			307 => 'Temporary Redirect',
			// Client Error 4xx
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			// Server Error 5xx
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			509 => 'Bandwidth Limit Exceeded'
		);
		return $_status[$code];
	}
	
	//gethostbyname
	//gethostbynamel
}

/*

//
// Accepts provided http content, checks for a valid http response,
// unchunks if needed, returns http content without headers on
// success, false on any errors.
//
function parseHttpResponse($content=null) {
    if (empty($content)) { return false; }
    // split into array, headers and content.
    $hunks = explode("\r\n\r\n",trim($content));
    if (!is_array($hunks) or count($hunks) < 2) {
        return false;
        }
    $header  = $hunks[count($hunks) - 2];
    $body    = $hunks[count($hunks) - 1];
    $headers = explode("\n",$header);
    unset($hunks);
    unset($header);
    if (!verifyHttpResponse($headers)) { return false; }
    if (in_array('Transfer-Coding: chunked',$headers)) {
        return trim(unchunkHttpResponse($body));
        } else {
        return trim($body);
        }
    }

//
// Validate http responses by checking header.  Expects array of
// headers as argument.  Returns boolean.
//
function validateHttpResponse($headers=null) {
    if (!is_array($headers) or count($headers) < 1) { return false; }
    switch(trim(strtolower($headers[0]))) {
        case 'http/1.0 100 ok':
        case 'http/1.0 200 ok':
        case 'http/1.1 100 ok':
        case 'http/1.1 200 ok':
            return true;
        break;
        }
    return false;
    }

//
// Unchunk http content.  Returns unchunked content on success,
// false on any errors...  Borrows from code posted above by
// jbr at ya-right dot com.
//
function unchunkHttpResponse($str=null) {
    if (!is_string($str) or strlen($str) < 1) { return false; }
    $eol = "\r\n";
    $add = strlen($eol);
    $tmp = $str;
    $str = '';
    do {
        $tmp = ltrim($tmp);
        $pos = strpos($tmp, $eol);
        if ($pos === false) { return false; }
        $len = hexdec(substr($tmp,0,$pos));
        if (!is_numeric($len) or $len < 0) { return false; }
        $str .= substr($tmp, ($pos + $add), $len);
        $tmp  = substr($tmp, ($len + $pos + $add));
        $check = trim($tmp);
        } while(!empty($check));
    unset($tmp);
    return $str;
    }

*/
?>