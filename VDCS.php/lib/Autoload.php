<?
class Autoload
{

	public static function path($file,$debug=false)
	{
		//if($file=='ShopRefOrderView.php') $debug=true;
		$channel_assists=null;
		if(defined('APP_CHANNEL_ASSISTS')) $channel_assists=toSplit(APP_CHANNEL_ASSISTS,',');
		$ary=array('channel/','channel/lib/',APP_CHANNEL.'/',APP_PORTAL.'/',APP_CHANNEL.'/lib/',APP_PORTAL.'/lib/','lib/');
		for($a=0;$a<count($ary);$a++){
			$path=_BASE_PATH_ROOT.$ary[$a].$file;
			if($debug) debugx('AutoloadPath ROOT: '.$path);
			if(is_file($path)) return $path;	//break;
			$path='';
		}
		$ary=array('channel/','channel/lib/','channel/'.APP_CHANNEL.'/','channel/'.APP_PORTAL.'/','include/channel/'.APP_CHANNEL.'/','include/channel/'.APP_PORTAL.'/','include/channel/','include/lib/','include/module/','include/model/','');
		if($channel_assists){
			foreach($channel_assists as $channel_a){
				//if($channel_a) array_push($ary,['channel/'.$channel_a.'/']);
				if($channel_a) array_push($ary,array('channel/'.$channel_a.'/'));
			}
		}
		for($a=0;$a<count($ary);$a++){
			$path=_BASE_PATH_COMMON.$ary[$a].$file;
			if($debug) debugx('AutoloadPath ROOT: '.$path);
			if(is_file($path)) return $path;	//break;
			$path='';
		}
		//debugx('--');
		if(!$path && function_exists('_autoload_path')){
			$path=_autoload_path($file);
			if($path) return $path;
		}
		if(!$path){
			$ary=array('','util/','lib/','web/',		//,'module/','model/'
				'lib/model/','lib/oauth/','lib/payment/','lib/interface/','lib/ua/','lib/ue/','inc/external/',
				'web/channel.'.VDCS_WEB_CHANNEL.'/'.APP_CHANNEL.'/','web/channel.'.VDCS_WEB_CHANNEL.'/'.APP_PORTAL.'/');
			if($channel_assists){
				foreach($channel_assists as $channel_a){
					if($channel_a) array_push($ary,'web/channel.'.VDCS_WEB_CHANNEL.'/'.$channel_a.'/');
				}
			}
			array_push($ary,'web/channel.'.VDCS_WEB_CHANNEL.'/','web/channel/'.APP_CHANNEL.'/','web/channel/'.APP_PORTAL.'/');
			if($channel_assists){
				foreach($channel_assists as $channel_a){
					if($channel_a) array_push($ary,'web/channel/'.$channel_a.'/');
				}
			}
			array_push($ary,'web/channel/','web/common/','web/lib/','web/module/','web/model/');
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
