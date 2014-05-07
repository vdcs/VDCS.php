<?
class UcContactsFollow
{
	const TableName			= 'dbu_follow';
	const TablePX			= '';
	const FieldID			= 'id';
	//id,uurc,uuid,types,uurc2,uuid2,groupid,summary,status,tim,tim_up,
	
	const RowDef			= 10;
	

	public static function doEachoFilter(&$tableData,$type,$uid)
	{
		$sqlQuery='';
		$fieldu='';$fieldr='';
		switch($type){
			case 'fans':
				$uids=$tableData->getValues('uuid');
				if(!$uids) return;
				$sqlQuery='uuid='.$uid.' and uuid2 in ('.$uids.')';
				$fieldu='uuid';$fieldr='uuid2';
				break;
			case 'follow':
				$uids=$tableData->getValues('uuid2');
				if(!$uids) return;
				$sqlQuery='uuid2='.$uid.' and uuid in ('.$uids.')';
				$fieldu='uuid2';$fieldr='uuid';
				break;
			default:
				
				break;
		}
		$sql='SELECT uuid,uuid2 FROM dbu_follow WHERE '.$sqlQuery;
		$tableEacho=DB::queryTable($sql);
		$tableData->doAppendFields('eacho');
		$tableData->doBegin();
		while($tableData->isNext()){
			$eacho=$tableEacho->getTermsValue($fieldr.'='.$tableData->getItemValue($fieldu),$fieldu)?1:0;
			$tableData->setItemValue('eacho',$eacho);
		}
	}
	
        
	/*
	########################################
	########################################
	*/
	public static function querier($type,$uid,&$p,$groupid='')
	{
		//debugx($groupid);
		$total=self::getTotal($type,$uid,$groupid);//获取总条数
		if($total==0) return newTable();
		//debugx($total);
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getList($type,$uid,$p->getPage(),$p->getListNum(),$groupid);//$p->getPage()显示当前页,$p->getListNum()每页显示条数
		return $tableData;
	}
	public static function searchPserson($type,$uid,&$p,$uname='')
	{
		$total=self::getSerachTotal($type,$uid,$uname);
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getSearchPerson($type,$uid,$uname,$p->getPage(),$p->getListNum());
		return $tableData;
	}
	
	public static function getTotal($type,$uid,$groupid='')
	{
		switch($type){
			case 'follow':
				//debugx($groupid);
				if($groupid===0){
					//debugx(123);
					$sql='select count(*) from '.self::TableName.' where uuid='.$uid.' and groupid=0';//未分组的关注人数
				}elseif($groupid==''){
					$sql='select count(*) from '.self::TableName.' where uuid='.$uid.'';//总共关注人数
					//debugx($sql);
					
				}else{
					$sql='select count(*) from dbu_groups where groupid='.$groupid;//属于某个组的总人数
				}
				break;
			case 'fans':
				$sql='select count(*) from '.self::TableName.' where uuid2='.$uid.'';
				break;
			default:
				$sql='select count(*) from db_user where uid in('.$uid.')';
				break;
		}
		return DB::queryInt($sql);//获取到数据的总条数
	}
	
	public static function getList($type,$uid,$page,$row=self::RowDef,$groupid='')
	{
		switch($type){
			case 'follow':
				if($groupid===0){
					//未分组的人
					$sql='select * from '.self::TableName.' where uuid='.$uid.' and groupid=0';
				}elseif($groupid==''){
					//全部人
					$sql='select * from '.self::TableName.' where uuid='.$uid.'';
				}else{
					//属于某个组的人
					$sql='select * from '.self::TableName.' where uuid='.$uid.' and id in(select rootid from dbu_groups where groupid='.$groupid.')';
					//debugx($sql);
				}
				$sql.=' order by tim desc,id desc';
				break;
			case 'fans':
				$sql='select * from '.self::TableName.' where uuid2='.$uid.'';
				$sql.=' order by tim desc,id desc';
				break;
			default:
				$sql='select uid,names,sign,grade,total_follow,total_fans,total_post from db_user where uid in('.$uid.')';
				break;
		}
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	public static function doDataFilter(&$tableData)
	{
		global $ua;
		UaExtend::appendInfo($tableData,['fieldx'=>'sign,total_follow,total_fans,total_post','sx'=>'']);
		UaExtend::appendInfo($tableData,['fieldx'=>'sign,total_follow,total_fans,total_post','sx'=>'2','relateid'=>'uuid2']);
		return;
		$tableGroup=UcContactsGroup::getTableByID($ua->id);
		$tableData->doAppendFields('groupname');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('groupname',UcContactsGroup::getTableByFollowid($tableData->getItemValue('id')));
		}
	}
	
	public static function getSerachTotal($type,$uid,$uname='')
	{
		$uids=DB::queryTable('select uid from db_user where name like "%'.$uname.'%"')->getValues('uid');
		if(!$uids) return 0;
		switch($type){
			case 'follow':
				$sql='select count(*) from dbu_follow where uuid='.$uid.' and uuid2 in ('.$uids.')';
				break;
			case 'fans':
				$sql='select count(*) from dbu_follow where uuid2='.$uid.' and uuid in ('.$uids.')';
				break;
			case 'play':
				$sql='select count(*) from db_user where name like "%'.$uname.'%"';
				break;
			default://全站搜索
				$sql='select count(*) from db_user where name like "%'.$uname.'%" and uid in('.$uid.')';
				break;
		}
		return DB::queryInt($sql);
	}
	
