<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

//define('_BASE_DIR_ROOT','/dir/');
define('_BASE_DIR_DATA','../data/');
define('_BASE_DIR_UPLOAD','../upload/');
define('_BASE_PATH_ROOT',substr(dirname(__FILE__),0,-14));
if(is_file($pathv=dirname(_BASE_PATH_ROOT).'/VDCS.php/VDCS.php') || is_file($pathv='/usr/VSAP/VDCS.php/VDCS.php')) define('VDCS_PATH',substr($pathv,0,-8));
elseif(is_file($pathv='D:/VSAP/VDCS.php/VDCS.php') || is_file($pathv='E:/wwwroot/VDCS/VDCS.php/VDCS.php/VDCS.php')) define('VDCS_PATH',substr($pathv,0,-8));
else die('Require VDCS!');

define('DEBUG_LOG',			1);


//define('APP_VAX',			'');
//define('APP_CHANNELA',		'');
//define('APP_THEMER',			'');
//define('APP_UA',			'account');
//define('APP_DOMAIN',			'vdcs.cn');
//define('APP_BASEURL',			'http://php.vdcs.cn/');


if(is_file('config.define.php')) include('config.define.php');


require(VDCS_PATH.'VDCS.php');
if(!defined('NOVDCS')) dcsInit(true,true);
