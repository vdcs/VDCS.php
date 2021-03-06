<?php
@set_time_limit(60);define('MAGIC_QUOTES_GPC',FALSE);
//function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));		if(MEMORY_LIMIT_ON) $GLOBALS['_memory_start_']=memory_get_usage();
define('ISRUN',version_compare(PHP_VERSION,'5.4')>-1?TRUE:FALSE);
//==============================================
if(!defined('VDCS')) require('util/VDCS.c.php');
define('VDCS_BUILD',				'0.9.1.6');
define('VDCS_UPDATE',				'2014-04-30');
//==============================================
if(!defined('VDCS_PATH')) define('VDCS_PATH',dirname(__FILE__).'/');
define('_BASE_PATH_VDCS',			VDCS_PATH);
defined('VAX_PATH') || 				define('VAX_PATH',				dirname(VDCS_PATH).'/VAX.php/');
//==============================================
//==============================================
defined('_BASE_DIR_ROOT') || 			define('_BASE_DIR_ROOT',			'/');
defined('_BASE_DIR_COMMON') || 			define('_BASE_DIR_COMMON',			'common/');
defined('_BASE_DIR_DATA') || 			define('_BASE_DIR_DATA',			'data/');
defined('_BASE_DIR_UPLOAD') || 			define('_BASE_DIR_UPLOAD',			'upload/');
defined('_BASE_PATH_ROOT') || 			define('_BASE_PATH_ROOT',			dirname(dirname(__FILE__)).'/www/');
defined('_BASE_PATH_COMMON') || 		define('_BASE_PATH_COMMON',			_BASE_PATH_ROOT._BASE_DIR_COMMON);
defined('APP_BASEURL') || 			define('APP_BASEURL',				_BASE_DIR_ROOT);
defined('APP_VAX') || 				define('APP_VAX',				'');
defined('APP_CHANNELA') || 			define('APP_CHANNELA',				'');
defined('APP_UA') ||				define('APP_UA',				'user');
define('VDCS_CONFIG',				'config');					define('VDCS_CONFIG_PATH',			VDCS_PATH.'config/');
define('VDCS_UTIL',				'util');
define('VDCS_LIB',				'lib');
define('VDCS_WEB',				'web');						define('VDCS_WEB_PATH',				VDCS_PATH.VDCS_WEB.'/');
define('VDCS_CHANNEL',				'channel');					define('VDCS_CHANNEL_PATH',			VDCS_WEB_PATH.VDCS_CHANNEL.'/');
define('VDCS_CHANNELA',				'channel.'.APP_CHANNELA);			define('VDCS_CHANNELA_PATH',			VDCS_WEB_PATH.VDCS_CHANNELA.'/');
define('VDCS_INC',				'inc');						define('VDCS_INC_PATH',				VDCS_PATH.VDCS_INC.'/');
define('VDCS_CD_PATH',				VDCS_PATH.'inc/cd/');
define('VDCS_MANAGE',				'manage');					define('VDCS_MANAGE_PATH',			VDCS_WEB_PATH.'manage/');
//echo '_BASE_PATH_ROOT='._BASE_PATH_ROOT.NEWLINE.'_BASE_PATH_COMMON='._BASE_PATH_COMMON.NEWLINE;
//==============================================
defined('DIR_SEPARATOR') || 			define('DIR_SEPARATOR',				'/');
defined('FILE_SEPARATOR') || 			define('FILE_SEPARATOR',			'/');
//PATH_SEPARATOR
define('PATTERN_FLAG',				'(.+?)');
define('PATTERN_FLAG_VAR',			'([\w\-\_\.\:]*)');
define('PATTERN_FLAG_LABEL',			'([^>"]*)');
define('PATTERN_FLAG_LABELS',			PATTERN_FLAG_LABEL.'(!([\w-\.][^!]+?))?');
define('PATTERN_FLAG_PARAM',			'([^{\$"]*)');
define('PATTERN_FLAG_PARAMS',			'([\s\w-\.\:=;\'\(\)<>\[\]{\$}]*)');
define('PATTERN_FLAG_PARAMQ',			'(,"(.|[^>"]*)")?');
define('PATTERN_FLAG_CONTENT',			'([\s\S.]*?)');		//((.|\n)*?)
define('PATTERN_FLAG_CONTENT2',			'((.|\n)*?)');
define('PATTERN_FLAG_OPTION',			'(!([\w-\.\:]+?))?');
define('PATTERN_FLAG_STR',			'(.+?)');

