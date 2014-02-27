<?
//==============================================
define('VDCS',					'VDCS.php');
define('VDCS_NAME',				'VDCS.php');
define('VDCS_PRO',				'dcs.php');
define('VDCS_MARK',				'Alpha');
define('VDCS_VER',				'1.0');
//define('VDCS_BUILD',				'0.8.5.1');
//define('VDCS_UPDATE',				'2012-06-21');
//==============================================
define('TABS',					"\t");
define('CRLF',					"\r\n");
define('_BASE_NEWLINE',				"\r\n");
define('NEWLINE',				_BASE_NEWLINE);
//==============================================
/*
PHP_OS,PHP_VERSION,PHP_SAPI,
$is_win = DIRECTORY_SEPARATOR == '\\';
*/
//define('PATH_SEPARATOR',			';');		//系统常量 linux=: window=;
//require('VDCS.'.(PATH_SEPARATOR==';'?'NT':'NX').'.php');
if(PATH_SEPARATOR==';'){
	define('ISWIN',true);
	defined('VDCS_RES_PATH') || 			define('VDCS_RES_PATH',			'D:/VSAP/VDCS.res/');
	define('PATH_SYMBOL',				':');
	function isRealPath($s){return !(strpos($s,':')===false);}
}
else{
	define('ISWIN',false);
	defined('VDCS_RES_PATH') || 			define('VDCS_RES_PATH',			'/var/VSAP/VDCS.res/');
	define('PATH_SYMBOL',				'/');
	function isRealPath($s){return (substr($s,0,1)=='/');}
}
//==============================================
define('PATH_PX',				'/');
define('PATH_SEPARATORR',			'\\');
define('PATH_SEPARATORS',			'/');
defined('DIR_SEPARATOR') || 			define('DIR_SEPARATOR',			'/');
defined('FILE_SEPARATOR') || 			define('FILE_SEPARATOR',		'/');
define('URL_SEPARATOR',				'/');

define('CHARSET',				'utf-8');
define('CHARSET_CONFIG',			'utf-8');
define('CHARSET_TEMPLATE',			'utf-8');
define('CHARSET_CONVERT',			'utf-8');
define('CHARSET_CODE',				'utf-8');
define('CHARSET_HTML',				'utf-8');
define('CHARSET_PAGE',				'utf-8');
define('CHARSET_TXT',				'utf-8');
define('CHARSET_XML',				'utf-8');
define('CONTENT_LANGUAGE',			'zh-cn');
define('CONTENT_TYPE',				'text/html');
define('CONTENT_TYPE_HTML',			'text/html');
define('CONTENT_TYPE_TXT',			'text/plain');
define('CONTENT_TYPE_XML',			'text/xml');
define('CONTENT_TYPE_WML',			'text/vnd.wap.wml');
define('CONTENT_TYPE_JS',			'application/x-javascript');
define('CONTENT_TYPE_JSON',			'application/x-javascript');
define('CONTENT_TYPE_CSS',			'text/css');
define('EXT_XCML',				'.xcml');
define('EXT_CFG',				'.xcml');
define('EXT_CONFIG',				'.xcml');
define('EXT_CONFIGURE',				'.php');
define('EXT_DTML',				'.dtml');
define('EXT_TEMPLAT',				'.dtml');
define('EXT_TEMPLATE',				'.dtml');
define('EXT_VPAGE',				'.vhtml');
define('EXT_HTML',				'.html');
define('EXT_SCRIPT',				'.php');
define('EXT_EXECUTE',				'.php');
define('EXT_CACHE',				'.php');
define('EXT_TXT',				'.txt');
define('EXT_XML',				'.xml');
define('EXT_XSL',				'.xsl');
define('EXT_XSLT',				'.xslt');
define('EXT_CSS',				'.css');
define('EXT_JS',				'.js');
define('EXT_INI',				'.ini');
define('EXT_LOG',				'.log');
define('EXT_GATHER',				'.xcml.dtml.php.log.ini.xml.xsl.cfg.dat.txt.vhtml.html.htm.js.css.gif.jpg.png.bmp');


define('PREPERTY_SYMBOL',			'$$$');
define('BATCH_SYMBOL',				'$$$');
define('SWAP_SYMBOL',				'~~~$$$~~~');

define('OBJECT_EXIST',				'__yes');
define('OBJECT_EXIST_SYMBOL',			'__');
define('OBJECT_CONNECT_SYMBOL',			'~');

define('OBJECT_VALUE_TRUE',			'__true');
define('OBJECT_VALUE_FALSE',			'__false');

