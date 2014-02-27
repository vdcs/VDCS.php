<?
class VDCSConfig
{
	
	public static function toPathsReal($paths,$filename,$vars='',$dbg=false)
	{
		$path='';
		$filename=appExt($filename);
		foreach($paths as $k=>$v){
			$path=$v.$filename;
			if($dbg) debugx($path);
			$path=rd($path,'channel',$vars);
			if($dbg) debugx($path);
			if(isFile($path)) break;
		}
		return $path;
	}
	public static function toPathsPlace($paths,$filename,$vars='',$n=1)
	{
		$path='';
		$filename=appExt($filename);
		$v=isInt($n)?$paths[$n-1]:$paths[$n];
		$path=$v.$filename;
		$path=rd($path,'channel',$vars);
		return $path;
	}
	
}


/*
################################################
################################################
################################################
################################################
*/
class VDCSData
{
	public static function s($o){return json_encode($o);}
	public static function us($o){return json_decode($o);}
	public static function enCode($o){return json_encode($o);}
	public static function deCode($o,$assoc=false){return json_decode($o,$assoc);}
	
	public static function JsonToTree($o)
	{
		$reTree=newTree();
		$ary=json_decode($o,true);
		if($ary){
			foreach($ary as $key=>$value){
				$reTree->addItem($key,$value);
			}
		}
		return $reTree;
	}
	public static function TreeToJson($tree)
	{
		$ary=array();
		$tree->doBegin();
		for($t=1;$t<=$tree->getCount();$t++){
			$ary[$tree->getItemKey()]=$tree->getItemValue();
			$tree->doMove();
		}
		return json_encode($ary);
	}
}


/*
################################################
################################################
################################################
################################################
*/
class VDCSCache
{
	
	public static function s($o){return serialize($o);}
	public static function us($o){return unserialize($o);}
	public static function enCode($o){return serialize($o);}
	public static function deCode($o){return unserialize($o);}
	
	public static function toPath($s,$dir='data'){return pathDir('data.cache').$dir.'/'.r($s,'/','__').EXT_CACHE;}
	
	
	/*
	########################################
	########################################
	*/
	public static function delCaches($values)
	{
		$ary=toSplit($values,BATCH_SYMBOL);
		foreach($ary as $value){
			if(ins($value,'@')<1) $value='@'.$value;
			utilString::lists($value,$dir,$file,'@');
			if(!$dir) $dir='config';
			//debugx($value.'='.$dir.'/'.$file);
			self::delCache($file,$dir);
		}
	}
	
	public static function delCache($file,$dir='data')
	{
		$path=self::toPath($file,$dir);
		if(isFile($path)) delFile($path);
	}
	
	public static function getCache($s,$dir='data',$b=true){return self::getArray($s,$dir,$b);}
	public static function setCache($s,$o,$dir='data'){self::setAry($s,$o,$dir);}
	
	
	/*
	########################################
	########################################
	*/
	public static function getArray($s,$dir='data',$b=true)
	{
		global $_VCACHE;
		$re=$b?array():null;
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		if(is_file($f)){include_once($f); if(is_array($_VCACHE[$s])) $re=$_VCACHE[$s];}
		return $re;
	}
	public static function getTree($s,$dir='data',$b=true)
	{
		global $_VCACHE;
		$re=$b?new utilTree():null;
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		if(is_file($f)){include_once($f); if(is_array($_VCACHE[$s])) $re->setArray($_VCACHE[$s]);}
		return $re;
	}
	public static function getTable($s,$dir='data',$b=true)
	{
		global $_VCACHE;
		$re=$b?new utilTable():null;
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		if(is_file($f)){include_once($f); if(is_array($_VCACHE[$s])) $re->setArray($_VCACHE[$s]);}
		return $re;
	}
	
