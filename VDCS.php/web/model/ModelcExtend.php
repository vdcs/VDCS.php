<?
class ModelcExtend
{
	const ModType='channel';
	
	
	/*
	########################################
	########################################
	*/
	public static function toTree($tData,$id=0)
	{
		$re=new utilTree();
		if(isTable($tData)){
			$tData->doItemBegin();
			for($t=0;$t<$tData->getRow();$t++){
				if($tData->getItemValueInt('id')==$id) $re->setArray($tData->getItemTree()->getArray());
				$tData->doItemMove();
			}
		}
		return $re;
	}
	
	public static function toTable($tData,$id=0)
	{
		$re=new utilTable();
		if(isTable($tData)){
			$re->setFields($tData->getFields());
			$tData->doItemBegin();
			for($t=0;$t<$tData->getRow();$t++){
				if($tData->getItemValueInt('fatherid')==$id) $re->addItem($tData->getItemTree());
				$tData->doItemMove();
			}
		}
		return $re;
	}
	
	public static function toTableSub($tData,$id)
	{
		$re=new utilTable();
		if(isTable($tData) && $classid>0){
			$re->setFields($tData->getFields());
			//debugTable($tData);
			$isfather=false;
			$tData->doItemBegin();
			for($t=0;$t<$tData->getRow();$t++){
				if($tData->getItemValueInt('id')==$id){
					$isfather=true;
					$treeFather=$tData->getItemTree();
				}
				if($isfather){
					if($tData->getItemValueInt('fatherid')==$treeFather->getItemInt('id')) $re->addItem($tData->getItemTree());
				}
				$tData->doItemMove();
			}
		}
		return $re;
	}
	
	
	public static function toIDS($tData,$id,$fieldid='id')
	{
		$re='0';
		if(isTable($tData)){
			$isroot=false;
			$_id=0;
			$_rootid=0;
			$_level=0;
			$tData->doItemBegin();
			for($t=0;$t<$tData->getRow();$t++){
				if($isroot){
					if($tData->getItemValueInt('rootid')!=$_rootid) break;
					if($tData->getItemValueInt('levelid')>$_level) $re.=','.$tData->getItemValueInt($fieldid);
				}
				else{
					if($tData->getItemValueInt($fieldid)==$id){
						$_id=$tData->getItemValueInt($fieldid);
						$_rootid=$tData->getItemValueInt('rootid');
						$_level=$tData->getItemValueInt('levelid');
						$re=$_id;
						$isroot=true;
					}
				}
				$tData->doItemMove();
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function setCache($sort,$cname,$tData)
	{
		VDCSCache::setCache(self::ModType.'.'.$cname.'.'.$sort,$tData->getArray(),'config');
	}
	public static function getCacheAry($sort,$cname)
	{
		return VDCSCache::getCache(self::ModType.'.'.$cname.'.'.$sort,'config',false);
	}
	public static function getCacheTable($sort,$cname)
	{
		$reTable=newTable();
		$arys=self::getCacheAry($sort,$cname);
		if(isAry($arys)) $reTable->setArray($arys);
		return $reTable;
	}
	public static function doCacheUpdate($sort,$channel,$cname='')
	{
		if(!$cname) $cname=$channel;
		VDCSCache::delCache(self::ModType.'.'.$cname.'.'.$sort,'config');
	}
	
}
?>