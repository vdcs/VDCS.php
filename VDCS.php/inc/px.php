<?
if(defined('NOVDCS')) dcsInit(false,true);

$px=queryx('px');

$classname='ChannelX'.$px;
$path=_BASE_PATH_ROOT.'common/channel/'.$classname.'.php';
if(!isFile($path)) $path=VDCS_CHANNEL_PATH.$classname.'.php';
if(isFile($path)){
	include($path);
	$classname::parser();
}
else{
	put('ClassX no found: '.$classname);
}
dcsEnd();
