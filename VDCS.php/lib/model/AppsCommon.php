<?
class AppsCommon
{
	
	public static function opt(&$opt)
	{
		if(!isa($opt)){
			$api=$opt;
			$opt=array();
			$opt['api']=$api;
		}
		return $opt;
	}

	public static function encryptKey($key,$tim=null)
	{
		if(is_null($tim)) $tim=DCS::timer();
		return utilCoder::toMD5($tim.','.$key);
	}

}