define('PATTERN_PRE',				'/{\@([^{\}]*)}/ies');
define('PATTERN_VAR',				'/{\$([^{\}}]*)}/ies');
define('PATTERN_VAR_PX',			'/\{\${\$px}([^\{\}\}]*)\}/ies');
//==============================================
function timer(){return microtime(true);}function timerBegin(){$GLOBALS['_timer_begin_']=timer();}function timerExec($len=8){return number_format(timer()-$GLOBALS['_timer_begin_'],$len);}
//==============================================
function iss($s){return isset($s{0});}
function isequal($s,$v){return $s==$v;}		function iscmp($s,$s2){return strcasecmp($s,$s2)==0?false:true;}
function isdate($s){$ar=explode('-',$s);return (count($ar)==3)? checkdate($ar[1],$ar[2],$ar[0]):false;}
function ina($ary,$s){return (!in_array((string)$s,(array)$ary))?false:true;}
function tv($v,$v2='',$v3='',$v4=''){return $v?$v:($v2?$v2:($v4?$v4:$v3));}
function ajoin($ary,$s){return implode($s,$ary);}
if(!function_exists('query')){
function queryString(){return $_SERVER['QUERY_STRING'];}
function query($k){return trim($_GET[$k]);}		//r($k,'.','_')
function queryi($k){return intval($_GET[$k]);}function queryInt($k){return intval($_GET[$k]);}
function queryn($k){return floatval($_GET[$k]);}function queryNum($k){return floatval($_GET[$k]);}
function queryx($k){$re=trim($_GET[$k]);return isx($re)?$re:'';}
function querys($k,$type=0,$ret='string'){return utilCode::toValues(query($k),$type,$ret);}
function post($k,$c=0){$re=trim($_POST[r($k,'.','_')]);if($c>0)$re=utilCode::toCut($re,$c);return $re;}		//r($k,'.','_')
function posti($k){return intval(post($k));}function postInt($k){return intval(post($k));}
function postn($k){return floatval(post($k));}function postNum($k){return floatval(post($k));}
function postx($k){$re=post($k);return isx($re)?$re:'';}
function posts($k,$type=0,$ret='string'){return utilCode::toValues(post($k),$type,$ret);}
function isPost(){return $_SERVER['REQUEST_METHOD']!='POST'?false:true;}
function isForm(){return $_POST['_chk']=='yes'?true:false;}
function isFormPost(){return $_POST['_chk']=='yes'?true:false;}
function request($k){return trim($_REQUEST[$k]);}
function requesti($k){return intval($_REQUEST[$k]);}
function requestn($k){return floatval($_REQUEST[$k]);}
function requestx($k){$re=trim($_REQUEST[$k]);return isx($re)?$re:'';}
}
//==============================================
//require('zextend/test.php');
define('DEBUGV',queryx('debug'));


