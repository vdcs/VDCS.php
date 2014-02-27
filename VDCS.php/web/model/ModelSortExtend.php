<?
class ModelSortExtend
{
	const KEY='sort';
	const TableName='dbs_sort';
	
	
	/*
	########################################
	########################################
	*/
	public static function doTableFilter(&$reTable,$strChannel,$type='')
	{
		$reTable->doAppendFields('id,level');
		$reTable->doItemBegin();
		for($t=0;$t<$reTable->getRow();$t++){
			$v_classid=$reTable->getItemValueInt('sortid');
			$v_ids=self::toIDS($table2,$v_classid);
			$reTable->setItemValue('id',$v_classid);
			$reTable->setItemValue('level',$reTable->getItemValueInt('levelid'));
			$reTable->doItemMove();
		}
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public static function toTable($tData,$id=0){return ModelcExtend::toTable($tData,$id);}
	public static function toTableSub($tData,$id){return ModelcExtend::toTableSub($tData,$id);}
	public static function toIDS($tData,$id){return ModelcExtend::toIDS($tData,$id);}
	
	
	
	
	/*
	########################################
	########################################
	*/
	public static function setCache($cname,$tData){ModelcExtend::setCache(self::KEY,$cname,$tData);}
	public static function getCacheAry($cname){return ModelcExtend::getCacheAry(self::KEY,$cname);}
	public static function getCacheTable($cname){return ModelcExtend::getCacheTable(self::KEY,$cname);}
	public static function doCacheUpdate($channel,$classid,$cname='')
	{
		if(!$cname) $cname=$channel.$classid;
		ModelcExtend::doCacheUpdate(self::KEY,$channel,$cname);
	}
	
}
?>