	public static function getSearchPerson($type,$uid,$uname,$page,$row=self::RowDef)
	{
		$uids=DB::queryTable('select uid from db_user where u_names like "%'.$uname.'%"')->getValues('uid');
		if(!$uids) return newTable();
		switch($type){
			case 'follow':
				$sql='select * from dbu_follow where uuid='.$uid.' and uuid2 in ('.$uids.')';
				$sql.=' order by tim desc,id desc';
				break;
			case 'fans':
				$sql='select * from dbu_follow where uuid2='.$uid.' and uuid in ('.$uids.')';
				$sql.=' order by tim desc,id desc';
				break;
			case 'play':
				$sql='select uid,names,sign,grade,total_follow,total_fans,total_post from db_user where names like "%'.$uname.'%"';
				break;
			default://全站搜索
				$sql='select uid,names,sign,grade,total_follow,total_fans,total_post from db_user where names like "%'.$uname.'%" and uid in('.$uid.')';
				break;
		}
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	

	/*
	########################################
	########################################
	*/
	public static function is($ua,$uid)
	{
		$_status=0;
		$sqlQuery='uuid='.$ua->id.' and uuid2='.$uid;
		$sql=DB::sqlSelect(self::TableName,'total',self::FieldID,$sqlQuery,'');
		//debugx($sql);
		$_total=DB::queryInt($sql);
		if($_total>0) $_status=1;
		return $_status;
	}
	public static function create($ua,$uid,$tData=null)
	{
		$_status=2;
		if(self::is($ua,$uid)<1 && $uid!=$ua->id){
			$_status=self::save($ua,$uid,$tData);
		}
		return $_status;
	}
	public static function cancel($ua,$uid)
	{
		$_status=2;
		if(self::is($ua,$uid)>0){
			$_status=self::del($ua,$uid,$tData);
		}
		return $_status;
	}
	
	public static function save($ua,$uid,$tData=null)
	{
		$_status=0;
		if(!isTree($tData)) $tData=newTree();
		//$tData->addItem('appid',0);
		//$tData->addItem('types','');
		
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		
		$tData->addItem('uurc2',$ua->rc);
		$tData->addItem('uuid2',$uid);
		
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$FieldsAdd='uurc,uuid,types,uurc2,uuid2,groupid,summary,status,tim,tim_up';
		$sql=DB::sqlInsert(self::TableName,$FieldsAdd,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		//$rootid=DB::insertid();//获取followid
		if($isexec){
			$sql='update '.$ua->TableName.' set total_fans=total_fans+1,new_fans=new_fans+1 where '.$ua->FieldID.'='.$uid;
			DB::exec($sql);
			$sql='update '.$ua->TableName.' set total_follow=total_follow+1 where '.$ua->FieldID.'='.$ua->id;
			DB::exec($sql);
			//$_status=self::createNewGroups($rootid);//同时创建默认分组
		}
		return $_status;
	}
	
	public static function del($ua,$uid)
	{
		$_status=0;
		$rootid=DB::queryInt('select id from '.self::TableName.' where uuid='.$ua->id.' and uuid2='.$uid);//获取follow的id
		$sqlQuery='uuid='.$ua->id.' and uuid2='.$uid;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		if($isexec){
			$sql='update '.$ua->TableName.' set total_fans=total_fans-1 where '.$ua->FieldID.'='.$uid;
			DB::exec($sql);
			$sql='update '.$ua->TableName.' set total_follow=total_follow-1 where '.$ua->FieldID.'='.$ua->id;
			DB::exec($sql);
			//$_status=UcContactsGroup::delFromGroups($rootid);
		}
		return $_status;
	}
	
	public static function getRecentPerson($uaid)
	{
		$timr=time()-3600*24*30;
		$id='';
		$rootid='';//评论
		$dataid_com='';//回复at
		$rootid_post='';//发布at
		$uids='';
		$idAry=array();
		$uids_new='';
		//评论
		$rootid=DB::queryTable('select rootid from db_mcomment where uuid='.$uaid .' and tim>'.$timr.'')->getValues('rootid');
		//at我的人
		$dataid_com=DB::queryTable('select dataid from db_mat where dataid>0 and uuid='.$uaid.' and tim>'.$timr.'')->getValues('dataid');//回复at
		$rootid_post=DB::queryTable('select rootid from db_mat where dataid=0 and uuid='.$uaid.' and tim>'.$timr.'')->getValues('rootid');//评论at
		
		$idAry=array_merge(explode(',',$rootid),explode(',',$dataid_com),explode(',',$rootid_post));
		$id=implode(array_unique($idAry),',');//去除重复
		if($id){
			$sql='select uuid from db_mlog where id in('.$id.')';
			$uids=DB::queryTable($sql)->getValues('uuid');
			$uids=implode(array_unique(explode(',',$uids)),',');//去除重复
			$uidsAry=explode(',',$uids);
			foreach($uidsAry as $uid){
				if($uid!=$uaid){
					$uids_new.=$uid.',';
				}
			}
			$uids=trim($uids_new,',');
		}
		return $uids;
	}
	
	public static function getFollowUids($uid){
		$follow_uids=DB::queryTable('select uuid2 from dbu_follow where uuid='.$uid.' and status=1')->getValues('uuid2');
		return $follow_uids;
	}
}
?>