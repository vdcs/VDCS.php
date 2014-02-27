<?php
class ForumUser
{
	const TableName			= 'db_forum_user';
	const TablePX			= '';
	const FieldID			= 'uid';
	const TableFields		= 'uid,uurc,uuid,groupid,name,grade,level,rank,money,emoney,points,exp,strength,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,total_data,total_topic,total_reply,islock,status,tim,tim_up,tim_post';
	
	public static function add(&$tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}	
	
	public static function checkUser($ua)
	{
		$_status=0;
		$uid=$ua->id;
		$exist=DB::queryInt('select count(*) from '.self::TableName.' where uid='.DB::q($uid,1));
		if($exist) return 1;
		$tData=newTree();
		$tData=$ua->queryTree($uid);
		$_status=self::create($ua,$tData);
		return $_status;
	}
	
	public static function create($ua,&$tData)
	{
		$_status=0;
		$vData=newTree();
		$vData->addItem('uid',$ua->id);
		$vData->addItem('uurc',$ua->KEY);
		$vData->addItem('uuid',$ua->id);
		$isexec=self::add($vData);
		
		if($isexec) $_status=1;
		return $_status;
	}
	
	//统计一些数据
	public static function setTotal($ua,$fields='data')
	{
		$_status=0;
		$uid=$ua->id;
		if(!$uid) return $_status;
		$_status=self::checkUser($ua);
		if(!$_status) return $_status;
		$fAry=utilString::toAry($fields);
		$sqladd='';
		foreach($fAry as $v){
			$f=self::TablePX.'total_'.$v;
			$sqladd.=$f.'='.$f.'+1,';
		}
		$sqladd=rtrim($sqladd,',');
		$sql='update '.self::TableName.' set '.$sqladd.' where '.self::FieldID.'='.$uid;
		$_status=DB::exec($sql);
		return $_status;
	}
	
}
?>