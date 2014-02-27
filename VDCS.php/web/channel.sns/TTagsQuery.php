<?
class TTagsQuery extends TTags
{
	
	//所有标签查询
	public static function getAllTags($fields='',$where='',$order='',$limit=0)
	{
		 return self::get($fields,$where,$order,$limit);
	}
	
	//热门标签查询
	public static function getHotTags($fields='',$where='',$order='',$limit=0){
		$where=DB::sqla('id>1005',$where);
		return self::get($fields,$where,$order,$limit);
	}
	
	
	//引导标签查询
	public static function getGuideTags($fields='',$where='',$order='',$limit=0){
		$where=DB::sqla('id>1005',$where);
		return self::get($fields,$where,$order,$limit);
	}
	
	//获取相关标签
	public static function getRelatedTags($tagids,$fields='',$where='',$order='',$limit=0)
	{
		if(!$tagids) return newTable();
		$query=DB::sqla('tagid in ('.$tagids.')',$where);
		return self::get($fields,$query,$order,$limit);
	}
	
	//通过关键字查询标签
	public static function getKeyTags($keyWord,$fields='',$where='',$order='',$limit=0){
		$where=DB::sqla('name like %'.$keyWord.'%',$where);
		return self::get($fields,$where,$order,$limit);
	}
	
	
	//获取标签信息
	public static function get($fields='',$where='',$order='',$limit=0){
		 $sql=DB::sqlQuery(self::TableName,$fields,$where,$order,$limit);
		 $tableData=DB::queryTable($sql);
		 
		 self::doDataFilter($tableData);
		 return $tableData;
	}
	
	//查询标签的相关订阅信息,通过标签id
	public static function getFollowTagsByTid($tagids,$fields='',$where='',$order='',$limit=0){
		$where=DB::sqla('tagid in ('.$tagids.')',$where);
		return self::getFollowTagsInfo($fields,$where,$order,$limit);
	}
	
	//查询标签的相关订阅信息,通过用户id
	public static function getFollowTagsByUid($uids,$fields='',$where='',$order='',$limit=0){
		$where=DB::sqla('uuid in ('.$uids.')',$where);
		return self::getFollowTagsInfo($fields,$where,$order,$limit);
	}
	

