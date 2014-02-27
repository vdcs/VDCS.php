<?
require(VDCS_WEB_PATH.'Pages.res.php');

function manageAgent($dir)
{
	define('MANAGE_DIR',substr($dir,strlen(dirname($dir))+1));
	define('MANAGE_PATH',rp($dir.DIR_SEPARATOR));
	//debugs(MANAGE_DIR.', '.MANAGE_PATH);
	mdef();
	minit();
	include('common/entry.agent.php');
}

function dcsManageInit()
{
	mdef();
	minit();
}

function mdef()
{
//define
if(!defined('MANAGE_DIR'))			define('MANAGE_DIR',			'manage');
if(!defined('MANAGE_PATH'))			define('MANAGE_PATH',			substr(MANAGE_COMMON_PATH,0,-7));
if(!defined('MANAGE_COMMON_PATH'))		define('MANAGE_COMMON_PATH',		MANAGE_PATH.'common'.DIR_SEPARATOR);
if(!defined('MANAGE_CHANNEL_PATH'))		define('MANAGE_CHANNEL_PATH',		MANAGE_PATH.'channel'.DIR_SEPARATOR);
if(!defined('CHANNEL_MANAGE_PATH'))		define('CHANNEL_MANAGE_PATH',		_BASE_PATH_COMMON.'channel'.DIR_SEPARATOR);
if(!defined('MANAGE_ENTRY_PORTAL'))		define('MANAGE_ENTRY_PORTAL',		'PagePortal');
defined('MANAGE_THEME') || 			define('MANAGE_THEME',			'default');
defined('MANAGE_THEME_APP') || 			define('MANAGE_THEME_APP',		'');
defined('THEME_APP') || 			define('THEME_APP',			'html5');
//system
if(!defined('VDCS_MANAGE_PATH'))		define('VDCS_MANAGE_PATH',		rp(dirname(__FILE__).DIR_SEPARATOR));
if(!defined('VDCS_MANAGE_CONFIG_PATH'))		define('VDCS_MANAGE_CONFIG_PATH',	VDCS_MANAGE_PATH.'config'.DIR_SEPARATOR);
if(!defined('VDCS_MANAGE_COMMON_PATH'))		define('VDCS_MANAGE_COMMON_PATH',	VDCS_MANAGE_PATH.'common'.DIR_SEPARATOR);
if(!defined('VDCS_MANAGE_THEMES_PATH'))		define('VDCS_MANAGE_THEMES_PATH',	VDCS_MANAGE_PATH.'themes'.DIR_SEPARATOR);
if(!defined('VDCS_MANAGE_CHANNEL_PATH'))	define('VDCS_MANAGE_CHANNEL_PATH',	VDCS_MANAGE_PATH.'channel'.DIR_SEPARATOR);
if(!defined('VDCS_CHANNEL_MANAGE_PATH'))	define('VDCS_CHANNEL_MANAGE_PATH',	VDCS_CHANNEL_PATH);
if(!defined('VDCS_CHANNELA_MANAGE_PATH'))	define('VDCS_CHANNELA_MANAGE_PATH',	VDCS_CHANNELA_PATH);
define('VDCS_MANAGE_ENTRY_PORTAL',		'PagePortal');
define('VDCS_MANAGE_ENTRY_PORTAL_PAGE',		'Page:::Portal');
define('VDCS_MANAGE_ENTRY_PORTAL_CHANNEL',	'Portal@@@');
define('CHANNEL_M',				'm');
}

function minit()
{
	global $cfg,$ctl,$mr,$mpo,$ma,$theme;
	!$cfg&&$cfg=new CommonConfig();
	!$ctl&&$ctl=new PagesControl();
	//!$mp&&$mp=new ManagePortal();
	!$mr&&$mr=new ManageRuler();
	!$mpMod&&$mpMod=new ManageModel();
	$mpo=null;
	//,$mps,$mpx,$mpa
	//$mps=null;$mpx=null;$mpa=null;
	
	!$ma&&$ma=new UaManager();
	!$GLOBALS['ua']&&$GLOBALS['ua']=&Ua::instance(APP_UA);
	
	ManageCommon::initConfigure();
	
	!$theme&&$theme=new ManageTheme();
	$theme->setCache(3);		//强制更新模板缓存
	
	if(!function_exists('doManagePage')) include(VDCS_MANAGE_COMMON_PATH.'doManagePage'.EXT_SCRIPT);
}

function mend()
{
	global $mpo,$mpMod,$mr,$ma;
	global $uu,$ua,$theme;
	_autoload_::save();
	$theme=null;$mpo=null;
	dcsEnd();
}

//function go($s=''){mgo($s);}
function mgo($s=''){if(!$s)$s=appURL('manage');dcsRedirect($s);}


function mPagePortalReload($className,$check=true)
{
	global $_cfg;
	$_cfg['entry']['type']='noportal';
	//debugx('--'.$className);
	if(!class_exists($className,false)&&$className==VDCS_MANAGE_ENTRY_PORTAL) $className='PageMessagePortal';
	doManagePage($className,false);
}


function _autoload_path($file)
{
	$ary=array(
			CHANNEL_MANAGE_PATH.MANAGE_CHANNEL_NOW.'/'.CHANNEL_M.'/',
			MANAGE_CHANNEL_PATH.MANAGE_CHANNEL_NOW.'/',
			MANAGE_CHANNEL_PATH,
			VDCS_MANAGE_CHANNEL_PATH.MANAGE_CHANNEL_NOW.'/',
			VDCS_CHANNELA_MANAGE_PATH.MANAGE_CHANNEL_NOW.'/'.CHANNEL_M.'/',
			VDCS_CHANNEL_MANAGE_PATH.MANAGE_CHANNEL_NOW.'/'.CHANNEL_M.'/',
			VDCS_CHANNELA_PATH.MANAGE_CHANNEL_NOW.'/',
			VDCS_CHANNEL_PATH.MANAGE_CHANNEL_NOW.'/',
			MANAGE_COMMON_PATH,VDCS_MANAGE_PATH,
			VDCS_MANAGE_COMMON_PATH,VDCS_MANAGE_CHANNEL_PATH);
	for($a=0;$a<count($ary);$a++){
		$path=$ary[$a].$file;
		//debugx($path);
		if(isFile($path)) return $path;		//break;
		$path='';
	}
	return $path;
}
?>