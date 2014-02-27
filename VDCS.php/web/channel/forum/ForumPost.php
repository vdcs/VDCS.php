<?php
class ForumPost
{
	const TableName			= 'db_forum_topic';
	const TablePX			= 't_';
	const FieldID			= 't_id';
	const TableFields		= 't_id,orderid,classid,tipid,uid,t_topic,t_subtopic,t_icon,t_style,t_prop1,t_prop2,t_prop3,t_prop4,t_prop5,t_summary,sp_keyword,sp_poll_agree,sp_poll_oppose,sp_dataname,sp_classid,sp_ip,sp_agent,t_isdigest,t_isgood,t_istop,t_islock,t_status,t_tim,t_total_view,t_total_comment,t_total_fav,t_total_regard,t_total_reply,t_reply_tim,t_reply_by,t_edit_tim,t_edit_by';
	const DataTableName		= 'db_forum_data';
	const DataTablePX		= 'd_';
	const DataFieldID		= 'd_id';
	const DataTableFields		= 'classid,rootid,orderid,uid,type,replyid,d_topic,d_icon,d_prop1,d_prop2,d_prop3,d_prop4,d_prop5,d_summary,d_remark,sp_code,sp_edition,sp_poll_agree,sp_poll_oppose,sp_defined,sp_ip,sp_agent,d_isroot,d_isgood,d_islock,d_status,d_tim,d_edit_tim,d_edit_by';
	
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}else if($treeRS->getItemInt('uid')!=$ua->id)
		{
			$_status=6;//没权限
		}
		return $_status;
	}
	
	
	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		$treeRS=self::doFilterTree($treeRS);
		return $treeRS;
	}
	
	public static function getDetailsTree($sqlTerm)
	{
		$treeRS=newTree();
		//$sqlQuery=$idField.'='.$id;
		if(!$sqlTerm) return $treeRS;
		$sql=DB::sqlSelect(self::DataTableName,'','*',$sqlTerm,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}

	protected function doFilterTree($treeRS)
	{
		
	}
	
	public static function querier($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		$params['query']=DB::sqla($params['query'],'uid='.$ua->id);//$params['query'] 额外条件
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterData($tableData);
		return $tableData;
	}
	
	
	protected function doFilterData($tableData)
	{
 		
	}
	
	public static function create($ua,&$tData)
	{
		$_status=0;
		$vData=newTree();
		$vData->addItem(self::TablePX.'topic',$tData->getItem('topic'));
		$vData->addItem('classid',$tData->getItem('classid'));
		$vData->addItem('uid',$ua->id);
		$vData->addItem(self::TablePX.'tim',DCS::timer());
		$vData->addItem(self::TablePX.'status',1);
		
		$isexec=self::add($vData);
		
		if($isexec){
			$tData->addItem('rootid',$vData->getItem('rootid'));
			$_status=self::createData($ua,$tData);
		}
		return $_status;
	}
	
	public static function createData($ua,&$tData)
	{
		$_status=0;
		$vData=newTree();
		$vData->addItem(self::DataTablePX.'topic',$tData->getItem('topic'));
		$vData->addItem(self::DataTablePX.'remark',$tData->getItem('remark'));
		$vData->addItem('classid',$tData->getItem('classid'));
		$vData->addItem('rootid',$tData->getItem('rootid'));
		$vData->addItem('replyid',$tData->getItem('replyid'));
		$vData->addItem('type',$tData->getItem('type'));
		
		$vData->addItem('uid',$ua->id);
		$vData->addItem(self::DataTablePX.'tim',DCS::timer());
		$vData->addItem(self::DataTablePX.'status',1);
		//dcsLog('vs',$vData->getValues());
		$_status=self::addData($vData);
		
		
		if($_status){
			$id=$tData->getItem('rootid');
			self::updateLast($ua,$id);//更新最后发布的人
			self::setCount($ua,$vData);//统计
		}
		
		return $_status;
	}
	
	public static function updateLast($ua,$id)
	{
		$cfields='t_reply_tim,t_reply_by';
		$_status=0;
		$tData=newTree();
		$tData->addItem('t_reply_by',$ua->id);
		$tData->addItem('t_reply_tim',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$cfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;
	}
	
	public static function setCount($ua,$tData)
	{
		$type=$tData->getItem('type');
		//统计用户
		$fields='';
		if(!$type) $fields='topic,data';
		if($type==1) $fields='data';//评论
		if($type==2) $fields='reply';//回复data,reply????
		ForumUser::setTotal($ua,$fields);
		
		
		//分类中统计
		$classid=$tData->getItem('classid');
		if(!$type) ForumClass::setTotal($classid,true);
		else ForumClass::setTotal($classid);
		if(!$type) return;
		$rootid=$tData->getItem('rootid');
		if(!$rootid) return;
		//帖子评论数
		self::setTotal($rootid,'reply');
	}
	
	public static function add(&$tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		$rootid=DB::insertid();
		$tData->addItem('rootid',$rootid);
		return $_status;
	}
	
	public static function addData(&$tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::DataTableName,self::DataTableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		$dataid=DB::insertid();
		return $_status;
	}
	
	
	public static function edit($ua,$id,$tData)
	{
		$tableUpfields='tim_up,tim_pay,ispay,status';
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$tableUpfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;	
	}
	
	
	//删除
	public static function delete($ua,$id)
	{
		$treeRS=newTree();
		$_status=self::isCheck($ua,$id,$treeRS);//权限判断应该需要改变
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//统计一些数据
	public static function setTotal($id,$fields='view')
	{
		$_status=0;
		if(!$id) return $_status;
		$fAry=utilString::toAry($fields);
		$sqladd='';
		foreach($fAry as $v){
			$f=self::TablePX.'total_'.$v;
			$sqladd.=$f.'='.$f.'+1,';
		}
		$sqladd=rtrim($sqladd,',');
		$sql='update '.self::TableName.' set '.$sqladd.' where '.self::FieldID.'='.$id;
		$_status=DB::exec($sql);
		return $_status;
	}
	
}

?>