define('STRUCT_PREPERTY_SYMBOL',		'###');		//$$$
define('STRUCT_EVALUATE_SYMBOL',		'~');		//=
define('STRUCT_SEPARATOR_SYMBOL',		'^');		//;

define('ITEMS_SYMBOLS',				';');
define('ITEMS_SYMBOL',				'=');

define('OPTION_VALUE_NO',			'__no');
define('OPTION_VALUE_NO1',			'__no1');
define('OPTION_VALUE_ALL',			'__all__');

define('DB_SQL_TPX',				'db_');
define('DB_SQL_TERM_KEY',			'__sql_term');
define('DB_SQL_ID_KEY',				'__sql_id');

define('XCML_ROOT',				'xcml');
define('XCML_NODENAME_CONFIGURE',		'configure');
define('XCML_NODENAME_ITEM',			'item');
define('XCML_PRESPACE',				"\t");
define('XCML_PRESPACE2',			"\t\t");
define('XCML_PRESPACE3',			"\t\t\t");
define('XCML_NODE_NAMES',			'__node');
define('XCML_NODE_EXIST',			'__yes');
define('XCML_NODE_CONNECT',			'.');
define('XCML_NODE_ATT',				'@');
define('XCML_NODE_PX',				'__');

define('HTMLMarkHead',				'<'.'?');
define('HTMLMarkHeads',				'<'.'?=');
define('HTMLMarkFoot',				'?'.'>');

define('VALUE_EMPTY_REPLACER',			'NaN');
define('VALUE_EMPTY_REPLACERS',			'<span class=\"gray\">NaN</span>');

define('BROWSER_IP',				'127.1.0.1');
define('BROWSER_AGENT',				'Mozilla/4.0(compatible; VDCS 1.0; VDCS OS 1.0; MyVDCS1;)');
//==============================================


//======================================================================
function put($s){echo $s;}			function puts($s){echo $s.NEWLINE;}

function debug(){for($i=0;$i<func_num_args();$i++) echo func_get_arg($i).NEWLINE;}
function debugc(){for($i=0;$i<func_num_args();$i++) echo '<!--'.func_get_arg($i).'-->'.NEWLINE;}
function debugv(){for($i=0;$i<func_num_args();$i++) echo '<pre>'.func_get_arg($i).NEWLINE.'</pre>';}
function debugs(){for($i=0;$i<func_num_args();$i++) echo func_get_arg($i).'<br/>'.NEWLINE;}
//======================================================================
function tn($o){return gettype($o);}
function isn($s){return is_null($s);}		//function isNull($s){return is_null($s);}
function ise($s){return empty($s);}		//function isEmpty($s){return empty($s);}
function isa($s){return is_array($s);}		function isAry($s){return is_array($s);}		//function isArray($s){return is_array($s);}
function iso($s){return is_object($s);}		function isObj($s){return is_object($s);}		//function isObject($s){return is_object($s);}
function isb($s){return is_bool($s);}		function isBool($s){return ($s===false||$s===0||!$s||$s==="0")?false:true;}
//function isInt($s){return (is_numeric($s) && is_int($s)) ? true : false;}
function isInt($s){return (is_numeric($s) && (strpos($s,'.')===false)) ? true : false;}		//ctype_digit	//is_int($s) ? true : false;
function isNum($s){return is_numeric($s) ? true : false;}
//function isDate($s){$ar=explode('-',$s);return (count($ar)==3)? checkdate($ar[1],$ar[2],$ar[0]):false;}
//function iss($s){return isset($s{0});}
function isVar($s){return !is_null($s);}	function isStr($s){return strlen($s)>0;}
//function isEqual($s,$v){return $s==$v;}		function isCmp($s,$s2){return strcasecmp($s,$s2)==0?false:true;}
function isx($re)
{
	static $unallowed		= ' 	?$%#*@&=\'"<>()[]{}~^/\,;!|';
	if($len=strlen($re)<1) return false;
	for($i=0;$i<$len;$i++){
		if(strpos($unallowed,$re[$i])>-1) return false;
	}
	//if(substr($re,0,1)=='_' || substr($re,0,1)=='-') return false;
	return true;
}
function isSecure($s){return isx($s);}

function vi($s){return intval($s);}		function toi($s){return intval($s);}			function toInt($s){return intval($s);}			function toInts($s){return isInt($s) ? $s : 0;}
function vn($s){return floatval($s);}		function ton($s){return floatval($s);}			function toNum($s){return floatval($s);}		function toNums($s){return isNum($s) ? $s : 0;}

