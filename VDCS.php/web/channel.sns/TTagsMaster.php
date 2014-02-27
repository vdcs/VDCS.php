<?
class TTagsMaster extends TTags
{
	const MasterTableName			= 'db_tags_master';
	const MasterFieldID			= 'tagid';
	//sort,tagid,uurc,uuid,status,tim
	
	
	/*
	########################################
	########################################
	*/
	public static function create($ua,$tagid,$tData=null)
	{
		$_status=2;
		if(self::isMaster($ua,$tagid)<1){
			$_status=self::save($ua,$tagid,$tData);
		}
		return $_status;
	}
	
	public function isMaster($ua,$tagid)
	{
		$_status=0;
		$sql='select id from db_tags_master where uuid='.$ua->id.' and tagid='.$tagid;
		$_total=DB::queryInt($sql);
		if($_total>0) $_status=1;
		//debug($sql);
		return $_status;
	}
	
	public static function save($ua,$tagid,$summary,$tData=null)
	{
		$_status=0;
		if(!isTree($tData)) $tData=newTree();
		$tData->addItem('tagid',$tagid);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('status',0);
		$tData->addItem('summary',$summary);
		$tData->addItem('tim',DCS::timer());
		
		$FieldsAdd='tagid,uuid,status,summary,tim';
		$sql=DB::sqlInsert(self::MasterTableName,$FieldsAdd,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	
	//待改
	public static function cancel($ua,$tagid)
	{
		$_status=2;
		if(self::is($ua,$tagid)>0){
			$_status=self::del($ua,$tagid);
		}
		return $_status;
	}
	public static function del($ua,$tagid)
	{
		$_status=0;
		$sqlQuery='uuid='.$ua->id.' and tagid='.$tagid;
		$sql=DB::sqlDelete(self::FollowTableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$sql="update db_tags set total_follow=total_follow-1 where tagid=".$tagid;
		$isexec2=DB::exec($sql);
		if($isexec && $isexec2) $_status=1;
		return $_status;
	}
	
}
?>