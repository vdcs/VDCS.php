<?
class TTagsFollow extends TTags
{
	const FollowTableName			= 'db_tags_follow';
	const FollowFieldID			= 'tagid';
	//sort,tagid,uurc,uuid,status,tim
	
	
	/*
	########################################
	########################################
	*/
	public static function create($ua,$tagid,$tData=null)
	{
		$_status=2;
		if(!$tagid) return 0;
		if(self::is($ua,$tagid)<1){
			$_status=self::save($ua,$tagid,$tData);
		}
		return $_status;
	}
	public static function cancel($ua,$tagid)
	{
		$_status=2;
		if(self::is($ua,$tagid)>0){
			$_status=self::del($ua,$tagid);
		}
		return $_status;
	}
	
	
	public static function is($ua,$tagid)
	{
		$_status=0;
		if(!$tagid) return $_status;
		$sqlQuery='uuid='.$ua->id.' and tagid='.$tagid.' and status=1';
		$sql=DB::sqlSelect(self::FollowTableName,'total',self::FollowFieldID,$sqlQuery,'');
		//debugx($sql);
		$_total=DB::queryInt($sql);
		if($_total>0) $_status=1;
		return $_status;
	}
	
	public static function save($ua,$tagid,$tData=null)
	{
		$_status=0;
		if(!$tagid) return $_status;
		if(!isTree($tData)) $tData=newTree();
		$tData->addItem('sort',$sort);
		$tData->addItem('tagid',$tagid);
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		
		$FieldsAdd='sort,tagid,uurc,uuid,status,tim';
		$sql=DB::sqlInsert(self::FollowTableName,$FieldsAdd,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec){
			TTagsAction::update($tagid,'total_follow','+');
			$_status=1;
		}
		return $_status;
	}
	
	public static function del($ua,$tagid)
	{
		$_status=0;
		//$sqlQuery='uuid='.$ua->id.' and tagid='.$tagid;
		//$sql=DB::sqlDelete(self::FollowTableName,$sqlQuery);
		$tim_up=time();
		$sql='update db_tags_follow set status=0,tim_up='.$tim_up.' where (uuid='.$ua->id.' and tagid='.$tagid.' and status=1)';
		//debugx($sql);
		$isexec=DB::exec($sql);
		$sql="update db_tags set total_follow=total_follow-1 where tagid=".$tagid;
		$isexec2=DB::exec($sql);
		if($isexec && $isexec2) $_status=1;
		return $_status;
	}
	
}
?>