//==============================================
function timeout($n=60){@set_time_limit($n);}
function datei($fmt='Y-m-d H:i:s',$stamp=0){return gmdate($fmt,($stamp?$stamp:time())+DCS::timezone(1));}
function extLoad($extname){if(!extension_loaded($extname)){$px=(PHP_SHLIB_SUFFIX==='dll')?'php_':'';dl($px.$extname.'.'.PHP_SHLIB_SUFFIX);}}
function isphp($version='5.0.0'){static $_is_php;$version=(string)$version;if(!isset($_is_php[$version])) $_is_php[$version]=(version_compare(PHP_VERSION,$version)<0) ? FALSE : TRUE;return $_is_php[$version];}
function isClassExists($classname){return class_exists($classname,false);}			function isInterfaceExists($classname){return interface_exists($classname,false);}		//if(!function_exists('isClassExists'))
function cfunc($s){if(function_exists($s)) call_user_func($s);}
function unsetr(){for($i=0;$i<func_num_args();$i++){$_key=func_get_arg($i);if(is_string($_key)) unsetrf($GLOBALS[$_key]);else unsetrf($_key);}}
function unsetrf(&$o){if(is_object($o)) $o=null; else unset($o);}
//==============================================
function dcsRedirect($s)
{
	$brow='';$scriptfile=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$ary=explode('?',$scriptfile); $ary=explode('/',$ary[0]);
	for($i=0;$i<count($ary)-1;$i++){$brow.=$ary[$i].'/';};
	if($s=='.') $s=$brow;
	if(substr($s,0,1)=='?') $s=$brow.$scriptfile;
	if(substr($s,0,1)!='/' && !strpos($s,'://')) $s=$brow.$s;
	dcsClear();header('Location: '.$s);dcsEnd();
}function go($s){dcsRedirect($s);}

function dcsClear(){unset($GLOBALS['_VCACHE'],$GLOBALS['_cfg']);}	//,'dcs','cfg','ctl'	$GLOBALS['dcs']=null;
function dcsEnd(){dcsClear();/*function_exists('exits')?exits():exit();*/exit();}	//flush();
//==============================================
function obStarts()
{
	global $_cfg;
	$_cfg['app']['app.gzip.status']=$_cfg['app']['app.gzip'];
	if($_cfg['app']['app.gzip'] && function_exists('ob_gzhandler')){
		$_cfg['app']['app.gzip.status']='On';
		return obStart('ob_gzhandler');
	}
	else{
		$_cfg['app']['app.gzip.status']='Off';
		return obStart();
	}
}
function obStart($calls=null){return ob_start($calls);}function obContent(){return ob_get_contents();}function obFlush(){return ob_end_flush();}function obClean(){return ob_end_clean();}
function ignoreAbort(){ignore_user_abort();}
//==============================================
function dcsHeader($type=null,$charset=null){
	if(!$type) $type=CONTENT_TYPE_HTML;
	if(!$charset) $charset=CHARSET_HTML;
	@header('Content-Type:'.$type.'; charset='.$charset);
}
function dcsNoCache(){
	header('Pragma: no-cache');						//HTTP/1.0
	header('Cache-Control: no-store, no-cache, must-revalidate');		//HTTP/1.1
	header('Cache-Control: post-check=0, pre-check=0', false);
}
function dcsExpires($day=30,$modify=null){
	//header('Cache-Control: max-age='.(86400*$expires).',must-revalidate');
	if($modify){
		if(!is_string($modify)) $modify=gmdate('D, d M Y H:i:s',time()+DCS::timezone(1));
		header('Last-Modified: '.$modify.' GMT');
	}
	header('Expires: '.datei('D, d M Y H:i:s').' GMT');
}
function dcsGzipStatus($t=1){return appv('app.gzip.status');}
function dcsMemoryUsage($t=1){$re=MEMORY_LIMIT_ON?(memory_get_usage()-$GLOBALS['_memory_start_']):0;return ($t==1)?utilCode::toFileSize($re):$re;}
function dcsExecTime($t=1,$len=4){$_time=microtime(1)-$_SERVER['REQUEST_TIME_FLOAT'];return($t==1) ? number_format($_time,$len) : number_format($_time*1000,$len);}
//==============================================
function dcsLogSave($filename,$sort,$msg){include_once(VDCS_UTIL.'/ResLog.php');ResLog::save($filename,$sort,$msg);}function dcsLog($sort,$msg){dcsLogSave('today',$sort,$msg);}function dcsLogError($sort,$msg){dcsLogSave('today',$sort,$msg);}
function dcsMessage($title,$explain='[unknown]',$t=1){include_once(VDCS_UTIL.'/ResMessage.php');ResMessage::show($title,$explain,$t);}
function dcsMessageError($num,$tit,$msg,$description='',$source=''){dcsMessage($tit,array('message'=>$msg,'number'=>$num,'description'=>$description,'source'=>$source));}
//==============================================
function pageHeader($type=null,$charset=null)
{
	if($GLOBALS['_cfg']['page.isheader']) return;$GLOBALS['_cfg']['page.isheader']=true;
	switch($type){
		case 'html':	$type=CONTENT_TYPE_HTML;break;
		case 'xml':	$type=CONTENT_TYPE_XML;$charset=CONTENT_TYPE_XML;break;
		case 'json':	$type=CONTENT_TYPE_JSON;$charset=CONTENT_TYPE_JSON;break;
		case 'jsx':
		case 'js':	$type=CONTENT_TYPE_JS;$charset=CONTENT_TYPE_JS;break;
		case 'cssx':
		case 'css':	$type=CONTENT_TYPE_CSS;$charset=CONTENT_TYPE_CSS;break;
		case 'wml':	$type=CONTENT_TYPE_WML;$charset=CONTENT_TYPE_JSON;break;
		case 'txt':	$type=CONTENT_TYPE_TXT;$charset=CONTENT_TYPE_XML;break;
		default:	if(!$type) $type=CONTENT_TYPE_HTML;break;		// 'html'
	}
	if(!$charset) $charset=CHARSET_HTML;
	@header('Content-Type:'.$type.'; charset='.$charset);
	obStarts();
}
function pageFlush(){ob_end_flush();}
//==============================================
//==============================================
function appExt($re,$_ext=EXT_CONFIG)
{
	if(insr($re,'.')>0){
		if(inp(EXT_GATHER,substr($re,insr($re,'.')),'.')<1) $re.=$_ext;
	}
	else $re.=$_ext;
	return $re;
}
//function appExt($re,$ext=EXT_CONFIG){return pathExt($re,$ext);}

