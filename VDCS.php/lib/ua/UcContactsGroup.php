<?
class UcContactsGroup
{
	const TableName			= 'dbu_group';
	const TablePX			= '';
	const FieldID			= 'id';
	//id,uurc,uuid,types,uurc2,uuid2,groupid,summary,status,tim,tim_up
	
	public static function createGroup($ua,$name,$summary,$tData=null)
	{
		$insertid=0;
		if(!isTree($tData)) $tData=newTree();
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('name',$name);
		$tData->addItem('summary',$summary);
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		
		$FieldsAdd='uuid,name,summary,status,tim';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $insertid=DB::insertid();
		return $insertid;
	}
	
	//删除小组dbu_group
	public static function delGroup($id)
	{
		$sqlQuery='id='.$id.'';
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec){
			//所有followid
			$sql='select rootid from dbu_groups where groupid='.$id.'';
			$rootids=DB::queryTable($sql)->getItemValue('rootid');
			$sqlQuery='groupid='.$id.'';
			$sql=DB::sqlDelete('dbu_groups',$sqlQuery);
			debugx($rootids);
			DB::exec($sql);
			return $rootids;//返回所有followid
		}
		return 0;
	}
	
	//编辑分组信息
	public static function editGroup($id,$name,$summary='')
	{
		$sql="update dbu_group set name='".$name."' and summary='".$summary."' where id='".$id."'";
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}



	//为用户添加分组dbu_groups	添加用户到某个组
	public static function createNewGroups($rootid,$groupid)
	{
		$_status=0;
		if(!$rootid || !$groupid) return $_status;
		$tData=newTree();
		$tData->addItem('groupid',$groupid);
		$tData->addItem('rootid',$rootid);
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		
		$FieldsAdd='groupid,rootid,status,tim';
		$sql=DB::sqlInsert('dbu_groups',$FieldsAdd,$tData);
		$isexec=DB::exec($sql);
		if($isexec){
			$_status=1;
			self::isGroupMember($rootid);
		}
		return $_status;
	}
	
	//将用户从分组中删除dbu_groups
	public static function delFromGroups($rootid,$groupid='')
	{
		$_status=0;
		if(!$rootid && !$groupid) return $_status;
		//$sqlQuery='rootid='.$rootid.' and groupid='.$groupid;
		if(!self::isGroupMember($rootid)){
			return 1;	
		}
		if(!$groupid){
			$sqlQuery='rootid='.$rootid;
		}else{
			$sqlQuery=DB::sqla('rootid='.$rootid,'groupid='.$groupid);
		}
		$sql=DB::sqlDelete('dbu_groups',$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec){
			$_status=1;
			self::isGroupMember($rootid);
		}
		//debugx($_status);
		return $_status;
	}
	
	
	//判断是否已经是已分组成员
	public static function isGroupMember($rootid)
	{
		$sql='select count(*) from dbu_groups where rootid='.$rootid.'';
		$isMember=DB::queryInt($sql);
		//如果不属于任何组，则改变状态为未分组
		if(!$isMember){
			$sql='update dbu_follow set groupid=0 where id='.$rootid.'';
			DB::exec($sql);	
		}else{
			$sql='update dbu_follow set groupid=1 where id='.$rootid.'';
			DB::exec($sql);		
		}
		return $isMember;
	}
	

	
	//返回某用户创建的所有组
	public static function getTableByID($uuid)
	{
		$sql='select * from dbu_group where uuid='.$uuid.' and status=1';
		$reTable=DB::queryTable($sql);
		return $reTable;
	}
	
	//返回某一个用户所属的组
	public static function getTableByFollowid($rootid)
	{
		$sql='select groupid from dbu_groups where rootid='.$rootid.'';
		$groupids=DB::queryTable($sql)->getValues('groupid');
		//debugx($groupids);
		if($groupids){
			$sql='select * from dbu_group where id in('.$groupids.') and status=1';
			$groupnames=DB::queryTable($sql)->getValues('name');
		}else{
			$groupnames='未分组';	
		}
		return $groupnames;
	}
	

}
?>