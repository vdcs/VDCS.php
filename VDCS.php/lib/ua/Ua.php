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
	

	public static function useURef()
	{
		if(!defined('INC__UaURef')) require('UaURef'.EXT_SCRIPT);
	}

	

	public static function prename($k='guest')
	{
		$re=appv('var.'.$k);
		if(!$re) $re='Guest';
		return $re;
	}
	
	public static function toNames($treeU,$FieldID='id')	//self::FIELD_ID
	{
		$adata=isa($treeU)?$treeU:$treeU->getArray();
		//debugTrace();
		//debuga($adata);
		if(!($_names=$adata['_names'])
			&& !($_names=$adata['names'])
			&& !($_names=$adata['name'])
			&& !($_names=$adata['email'])
			&& !($_names=$adata['mobile'])) $_names='['.$adata[$FieldID].']';
		//debugx('names='.$_names);
		return $_names;
	}
	
}
