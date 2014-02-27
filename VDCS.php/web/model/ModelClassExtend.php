<?
class ModelClassExtend
{
	const KEY='class';
	const TableName='dbs_class';
	
	
	/*
	########################################
	########################################
	*/
	public static function doTableFilter(&$reTable,$strChannel,$type='')
	{
		$table2=newTable();
		$table2->setArray($reTable->getArray());
		//##########
		$treeConfig=CommonChannelExtend::getTree($strChannel,'configure',true);
		//debugTree($treeConfig);
		$urlPage=$treeConfig->getItem('url.list');
		$urlClass=$treeConfig->getItem('url.class');
		//##########	compat
		if(len($urlClass)<1){
			if(appv('url.mode')=='rewrite'){	//VDCSDTML::URL_SCRIPT
				$urlClass='/'.$strChannel.'/class_{$classid}.html';
			}
			else{
				$urlClass='/'.$strChannel.'/class.php?classid={$classid}';
			}
		}
		//##########
		$treeSQL=CommonChannelExtend::getTree($strChannel,'sql',true);
		$tableName=$treeSQL->getItem('struct.list.table');
		if(len($tableName)<1) $tableName=$treeSQL->getItem('struct.table.name');
		unsetr($treeSQL);
		//##########
		$reTable->doAppendFields('id,level,ids,linkurl,curl,_space,_total,_url');
		$reTable->doItemBegin();
		for($t=0;$t<$reTable->getRow();$t++){
			$v_classid=$reTable->getItemValueInt('classid');
			$v_ids=self::toIDS($table2,$v_classid);
			$reTable->setItemValue('id',$v_classid);
			$reTable->setItemValue('level',$reTable->getItemValueInt('levelid'));
			$reTable->setItemValue('ids',$v_ids);
			$reTable->setItemValue('linkurl',rd($urlPage,'classid',$v_classid));
			$reTable->setItemValue('curl',rd($urlClass,'classid',$v_classid));
			$v_space='';
			for($s=0;$s<$reTable->getItemValueInt('levelid');$s++){
				$v_space.='&nbsp; ';
			}
			$reTable->setItemValue('_space',$v_space);
			$sql='select count(*) from '.self::TableName.' where classid in ('.$v_ids.')';
			$reTable->setItemValue('_total',DB::queryInt($sql));
			//#####
			$v_channel=$reTable->getItemValue('channel');
			if(!$v_channel) $v_channel=$strChannel;
			$_url=r($reTable->getItemValue('linkurl'),'/'.$strChannel.'/','/'.$v_channel.'/');
			$reTable->setItemValue('_url',$_url);
			//#####
			$reTable->doItemMove();
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toTree($tData,$id=0){return ModelcExtend::toTree($tData,$id);}
	public static function toTable($tData,$id=0){return ModelcExtend::toTable($tData,$id);}
	public static function toTableSub($tData,$id){return ModelcExtend::toTableSub($tData,$id);}
	public static function toIDS($tData,$id,$fieldid='classid'){return ModelcExtend::toIDS($tData,$id,$fieldid);}
	
	
	/*
	########################################
	########################################
	*/
	public static function setCache($cname,$tData){ModelcExtend::setCache(self::KEY,$cname,$tData);}
	public static function getCacheAry($cname){return ModelcExtend::getCacheAry(self::KEY,$cname);}
	public static function getCacheTable($cname){return ModelcExtend::getCacheTable(self::KEY,$cname);}
	public static function doCacheUpdate($channel,$cname=''){ModelcExtend::doCacheUpdate(self::KEY,$channel,$cname);}
	
	
	/*
	########################################
	########################################
	*/
	public static function toSQLQuery($channel,$classid)
	{
		return 'classid in (select classid from '.self::TableName.' where (classid='.$classid.' or fatherid='.$classid.') and channel=\''.$channel.'\')';
	}
	
}
?>