function toDirPath($re,$t=0)
{
	$re=str_replace(PATH_SEPARATORR,PATH_SEPARATORS,$re);
	if($t==1){
		//if(ins($re,':')<1 && substr($re,0,1)!=PATH_PX) $re=PATH_PX.$re;
		if(!isRealPath($re)) $re=PATH_PX.$re;
	}
	if(substr($re,-1)!=PATH_SEPARATORS) $re.=PATH_SEPARATORS;
	return $re;
}

function toPathRel($re){return r($re,$GLOBALS['_cfg']['sys.path']['root'],DIR_SEPARATOR);}
//==============================================
function toPathsReal($paths,$filename,$dbg=false)
{
	$filename=appExt($filename);
	foreach($paths as $k=>$v){
		$path=$v.$filename;
		if($dbg) debugx($path);
		if(is_file($path)) return $path;
	}
	return '';
}
//==============================================


/*
################################################
################################################
*/
class DCS
{
	static $timer=0,$local=false;
	public function init()
	{
		//$this->_data['start']=microtime(1);
		self::$timer=time();
		self::$local=self::isLocal();
		defined('ISLOCAL')		|| define('ISLOCAL',self::$local);
	}
	
	static $PATH_AL=null;
	public static function pathal($ary){if(!self::$PATH_AL)self::$PATH_AL=array();array_push(self::$PATH_AL,$ary);}
	
	static $mapDatc=array();
	public static function isDatc($key){return isset(self::$mapDatc[$key]);}
	public static function getDatc($key){return self::$mapDatc[$key];}
	public static function setDatc($key,$value){self::$mapDatc[$key]=$value;}
	public static function getDatcArray(){return self::$mapDatc;}
	
	public static function year(){return date('Y');}
	public static function tim($t=1){return $t?time():self::$timer;}
	public static function timer(){return self::$timer;}
	public static function timezone($second=false){defined('TIMEZONE') || define('TIMEZONE',SYS_TIMEZONE);$re=TIMEZONE;if($second) $re*=3600;return $re;}
	
	public static function time(){return self::timec(self::$timer);}public static function now(){return self::time();}
	public static function today(){return self::timec(self::$timer,'Y-m-d');}
	public static function timec($num,$fmt='Y-m-d H:i:s'){return gmdate($fmt,$num+self::timezone(1));}
	
