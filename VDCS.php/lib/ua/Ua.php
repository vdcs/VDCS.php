<?
defined('APP_UA')		|| define('APP_UA','user');
class Ua
{
	
	public static function obj($rc=APP_UA,$ex='')
	{
		$className='Ua'.ucfirst($rc).ucfirst($ex);
		return new $className();
	}
	public static function init(&$rc=APP_UA,$ex='')
	{
		global $uu;
		$res=$rc.$ex;
		if(!$uu[$res]){
			$uu[$res]=self::obj($rc,$ex);
			$uu[$res]->init();
		}
	}
	public static function &instance($rc=APP_UA,$ex='')
	{
		global $uu;
		$res=$rc.$ex;
		if(!$uu[$res]){
			self::init($rc,$ex,$isinit);
		}
		return $uu[$res];
	}
	
}
?>