	//.r($s,'/','__')
	//if(!is_dir($dir)) @mkdir($dir,0777);
	public static function setAry($s,$o,$dir='data')
	{
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		self::doUpdateAry($s,$o,$f);
	}
	public static function setTree($s,$o,$dir='data')
	{
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		self::doUpdateTree($s,$o,$f);
	}
	public static function setTable($s,$o,$dir='data')
	{
		$f=pathDir('data.cache').$dir.'/'.$s.EXT_CACHE;
		self::doUpdateTable($s,$o,$f);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function doUpdateAry($s,$o,$f)
	{
		$_v='<'.'?'.NEWLINE;
		$_v.=NEWLINE.'// VDCS Cache Tree: '.$s;
		$_v.=NEWLINE.'$_VCACHE[\''.$s.'\']='.self::toAryString($o);
		$_v.=NEWLINE.'?'.'>';
		self::doUpdateFile($s,$f,$_v);
	}
	
	public static function doUpdateTree($s,$o,$f)
	{
		$_v='<'.'?'.NEWLINE;
		$_v.=NEWLINE.'// VDCS Cache Tree: '.$s;
		$o->doBegin();
		for($t=0;$t<$o->getCount();$t++){
			$_v.=NEWLINE.'$_VCACHE[\''.$s.'\'][\''.$o->getItemKey().'\']='.self::toValue($o->getItemValue(),1).';';
			$o->doMove();
		}
		$_v.=NEWLINE.'?'.'>';
		self::doUpdateFile($s,$f,$_v);
	}
	
	public static function doUpdateTable($s,$o,$f)
	{
		$_v='<'.'?'.NEWLINE;
		$_v.=NEWLINE.'// VDCS Cache Table: '.$s;
		$_v.=NEWLINE.'$_VCACHE[\''.$s.'\'][0]=\''.$o->getFields().'\';';
		$o->doItemBegin();
		$arf=$o->getFieldsArray();
		for($t=0;$t<$o->getRow();$t++){
			for($a=0;$a<count($arf);$a++){
				$_v.=NEWLINE.'$_VCACHE[\''.$s.'\']['.($t+1).'][\''.$arf[$a].'\']='.self::toValue($o->getItemValue($arf[$a]),1).';';
			}
			$o->doItemMove();
		}
		$_v.=NEWLINE.'?'.'>';
		self::doUpdateFile($s,$f,$_v);
	}
	
	public static function doUpdateFile($s,$f,$content)
	{
		//utilFile::doFileCreate($f,$content);
		$f=utilFile::toPath($f);
		if(!$fp=fopen($f,'w+')) return false;
		flock($fp,2);
		fwrite($fp,$content);
		fclose($fp);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toVarString($re)
	{
		return str_replace(array("\r","\n\n","\n"),array("\n","\n",NEWLINE),$re);		//回车,换行,换行
	}
	public static function toAryString($array,$level=0)
	{
		if(!is_array($array)){return "'".$array."'";}
		if(is_array($array) && function_exists('var_export')){return self::toVarString(var_export($array,true));}
	}
	
	public static function getcachevars($data,$type='VAR') {
		$re='';
		foreach($data as $key=>$val){
			if(is_array($val)){
				$re.="\$$key=".arrayeval($val).";\n";
			}
			else{
				$val=addcslashes($val,'\'\\');
				$re.=$type=='VAR'?"\$$key='$val';\n":"define('".strtoupper($key)."','$val');\n";
			}
		}
		return $re;
	}
	//ceil
	
	public static function toValue($re,$t=1)
	{
		return var_export($re,$t?true:false);
		//return str_replace(['\\','\'',"\r\n","\n\r","\r","\n"],['\\\\','\\\'','\'.NEWLINE.\'','\'.NEWLINE.\'','\'.NEWLINE.\'','\'.NEWLINE.\''],$re);
	}
	
}


/*
################################################
################################################
################################################
################################################
*/
class VDCS_Client
{
	private $_data=array();
	private $_isSession=false;
	
	public function __construct()
	{
		$this->_data['cookies_expires']=$this->getCookies('expires');
		if(!is_numeric($this->_data['cookies_expires'])) $this->_data['cookies_expires']=0;
		$this->_data['cookies_name']=c('sys','cookies.name');
		$this->_data['cookies_path']=c('sys','cookies.path');
		$this->_data['cookies_domain']=c('sys','cookies.domain');
	}
	public function __destruct()
	{
		$this->doSessionDestory();
		unset($this->_data);
	}
	
	
	public static function getIP($t=0,$v='unknown')
	{
		if($t>0){
			$cip=getenv('HTTP_CLIENT_IP');
			$xip=getenv('HTTP_X_FORWARDED_FOR');
			$rip=getenv('REMOTE_ADDR');
			$srip=$_SERVER['REMOTE_ADDR'];
			if($cip && strcasecmp($cip,'unknown')) $re=$cip;
			elseif($xip && strcasecmp($xip,'unknown')) $re=$xip;
			elseif($rip && strcasecmp($rip,'unknown')) $re=$rip;
			elseif($srip && strcasecmp($srip,'unknown')) $re=$srip;
			preg_match("/[\d\.]{7,15}/",$re,$match);
			$re=$match[0]?$match[0]:$v;
		}
		else{
			$re=$_SERVER['REMOTE_ADDR'];
		}
		return $re;
	}
	public static function getPort(){return $_SERVER['REMOTE_PORT'];}
	public static function getAgent(){return $_SERVER['HTTP_USER_AGENT'];}
	
	
	/*
	########################################
	########################################
	*/
	public function setSessionID($sid){session_id($sid);}
	public function getSessionID(){if(!$this->_isSession)$this->initSession();return $_COOKIE['PHPSESSID'];}	//session_id()
	
	public function initSession()
	{
		if(!$this->_isSession){
			if(isset($_REQUEST['PHPSESSID']) && isx($_REQUEST['PHPSESSID'])){
				@session_id($_REQUEST['PHPSESSID']);
			}
			@session_start();					// must no header
			//session_name($this->_data['cookies_name']);
			$this->_isSession=true;
		}
	}
	public function doSessionDestory()
	{
		//if($this->_isSession) session_write_close();
	}
	
	
	public function getSession($k){if(!$this->_isSession)$this->initSession(); return isset($_SESSION[$k])?$_SESSION[$k]:'';}
	public function setSession($k,$v){if(!$this->_isSession)$this->initSession(); $_SESSION[$k]=$v;}
	public function delSession($k){if(!$this->_isSession)$this->initSession(); unset($_SESSION[$k]);}
	
	public function doSessionClear()
	{
		if(!$this->_isSession)$this->initSession();
		while(list($k,$v)=each($_SESSION)){
			unset($_SESSION[$k]);
		}
	}
	
	public function getSessionAry()
	{
		if(!$this->_isSession)$this->initSession();
		$reAry=array();
		while(list($k,$v)=each($_SESSION)){
			$reAry[$k]=$v;
		}
		return $reAry;
	}
	public function getSessionTree()
	{
		$reTree=new utilTree();
		$reTree->setArray($this->getSessionAry());
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	//public function setSessionName($strer){session_name($strer);}
	public function setCookiesAge($s){$this->setCookiesExpires($s);}
	public function setCookiesExpires($s)
	{
		if(is_numeric($s)){$this->_data['cookies_expires']=$s;}
		else{
			switch($s){
				case 'yes':	$this->_data['cookies_expires']=31536000; break;
				case 'y':	$this->_data['cookies_expires']=31536000; break;
				case 'm':	$this->_data['cookies_expires']=2592000; break;
				case 'd':	$this->_data['cookies_expires']=86400; break;
				case 'h':	$this->_data['cookies_expires']=3600; break;
				default:	$this->_data['cookies_expires']=0; break;
			}
		}
		if($this->_data['cookies_expires']) $this->_data['cookies_expires']+=time();
		$this->setCookies('expires',$this->_data['cookies_expires']);
	}
	
	public function setCookiesDomain($s){$this->_data['cookies_domain']=$s;}
	public function setCookiesPath($s){$this->_data['cookies_path']=$s;}
	
	public function getCookies($k,$d=''){$d=$d?$d.'__':''; $k=str_replace('.','_',$k); return $_COOKIE[$d.$k];}
	public function setCookies($k,$v,$d=''){$d=$d?$d.'__':''; $k=str_replace('.','_',$k); setcookie($d.$k,$v,$this->_data['cookies_expires'],$this->_data['cookies_path'],$this->_data['cookies_domain']);}
	public function delCookies($k,$d=''){$this->setCookies($k,'',$d);}
	//public function delCookies($k,$d=''){$d=$d?$d.'__':''; $k=str_replace('.','_',$k);setcookie($d.$k,'',-86400 * 365,0);}
	
	public function doCookiesClear()
	{
		while(list($k,$v)=each($_COOKIE)){
			setcookie($k,'',$this->_data['cookies_expires'],$this->_data['cookies_path'],$this->_data['cookies_domain']);
		}
	}
	
	public function getCookiesAry()
	{
		$reAry=array();
		while(list($k,$v)=each($_COOKIE)){
			$reAry[$k]=$v;
		}
		return $reAry;
	}
	public function getCookiesTree()
	{
		$reTree=new utilTree();
		$reTree->setArray($this->getCookiesAry());
		return $reTree;
	}
}


/*
################################################
################################################
################################################
################################################
*/
class VDCSTime
{
	
	public static function toConvert($time,$type=1,$zone=null)
	{
		if(strlen($time)>0 && !is_numeric($time)) $time=self::toNumber($time);
		if(!$time){
			if($type==0) return '';
			$time=DCS::timer();
		}
		switch((int)$type){
			case 1:		$fmttime='Y-m-d H:i:s'; break;			//2000-10-10 23:45:45
			case 10:	$fmttime='YmdHis'; break;			//20001010234545
			case 11:	$fmttime='Y年m月d日 H时i分s秒'; break;		//年(4)-月-日 时:分:秒
			case 12:	$fmttime='Y-m-d H:i'; break;			//2000-10-10 23:45
			case 13:	$fmttime='m-d H:i'; break;			//10-10 23:45
			case 4:		$fmttime='Y-m-d'; break;			//2003-10-10
			case 44:	$fmttime='Y年m月d日'; break;			//2003年10月10日
			case 6:		$fmttime='H:i:s'; break;			//23:45:45
			case 7:		$fmttime='m-d'; break;				//10-10
			case 8:		$fmttime='Y-m'; break;				//2003-10
			//default:	return $time; break;
		}
		return gmdate($fmttime,$time+DCS::timezone(1));
	}
	public function toConverts($time,$type='',$zone=null)
	{
		if(strlen($time)>0 && !is_numeric($time)) $time=self::toNumber($time);
		if(!$time) $time=DCS::timer();
		return gmdate($type?$type:'Y-m-d',$time+DCS::timezone(1));
	}
	
	
	public static function toZone($second=false,$zone=null)
	{
		is_null($zone) && $zone=DCS::timezone();
		return $second?$zone*3600:$zone;
	}
	/*
	public static function isDate($time)
	{
		list($y,$m,$d)=explode('-',$time);
		return checkdate((int)$d,(int)$m,(int)$y);
	}
	*/
	public static function toTimeYMD($time)
	{
		$timeAry=explode('-',$time);
		if(checkdate((int)$timeAry[1],(int)$timeAry[2],(int)$timeAry[0])==false) unset($timeAry);return '1';
		unset($timeAry);
		return $time;
	}
	
	public static function toString($num,$fmt='',$zone=null)
	{
		if(!is_numeric($num)) return $num;
		if(!$fmt) $fmt='Y-m-d H:i:s';
		switch($fmt){
			case 'date':		$fmt='Y-m-d';break;
		}
		return gmdate($fmt,$num+self::toZone(true,$zone));
	}
	
	public static function toNumber($time,$zone=null)
	{
		if(is_numeric($time)) return $time;
		$re=strtotime($time);	//+self::toZone('s',$zone)
		if(!$re) $re=0;
		return $re;	//-self::toZone('s',$zone)
	}
	
	public static function toDateAdd($time,$inte,$num,$zone=-1)
	{
		$plus=0;
		if(!is_numeric($time)){
			$fmt=ins($time,' ')>0?'Y-m-d H:i:s':'Y-m-d';
			$tnum=self::toNumber($time,$zone);
		}
		else{
			$tnum=$time;
		}
		//debugx($time.'--'.$fmt);
		switch($inte){
			case 'y':	$pstr='year';		$plus=$num*365*24*60*60;	break;
			case 'm':	$pstr='month';		$plus=$num*30*24*60*60;		break;
			case 'w':	$pstr='week';		$plus=$num*7*24*60*60;		break;
			case 'd':	$pstr='days';		$plus=$num*24*60*60;		break;
			case 'h':	$pstr='hours';		$plus=$num*60*60;		break;
			case 'mi':	$pstr='minutes';	$plus=$num*60;			break;
			case 's':	$pstr='seconds';	$plus=$num;			break;
		}
		//$tnum+=$plus;
		$pstr=($num>0?'+':'').strval($num).' '.$pstr;
		$tnum=strtotime($pstr,$tnum);	//+self::toZone('s',$zone);
		return $fmt?self::toString($tnum,$fmt,$zone):$tnum;
	}
}


/*
################################################
################################################
################################################
################################################
*/
class VDCSDB
{
	public static function &getInstance($cfg,$assist=NULL,$drvtype='pdo'){$db=null;return self::instance($cfg,$assist,$db,$drvtype);}
	public static function instance($cfg,$assist=NULL,&$db=null,$drvtype='pdo')
	{
		static $instances=array();
		$cfg['type'] || $cfg['type']='mysql';
		$drvkey='driver:'.$drvtype.'.'.$cfg['type'];
		if(!$instances[$drvkey]){
			if(ISRUN) include_once(VDCS_UTIL.'/db/'.$cfg['type'].'.common'.EXT_EXECUTE);
			include_once(VDCS_UTIL.'/db/'.$drvtype.'.'.$cfg['type'].EXT_EXECUTE);
			$instances[$drvkey]=true;
		}
		$cls=$drvtype.'_'.$cfg['type'];
		$cls_key=$cls.'_'.($assist?$assist:'DB');
		if(!$instances[$cls_key]){
			$instances[$cls_key]=new $cls($cfg);
			if(!is_null($assist)){
				if(!class_exists($assist,false)) eval('class '.$assist.'{use DBCommon,DBAssist;}');		//static class ?
				$assist::$db=&$instances[$cls_key];
			}
		}
		$db=&$instances[$cls_key];
		return $db;
	}
	
	public static function setConfig(&$db,$cfg)
	{
		if(isTree($cfg)) $cfg=$cfg->getArray();
		$db->cfg('type',$cfg['type']);
		$db->cfg('port',$cfg['port']);
		$db->cfg('perdure',$cfg['perdure']);
		$db->cfg('server',$cfg['server']);
		$db->cfg('database',$cfg['database']);
		$db->cfg('user',$cfg['user']);
		$db->cfg('username',$cfg['username']);
		$db->cfg('password',$cfg['password']);
		$db->cfg('charset',$cfg['charset']);
		$db->cfg('charset.result',$cfg['charset.result']);
		$db->cfg('tablepx',$cfg['tablepx']);
		$db->cfg('debug',$cfg['debug']);
	}
}

class VDCSDBConstruct
{
	public $_data=array();
	public $cid=false;		// The returned link identifier whenever a successful database connection is made.
	
	public function __construct()
	{
		$this->_data['name']='db';
		$this->_data['total.query']=0;
	}
	public function __destruct()
	{
		unset($_data);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getTotal($type='query'){return $this->_data['total.'.$type];}
	
	public function getSQL($t=-1){return($t>-1)?$this->_data['sql'][$t]:$this->_data['sql'];}
	public function getSQLTree()
	{
		$reTree=new utilTree();
		$reTree->setArray($this->_data['sql']);
		return $reTree;
	}
	
	public function addSQL($sql)
	{
		$this->_data['sql'][$this->_data['total.query']]=$sql;
		$this->_data['total.query']++;
	}
	
	public function doErrorEvent($t,$log='',$sql='',$msg='',$isend=0)
	{
		if(!defined('DB_DEBUG')){
			$dbdebug=c('app','sys.db.debug');
			if(strlen($dbdebug)<1) $dbdebug='3';
			define('DB_DEBUG',intval($dbdebug));
		}
		if((DB_DEBUG==1 || DB_DEBUG>2) && $log) dcsLogError('db.'.$t,$log);
		if(DB_DEBUG>1 && $msg) dcsMessage($this->_data['name'].' '.ucwords($t).' Error',array('message'=>$msg,'source'=>$sql),$isend);
	}
}


//======================================================================
//======================================================================

if(!defined('APP_VERSION_NAME')){
	include(VDCS_WEB_PATH.'define'.EXT_EXECUTE);
}
define('APP_VERSION',				APP_VERSION_NAME.' '.APP_VERSION_NO.' '.APP_VERSION_MARK);
//define('WEB_VERSION',				'<span class="vver"><a href="'.APP_VERSION_URL.'" target="_blank" title="'.APP_VERSION_BUILD.' by '.APP_VERSION_UPDATE.'">'.APP_VERSION_NAME.' <em>'.APP_VERSION_NO.'</em></a> <em>'.APP_VERSION_MARK.'</em></span>');
function appWebVersion(){return '<span class="vver"><a href="'.APP_VERSION_URL.'" target="_blank" title="'.APP_VERSION_BUILD.' by '.APP_VERSION_UPDATE.'">'.APP_VERSION_NAME.' <em>'.APP_VERSION_NO.'</em></a> <em>'.APP_VERSION_MARK.'</em></span>';}


define('SYS_TIMEZONE',				8);
//======================================================================
//$_cfg['sys']['time_zone']			= 8;
//----------------------------------------------------------------------
$_cfg['sys']['cookies.name']			= 'dcs';
$_cfg['sys']['cookies.path']			= '/';
$_cfg['sys']['cookies.domain']			= '';
//======================================================================
$_cfg['sys.dir']['root']			= '';
$_cfg['sys.dir']['vdcs']			= VDCS_PATH;
//----------------------------------------------------------------------
$_cfg['sys.dir']['common']			= 'common/';
$_cfg['sys.dir']['common.config']		= 'common/config/';
$_cfg['sys.dir']['common.include']		= 'common/include/';
$_cfg['sys.dir']['data']			= _BASE_DIR_DATA;
$_cfg['sys.dir']['data.cache']			= _BASE_DIR_DATA.'cache/';
$_cfg['sys.dir']['data.log']			= _BASE_DIR_DATA.'log/';
//======================================================================
$_cfg['sys.url']['root']			= '';
$_cfg['sys.url']['common']			= 'common/';
$_cfg['sys.url']['data']			= 'data/';
//$_cfg['sys.url']['images']			= 'images/';
//$_cfg['sys.url']['support']			= 'support/';
//----------------------------------------------------------------------
//$_cfg['sys.url']['manage']			= 'manage/';
//======================================================================