	public static function server($key){return $_SERVER[$key];}
	public static function serverName(){return $_SERVER['SERVER_NAME'];}
	public static function serverIP(){return $_SERVER['SERVER_ADDR']?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR'];}
	public static function serverPort(){return $_SERVER['SERVER_PORT'];}
	public static function serverProtocol(){return $_SERVER['SERVER_PROTOCOL'];}
	public static function serverString(){return self::isLocal()?'local':'server';}
	public static function isLocal(){static $re=null;if(is_null($re)) $re=ins('127,192,10.,::1',substr(self::serverip(),0,3))>0?true:false;return $re;}
	public static function isLocali(){static $re=null;if(is_null($re)) $re=ins('127,::1',substr(self::serverip(),0,3))>0?true:false;return $re;}

	public static function browseDomain(){return $_SERVER['HTTP_HOST'];}
	public static function browseScript(){return $_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];}
	public static function browseURI(){return $_SERVER['REQUEST_URI'];}
	public static function browseURL($script=false){return self::url($script?$_SERVER['REQUEST_URI']:'');}
	public static function browsePath($script=false){return $script?self::browseURI():self::browseScript();}
	
	public static function urlLink($re,$params){return utilCode::urlLink($re,$params);}
	public static function urlEncode($re){return rawurlencode($re);}
	public static function urlDecode($re){return rawurldecode($re);}
	public static function isURL($url){return ins($url,'://')>0 ? true : false;}		//ins($url,'http://')>0 || ins($url,'https://')>0
	public static function url($url='',$baseurl=null){if(ins($url,'://')>0) return $url;return (!$baseurl?self::urlHost():$baseurl).ltrim($url,'/');}
	public static function urlHost(){return self::transport().'://'.self::host().'/';}
	public static function host(){return $_SERVER['HTTP_HOST'];}
	public static function transport(){return $_SERVER['HTTPS']=='on'?'https':'http';}
	
	public static function ip(){return $_SERVER['REMOTE_ADDR'];}
	public static function port(){return $_SERVER['REMOTE_PORT'];}
	public static function agent(){return $_SERVER['HTTP_USER_AGENT'];}
	//public static function sessionid(){return session_id();}
	public static function sessionid(){return $GLOBALS['dcs']->client->getSessionID();}
	public static function sessionSet($key,$value){return $GLOBALS['dcs']->client->setSession($key,$value);}
	public static function sessionGet($key){return $GLOBALS['dcs']->client->getSession($key);}
	public static function sessionDel($key){return $GLOBALS['dcs']->client->delSession($key);}
	public static function cookieSet($key,$value){return $GLOBALS['dcs']->client->setCookies($key,$value);}
	public static function cookieGet($key){return $GLOBALS['dcs']->client->getCookies($key);}
	public static function cookieDel($key){return $GLOBALS['dcs']->client->delCookies($key);}
	public static function cookieAge($age){return $GLOBALS['dcs']->client->setCookiesAge($age);}
	public static function cookieDomain($domain){return $GLOBALS['dcs']->client->setCookiesDomain($domain);}
	
	public static function pathConfigureDomain($file)
	{
		$basepath=dirname($file).'/';
		$filename=substr($file,strlen($basepath),-4);
		//debug($filename);
		if(!is_file($pathv=$basepath.$filename.'@'.$_SERVER['HTTP_HOST'].'.php')) $pathv='';
		if(!$pathv) $pathv=$basepath.$filename.'@'.'default.php';
		return $pathv;
	}

	public static function linkURL($channel,$page,$params=null){return $GLOBALS['cfg']->getLinkURL($channel,$page,$params);}
}