	//获取标签订阅信息
	public static function getFollowTagsInfo($fields='',$where='',$order='',$limit=0){
		$sql=DB::sqlQuery('db_tags_follow',$fields,$where,$order,$limit);
	//	debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	//获取使用该标签最多的用户
	public static function activeU($tagid,$fields='',$where='',$order='',$limit=0)
	{
		$month = date('m');
		$year = date('Y');
		$last_month = date('m') - 1;
		if($month == 1){
			$last_month = 12;
			$year = $year - 1;
		}
		$mintime=mktime(0, 0, 0, $last_month, 0, $year);
		$maxtime=mktime(0, 0, 0, $month, 0, $year);
		//$sql=DB::sqlQuery('db_mtags','rootid','(tim between '.$mintime.' and '.$maxtime.') and tagid = '.$tagid);//上个月使用情况
		$sql=DB::sqlQuery('db_mtags','rootid','tagid = '.$tagid);//至今使用情况
		$rootids=DB::queryTable($sql)->getValues('rootid');
		if(!$rootids) return;
		//$sql='select distinct(uuid) from db_mlog where id in('.$rootids.')';
		$sql='select uuid,count(uuid) as total from db_mlog where id in('.$rootids.') group by uuid order by total desc limit 9';
		$table=DB::queryTable($sql);//使用最多的9个人id和次数
		//debugTable($table);
		$uids=DB::queryTable($sql)->getValues('uuid');
		$activeUTable=newTable();
		if($uids) $activeUTable=DB::queryTable('select * from db_user where uid in('.$uids.')');
		
		$activeUTable->doAppendFields('totalTimes');
		//debugTable($activeUTable);
		$activeUTable->doItemBegin();
		while($activeUTable->isNext()){
			//$totalTimes=self::getTotalByUid($activeUTable->getItemValue('uuid'),$tagid);
			//debug($totalTimes);
			$activeUTable->setItemValue('totalTimes',$table->getTermsValue('uuid='.$activeUTable->getItemValue('uuid'),'total'));
			//$activeUTable->setItemValue('totalTimes',self::getTotalByUid($activeUTable->getItemValue('uuid'),$rootids));
		}
		return $activeUTable;
	}
	
	//获取某用户使用最多次的标签或者使用次数最多的一些标签
	public static function getMostUsedTags($uid,$num){
		$limit='';
		$uid_term='';
		if($num) $limit='limit '.$num;
		if($uid) $rootids=DB::queryTable('select id from db_mlog where uuid ='.$uid)->getValues('id');
		if($rootids){
			$tagids=DB::queryTable('select tagid,count(tagid) as total from db_mtags where rootid in('.$rootids.') group by tagid order by total desc,tim desc '.$limit)->getValues('tagid');
		}else{
			$tagids=DB::queryTable('select tagid,count(tagid) as total from db_mtags group by tagid order by total desc,tim desc '.$limit)->getValues('tagid');
		}
		if(!$tagids) return newTree();
		$where='tagid in('.$tagids.')';
		$treeRs=self::get('',$where);
		return $treeRs;
	}
	
	//近期使用的标签
	public static function getRecentUsedTags($uid,$num='')
	{
		if($num) $limit='limit '.$num.'';
		$tim_l=time()-3600*24*30;//前30天的日期
		if($uid) $rootids=DB::queryTable('select id from db_mlog where uuid ='.$uid.' and tim_up>'.$tim_l.'')->getValues('id');
		if($rootids) $tagids=DB::queryTable('select tagid,count(tagid) as total from db_mtags where rootid in('.$rootids.') group by tagid order by total desc,tim desc '.$limit)->getValues('tagid');
		if(!$tagids) return newTree();
		$where='tagid in('.$tagids.')';
		$treeRs=self::get('',$where);
		return $treeRs;
	}
	
	//近期订阅的标签
	public static function getRecentFollowTags($uid,$num='')
	{
		if($num) $limit='limit '.$num.'';
		$tim_l=time()-3600*24*30;//前30天的日期
		debugx('select tagid from db_tags_follow where uuid='.$uid.' and status=1 and tim>'.$tim_l.'');
		if($uid) $tagids=DB::queryTable('select tagid from db_tags_follow where uuid='.$uid.' and status=1 and tim>'.$tim_l.'')->getValues('tagid');
		if(!$tagids) return newTree();
		$where='tagid in('.$tagids.')';
		$treeRs=self::get('',$where);
		return $treeRs;
	}
	
	//标签的管理者
	public static function getMasters($tagid)
	{
		$sql='select uuid from db_tags_master where tagid='.$tagid.' and status=1';
		$uids=DB::queryTable($sql)->getValues('uuid');
		if(!$uids) return;
		$mastersTable=DB::queryTable('select * from db_user where uid in('.$uids.')');
		return $mastersTable;
	}
	
	//标签相关日志
	public static function getTagLogs($tagid)
	{
		$sql='select * from db_tags_log where tagid='.$tagid;
		return DB::queryTable($sql);
	}
	
	//用户总共使用该标签的次数或者该标签一共被使用的次数(如果$uid='')
	public static function getTotalByUid($uid,$tagid)
	{
		//debug('select id from db_mlog where uuid ='.$uid);
		//debug($uid);
		if($uid!='') $rootids=DB::queryTable('select id from db_mlog where uuid ='.$uid)->getValues('id');
		if($rootids){
			$totalTimes=DB::queryInt('select count(*) from db_mtags where tagid='.$tagid.' and rootid in('.$rootids.')');
		}else{
			$totalTimes=DB::queryInt('select count(*) from db_mtags where tagid ='.$tagid.'');
		}
		
		if(!$totalTimes) $totalTimes=0;
		return $totalTimes;
		/*
		$sql='select count(*) from db_mlog where id in('.$rootids.') and uuid='.$uid;
		$totalTimes=DB::queryInt($sql);
		return $totalTimes;
		*/
		
	}
	
	//过滤
	public static function doDataFilter(&$tableData){
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('logo',CommonTheme::toUploadURL($tableData->getItemValue('logo')));
		}
	}
	
	
	
	/*
	########################################
	########################################
	*/
	//通过标签标签id或者标签名获取标签信息
	public static function viewInfo($id,$name='')
	{
		if($id) $sqlQuery='tagid = '.$id;
		else $sqlQuery='name='.DB::q($name,1);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
}
?>