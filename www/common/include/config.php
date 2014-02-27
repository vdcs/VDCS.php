<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

//define('_BASE_DIR_ROOT','/dir/');
define('_BASE_DIR_DATA','../data/');
define('_BASE_DIR_UPLOAD','../upload/');
define('_BASE_PATH_INCLUDE',dirname(__FILE__).'/');
define('_BASE_PATH_ROOT',substr(_BASE_PATH_INCLUDE,0,-15));
//define('VDCS_PATH',_BASE_PATH_INCLUDE.'VDCS/');
if(is_file($pathv=dirname(_BASE_PATH_ROOT).'/VDCS.php/VDCS.php') || is_file($pathv='/usr/VSAP/VDCS.php/VDCS.php') || is_file($pathv='/var/wwwroot/VDCS/VDCS.php/VDCS.php/VDCS.php')) define('VDCS_PATH',substr($pathv,0,-8));
elseif(is_file($pathv='D:/VSAP/VDCS.php/VDCS.php') || is_file($pathv='E:/wwwroot/VDCS/VDCS.php/VDCS.php/VDCS.php')) define('VDCS_PATH',substr($pathv,0,-8));
else die('Require VDCS!');

define('DEBUG_LOG',			1);

//define('VDCS_WEB_CHANNEL',		'sns');
//define('THEME_APP',			'html5');
//define('APP_UA',			'account');
//define('APP_DOMAIN',			'vdcs.cn');

if(is_file('config.define.php')) include('config.define.php');


require(VDCS_PATH.'VDCS.php');
if(!defined('NOVDCS')){
dcsInit(true,true);
}

/*
debugs('Query String:');
debugs($_SERVER['QUERY_STRING']);

debugs('');

debugs('Script Name:');		//PHP_SELF,DOCUMENT_URI
debugs($_SERVER['SCRIPT_NAME']);

debugs('');

debugs('Request URI:');		//PHP_SELF
debugs($_SERVER['REQUEST_URI']);
*/