function len($s){return strlen($s);}		function toLen($s){return strlen($s);}			function toLength($s){return strlen($s);}
function str($s){return strval($s);}		function vv($s,$v=''){if(strlen($s)<1) $s=$v;return $s;}	function va($s,$v,$p='.'){return strlen($s)>0?($s.$p.$v):$v;}
function t($s){return trim($s);}		//function toTrim($s){return trim($s);}
function toLower($s){return strtolower($s);}	function toUpper($s){return strtoupper($s);}		function toReverse($s){return strrev($s);}
function substri($s,$p1,$p2=NULL){return ($p2===NULL)?substr($s,$p1-1):substr($s,$p1-1,$p2);}		function toSubstr($s,$p1,$p2=NULL){return substri($s,$p1,$p2);}		function mid($s,$p1,$p2=NULL){return substri($s,$p1,$p2);}
function left($s,$n){return substr($s,0,$n);}	function lefti($s,$n){return substr($s,0,$n);}		function right($s,$n){return substr($s,-$n);}	function righti($s,$n){return substr($s,-$n);}
function toSplit($s,$p=','){return explode($p,$s);}
function msubstr($str,$start=0,$length,$charset='utf-8',$suffix=true){return utilCoder::msubstr($str,$start,$length,$charset,$suffix);}

function ins($s,$v){$re=@strpos($s,$v); return ($re===false) ? 0 : $re+1;}				function inStr($s,$v){return ins($s,$v);}	// instr
function insr($s,$v){$re=@strrpos($s,$v); return ($re===false) ? 0 : $re+1;}				//function inStrRev($s,$v){return insr($s,$v);}
function inp($s,$v,$smb=','){return ins($smb.$s.$smb,$smb.$v.$smb);}					function inPart($s,$v,$smb=','){return inp($s,$v,$smb);}
//function ina($ary,$s){return (!in_array((string)$s,(array)$ary))?false:true;}				//function inAry($ary,$s){return ina($ary,$s);}

function ri($s,$k,$v=''){return str_replace($k,$v,$s);}			
function r($s,$k,$v=''){return str_replace($k,$v,$s);}			function toReplace($s,$k,$v=''){return r($s,$k,$v);}
function rv($s,$k,$v=''){return str_replace('{'.$k.'}',$v,$s);}		function toVari($s,$k,$v=''){return rv($s,$k,$v);}		function isVari($s,$k){return (strpos($s,'{'.$k.'}')===false) ? false : true;}
function rdi($s,$k,$v=''){return str_replace('{$'.$k.'}',$v,$s);}	
function rd($s,$k,$v=''){return str_replace('{$'.$k.'}',$v,$s);}	function toDisp($s,$k,$v=''){return rd($s,$k,$v);}		function isDisp($s,$k){return (strpos($s,'{$'.$k.'}')===false) ? false : true;}

/*
function queryString(){return $_SERVER['QUERY_STRING'];}
function query($k){return trim($_GET[r($k,'.','_')]);}
function queryi($k){return intval(query($k));}function queryInt($k){return intval(query($k));}
function queryn($k){return floatval(query($k));}function queryNum($k){return floatval(query($k));}
function queryx($k){$re=query($k);return isx($re)?$re:'';}
function post($k){return trim($_POST[r($k,'.','_')]);}
function posti($k){return intval(post($k));}function postInt($k){return intval(post($k));}
function postn($k){return floatval(post($k));}function postNum($k){return floatval(post($k));}
function postx($k){$re=post($k);return isx($re)?$re:'';}
function isPost(){return $_SERVER['REQUEST_METHOD']!='POST'?false:true;}
function isForm(){return $_POST['_chk']=='yes'?true:false;}
function form($k){return trim($_POST[r($k,'.','_')]);}
function formi($k){return intval(form($k));}function formInt($k){return intval(form($k));}
function formn($k){return floatval(form($k));}function formNum($k){return floatval(form($k));}
function formx($k){$re=form($k);return isx($re)?$re:'';}
*/
/*
function g($k){return str_replace(array('\'','\.','\:'),array('','',''),$_GET[$k]);}
function gpc($k,$t='G',$v=''){
	switch(strtoupper($t)){
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? (is_array($var[$k]) ? $var[$k] : trim($var[$k])) : $v;
}
*/


//======================================================================
function rp($re){return str_replace("\\", "/",$re);}

function pathExt($re,$_ext=EXT_CONFIG)
{
	$pos=@strrpos($re,'.');
	if($pos===false){
		$re.=$_ext;
	}else{
		if(inp(EXT_GATHER,substr($re,$pos+1),'.')<1) $re.=$_ext;
	}
	return $re;
}

function urlLink($re,$apd='')
{
	if($apd){
		if(ins($re,'?')<1) $re.='?';
		else if(right($re,1)!='&') $re.='&';
		$re.=$apd;
	}
	return $re;
}
