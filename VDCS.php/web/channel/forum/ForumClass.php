<?php
class ForumClass
{
	const TableName			= 'dbs_class';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'channel,classid,orderid,levelid,rootid,fatherid,types,no,name,shortname,mode,sort,type,level,names,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,logo,summary,popedom,managers,configure,dirname,dirpath,dirpaths,issp,sp_popedom,sp_emoney,sp_points,status,tim,tim_up,explain,total,total_data,total_day,total_week,total_month';

	public static function setCount($ua,$tData)
	{
		$type=$tData->getItem('type');
		//统计用户
		$fields='';
		if(!$type) $fields='topic,data';
		if($type==1) $fields='data';//评论
		if($type==2) $fields='reply';//回复data,reply????
		ForumUser::setTotal($ua,$fields);
		
		
		if(!$type) return;
		$rootid=$tData->getItem('rootid');
		if(!$rootid) return;
		$sql='select rootid from '.self::DataTableName.' where '.self::DataFieldID.'='.$rootid;
		$t_id=DB::queryInt($sql);
		if(!$t_id) return;
		//帖子评论数
		self::setTotal($t_id,'comment');
		
	}
	
	//统计一些数据
	public static function setTotal($id,$istopic=false)
	{
		$_status=0;
		if(!$id) return $_status;
		$sqladd='';
		if($istopic) $sqladd.='total=total+1,';
		$sqladd.='total_data=total_data+1,';
		
		$now=DCS::timer();//现在
		$nowl=$now-1;//前一秒
		$date_now=datei('Ymd',$now);
		$date_l=datei('Ymd',$nowl);
		if($date_l!=$date_now) $sqladd.='total_day=1,';
		else $sqladd.='total_day=total_day+1,';
		
		
		$sqladd=rtrim($sqladd,',');
		$sql='update '.self::TableName.' set '.$sqladd.' where `classid`='.$id.' and channel="forum"';
		$_status=DB::exec($sql);
		return $_status;
	}
	
}

?>