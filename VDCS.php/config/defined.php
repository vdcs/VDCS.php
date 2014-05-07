<?
function _cfgGet(){
//======================================================================
$_cfg['sys.dir']['root']			= '';
$_cfg['sys.dir']['vdcs']			= VDCS_PATH;
//##########
if(!is_dir($path_res=(dirname(_BASE_PATH_ROOT).'/VDCS.res/'))){
	$path_res=dirname(VDCS_PATH).'/VDCS.res/';
	//'/usr/VSAP/VDCS.res/','D:/VSAP/VDCS.res/'
	if(!is_dir($path_res)) $path_res=VDCS_RES_PATH;
}
//debugx($path_res);
$_cfg['sys.dir']['vdcs.res']			= $path_res;
//##########
$_cfg['sys.dir']['vdcs.config']			= VDCS_CONFIG_PATH;
$_cfg['sys.dir']['vdcs.web']			= VDCS_WEB_PATH;
$_cfg['sys.dir']['vdcs.web.config']		= VDCS_WEB_PATH.'config/';
$_cfg['sys.dir']['vdcs.channel']		= VDCS_CHANNEL_PATH;
$_cfg['sys.dir']['vdcs.manage']			= VDCS_MANAGE_PATH;
//----------------------------------------------------------------------
$_cfg['sys.dir']['common']			= 'common/';
$_cfg['sys.dir']['common.config']		= 'common/config/';
$_cfg['sys.dir']['common.include']		= 'common/include/';
$_cfg['sys.dir']['common.channel']		= 'common/channel/';
$_cfg['sys.dir']['common.channel.user']		= 'common/channel/account/';
$_cfg['sys.dir']['data']			= _BASE_DIR_DATA;
$_cfg['sys.dir']['data.cache']			= _BASE_DIR_DATA.'cache/';
$_cfg['sys.dir']['data.log']			= _BASE_DIR_DATA.'log/';
//$_cfg['sys.dir']['data.xml']			= _BASE_DIR_DATA.'xml/';
//----------------------------------------------------------------------
$_cfg['sys.dir']['images']			= 'images/';
$_cfg['sys.dir']['script']			= 'images/script/';
$_cfg['sys.dir']['themes']			= 'themes/';
$_cfg['sys.dir']['upload']			= _BASE_DIR_UPLOAD;
//$_cfg['sys.dir']['passport']			= 'passport/';
//$_cfg['sys.dir']['account']			= 'account/';
//$_cfg['sys.dir']['support']			= 'support/';
//$_cfg['sys.dir']['about']			= 'about/';
//======================================================================
$_cfg['sys.url']['root']			= '';
$_cfg['sys.url']['common']			= 'common/';
$_cfg['sys.url']['images']			= 'images/';
$_cfg['sys.url']['css']				= 'images/css/';
$_cfg['sys.url']['script']			= 'images/script/';
$_cfg['sys.url']['themes']			= 'themes/';
$_cfg['sys.url']['upload']			= 'upload/';
$_cfg['sys.url']['support']			= 'support/';
$_cfg['sys.url']['about']			= 'about/';
$_cfg['sys.url']['account']			= 'account/';
$_cfg['sys.url']['passport']			= 'passport/';
//----------------------------------------------------------------------
$_cfg['sys.url']['common.upload']		= 'common/upload.html';
$_cfg['sys.url']['common.payment']		= 'common/payment.html';
//$_cfg['sys.url']['common.vcode']		= 'common/vcode.html';
$_cfg['sys.url']['common.search']		= 'common/search.html';
$_cfg['sys.url']['search']			= 'common/search.html';
$_cfg['sys.url']['sitemap']			= 'common/sitemap.html';
//----------------------------------------------------------------------
//$_cfg['sys.url']['data']			= 'data/';
//$_cfg['sys.url']['data.xml']			= 'data/xml/';
//$_cfg['sys.url']['xml']				= 'data/xml/';		//data.xml
//$_cfg['sys.url']['xml']				= 'data/xml/';
//$_cfg['sys.url']['xml.rss2']			= 'data/xml/rss2.xml';
//----------------------------------------------------------------------
$_cfg['sys.url']['index']			= '';
//$_cfg['sys.url']['plaza']			= 'plaza';
$_cfg['sys.url']['home']			= 'home';
$_cfg['sys.url']['register']			= 'register';
$_cfg['sys.url']['login']			= 'login';
$_cfg['sys.url']['logout']			= 'logout';
//$_cfg['sys.url']['user.login']			= 'login';
//----------------------------------------------------------------------
/*
$_cfg['sys.url']['oauth']			= 'passport/oauth/';
$_cfg['sys.url']['oauth.qq']			= 'passport/oauth/qq'.EXT_SCRIPT;
$_cfg['sys.url']['oauth.weibo']			= 'passport/oauth/weibo'.EXT_SCRIPT;
*/
//----------------------------------------------------------------------
//$_cfg['sys.url']['manage']			= 'manage'.EXT_SCRIPT;
//======================================================================
$_cfg['app']['app.version']			= APP_VERSION;
$_cfg['app']['app.version.name']		= APP_VERSION_NAME;
$_cfg['app']['app.version.pro']			= APP_VERSION_PRO;
$_cfg['app']['app.version.no']			= APP_VERSION_NO;
$_cfg['app']['app.version.mark']		= APP_VERSION_MARK;
$_cfg['app']['app.version.explain']		= APP_VERSION_EXPLAIN;
$_cfg['app']['app.version.build']		= APP_VERSION_BUILD;
$_cfg['app']['app.version.update']		= APP_VERSION_UPDATE;
$_cfg['app']['app.version.url']			= APP_VERSION_URL;
$_cfg['app']['web.version']			= appWebVersion();		//WEB_VERSION
//======================================================================
return $_cfg;
}
function _cfgSets($ks,$cfg){
	foreach($cfg as $k=>$v){
		_cfgSet($ks,$k,$v);
	}
}
function _cfgSet($ks,$k,$v){
	global $_cfg;
	if(ise($_cfg[$ks][$k])) $_cfg[$ks][$k]=$v;
}
function _cfgDefined(){
	global $_cfg;
	$cfg=_cfgGet();
	foreach($cfg as $k=>$v){
		if(is_array($v)){
			_cfgSets($k,$v);
		}
		else{
			if(ise($_cfg[$k])) $_cfg[$k]=$v;
		}
	}
}
_cfgDefined();
?>