/*
################################################
################################################
*/
class test{
	static $PUT=true;
	function init(){static $use;if($use)return;include_once(VDCS_UTIL.'/ResTest.php');$use=true;}
	function v($o){self::init();return ResTest::toVar($o);}				function toVarString($o){return self::v($o);}
	function xml($o){self::init();return ResTest::toXML($o);}			function toXMLString($o){return self::xml($o);}
	function t($o,$t=''){self::init();return ResTest::toTxt($o,$t);}		function toTxtString($o,$t=''){return self::t($o,$t);}
	function a($o,$t=''){self::init();return ResTest::toAry($o,$t);}		function toAryString($o,$t=''){return self::a($o,$t);}
	function o($o,$t=''){self::init();return ResTest::toObject($o,$t);}		function toObjString($o,$t=''){return self::o($o,$t);}
	function tree($o,$t=''){self::init();return ResTest::toTree($o,$t);}		function toTreeString($o,$t=''){return self::tree($o,$t);}
	function table($o,$t=''){self::init();return ResTest::toTable($o,$t);}		function toTableString($o,$t=''){return self::table($o,$t);}
	function x($s,$br=true){if(!self::$PUT)return;self::init();ResTest::x($s,$br);}function j($s){self::init();ResTest::j($s);}function vc($s){self::init();ResTest::vc($s);}
}
function debugSet($b=false,$p=true){define('DEBUG_OUT',$b);test::$PUT=$p;}
function isDebug(){return !!DEBUG_OUT;}
function debugx($s,$br=true){test::x($s,$br);}function debugj($s){test::j($s);}function debugvc($s){test::vc($s);}
function debuga($o,$t=''){debugx(test::toAryString($o,$t));}
function debugo($o,$t=''){debugvc(test::toObjString($o,$t));}
function debugTree($o,$t=''){debugx(test::toTreeString($o,$t));}
function debugTable($o,$t=''){debugx(test::toTableString($o,$t));}
function debugxx($s){if(DCS::isLocal())debugx($s);}
function debugTrace(){put('<!--'.NEWLINE);debug_print_backtrace();put('-->');}




/*
################################################
################################################
################################################
################################################
*/
function isTree($o){return is_object($o)&&!is_array($o);}
function isTable($o){return is_object($o)&&!is_array($o);}

function removeBOM($s)		//移除头部BOM信息
{
	$bom=chr(0xEF).chr(0xBB).chr(0xBF);
	if(strlen($s)>3 && substr($s,0,3)==$bom) $s=substr($s,3);
	return $s;
}
function toFilePath($f){return iconv('UTF-8','GBK',$f);}
function isExists($f){return @file_exists(toFilePath($f));}
function isDir($f){return @is_dir(toFilePath($f));}
function isFile($f){return @is_file(toFilePath($f));}
function getFile($f){return getFileContent($f);}function setFile($f,$s){return doFileCreate($f,$s);}
function getFileContent($f){return removeBOM(@file_get_contents(toFilePath($f)));}
function doFileWrite($f,$s){return @file_put_contents(toFilePath($f),$s);}function doFileCreate($f,$s){return doFileWrite($f,$s);}
function doFileCopy($f,$f2){return @copy(toFilePath($f),toFilePath($f2));}
function doFileDel($f){$f=toFilePath($f);return @is_file($f)?@unlink($f):false;}
function getPathPart($path,$t){
	$paths=pathinfo($path);		//dirname,basename,extension,filename
	$re='';
	switch($t){
		case 'name':		$re=$paths['filename'];break;
		case 'ext':		$re=$paths['extension'];break;
		case 'exts':		$re='.'.$paths['extension'];break;
		case 'names':		$re=$paths['basename'];break;
		case 'dir':		$re=$paths['dirname'];break;
		default:
			$re=array();
			$re['dir']=$paths['dirname'];
			$re['name']=$paths['filename'];
			$re['ext']=$paths['extension'];
			$re['exts']='.'.$paths['extension'];
			break;
	}
	return $re;
}
function clearFileCaches(){clearstatcache();}



/*
################################################
################################################
*/
/*
$aryItems=array();
function addItem($key,$value)
{
	global $itemTotal,$aryItems;
	$n=count($aryItems);
	$aryItems[$n][0]=$key;
	$aryItems[$n][1]=$value;
}


function doClear()
{
	global $aryItems;
	unset($aryItems);
}
*/
