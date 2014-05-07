<?
class Autoload
{

	public static function path($file,$debug=false)
	{
		if(DEBUGV=='autoload') $debug=true;
		$channel_assists=null;
		if(defined('APP_CHANNEL_ASSISTS')) $channel_assists=toSplit(APP_CHANNEL_ASSISTS,',');
		/*
		$ary=array('channel/','channel/lib/',APP_CHANNEL.'/',APP_PORTAL.'/',APP_CHANNEL.'/lib/',APP_PORTAL.'/lib/','lib/');
		for($a=0;$a<count($ary);$a++){
			$path=_BASE_PATH_ROOT.$ary[$a].$file;
			if($debug) debugx('AutoloadPath ROOT: '.$path);
			if(is_file($path)) return $path;	//break;
			$path='';
		}
		*/
		$ary=array('channel/','channel/lib/','channel/'.APP_CHANNEL.'/','channel/'.APP_PORTAL.'/','include/lib/','include/module/','include/model/','include/','');
		if($channel_assists){
			foreach($channel_assists as $value){
				//if($value) array_push($ary,['channel/'.$value.'/']);
				if($value) array_push($ary,array('channel/'.$value.'/'));
			}
		}
		for($a=0;$a<count($ary);$a++){
			$path=_BASE_PATH_COMMON.$ary[$a].$file;
			if($debug) debugx('AutoloadPath COMMON: '.$path);
			if(is_file($path)) return $path;	//break;
			$path='';
		}
		if(!$path && APP_VAX){
			$ary=toSplit(APP_VAX,',');
			array_push($ary,'lib','module','');
			//print_r($ary);
			for($a=0;$a<count($ary);$a++){
				$path=VAX_PATH.$ary[$a].'/'.$file;
				if($debug) debugx('AutoloadPath VAX: '.$path);
				if(is_file($path)) return $path;	//break;
				$path='';
			}
		}
		if(!$path && defined('APP_PATH_ASSIST')){
			$ary=array('lib/','module/','');
			for($a=0;$a<count($ary);$a++){
				$path=APP_PATH_ASSIST.$ary[$a].$file;
				if($debug) debugx('AutoloadPath ASSIST: '.$path);
				if(is_file($path)) return $path;	//break;
				$path='';
			}
		}
		//debugx('--');
		if(!$path && function_exists('__autoload_path')){
			$path=__autoload_path($file);
			if($path) return $path;
		}
		if(!$path && function_exists('mautoload_path')){
			$path=mautoload_path($file);
			if($path) return $path;
		}
		if(!$path){
			$ary=array();
			if($channel_assists){
				foreach($channel_assists as $value){
					if($value) array_push($ary,'web/'.VDCS_CHANNELA.'/'.$value.'/');
				}
			}
			array_push($ary,'web/'.VDCS_CHANNELA.'/','web/channel/'.APP_CHANNEL.'/','web/channel/'.APP_PORTAL.'/');
			if($channel_assists){
				foreach($channel_assists as $value){
					if($value) array_push($ary,'web/channel/'.$value.'/');
				}
			}
			array_push($ary,'web/'.VDCS_CHANNELA.'/'.APP_CHANNEL.'/','web/'.VDCS_CHANNELA.'/'.APP_PORTAL.'/');
			array_push($ary,'web/channel/','web/common/','web/module/','web/model/','web/');
			array_push($ary,'lib/model/','lib/oauth/','lib/payment/','lib/interface/','lib/ue/','inc/external/','lib/ua/','lib/',
							'util/','');			//,'module/','model/'
			//if($debug) debuga($ary);
			for($a=0;$a<count($ary);$a++){
				$path=VDCS_PATH.$ary[$a].$file;
				if($debug) debugx('AutoloadPath VDCS: '.$path);
				if(is_file($path)) return $path;	//break;
				$path='';
			}
		}
		if(!$path && DCS::$PATH_AL) $path=self::pathi(DCS::$PATH_AL,'',$file,$debug);
		if($debug) debugx('path = '.$path);
		return $path;
	}
	
	public static function pathi($ary,$basepath,$file,$debug=false,$type='i')
	{
		$path='';
		for($a=0;$a<count($ary);$a++){
			$path=$basepath.$ary[$a].$file;
			if($debug) debugx('AutoloadPath '.$type.': '.$path);
			if(is_file($path)) return $path;	//break;
			$path='';
		}
		return $path;
	}

	public static function compatFalse($class)
	{
		if(inp('CI_,Ice',substr($class,0,3))>0) return true;
		return false;
	}
	
}
/*
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/channel/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/channel/lib/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/channel/APP_CHANNEL/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/channel/APP_PORTAL/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/include/lib/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/include/module/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/include/model/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/include/cache.php
AutoloadPath COMMON: /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/common/cache.php
AutoloadPath VAX: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VAX.php/wx/cache.php
AutoloadPath VAX: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VAX.php/lib/cache.php
AutoloadPath VAX: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VAX.php/module/cache.php
AutoloadPath VAX: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VAX.php//cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/util/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/model/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/oauth/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/payment/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/interface/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/ua/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/ue/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/inc/external/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/lib/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel./APP_CHANNEL/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel./APP_PORTAL/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel./cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel/APP_CHANNEL/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel/APP_PORTAL/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/channel/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/common/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/lib/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/module/cache.php
AutoloadPath VDCS: /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/model/cache.php
*/
