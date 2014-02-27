<?php
class ForumQuery
{
	const TableName			= 'db_forum_topic';
	const TablePX			= 't_';
	const FieldID			= 't_id';
	const TableFields		= 't_id,orderid,classid,tipid,uid,t_topic,t_subtopic,t_icon,t_style,t_prop1,t_prop2,t_prop3,t_prop4,t_prop5,t_summary,sp_keyword,sp_poll_agree,sp_poll_oppose,sp_dataname,sp_classid,sp_ip,sp_agent,t_isdigest,t_isgood,t_istop,t_islock,t_status,t_tim,t_total_view,t_total_comment,t_total_fav,t_total_regard,t_total_reply,t_reply_tim,t_reply_by,t_edit_tim,t_edit_by';
	const DataTableName		= 'db_forum_data';
	const DataTablePX		= 'd_';
	const DataFieldID		= 'd_id';
	const DataTableFields		= 'classid,rootid,orderid,uid,d_topic,d_icon,d_prop1,d_prop2,d_prop3,d_prop4,d_prop5,d_summary,d_remark,sp_code,sp_edition,sp_poll_agree,sp_poll_oppose,sp_defined,sp_ip,sp_agent,d_isroot,d_isgood,d_islock,d_status,d_tim,d_edit_tim,d_edit_by';
	
	//检测记录是否存在
	public static function view($rootid,&$treeRS=null)
	{
		$_status=1;
		$treeRS=self::getDataTree($rootid,'type=0');
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}
		return $_status;
	}
	
	
	public static function getDataTree($rootid,$sqlTerm='')
	{
		$treeRS=newTree();
		$sqlQuery=DB::sqla($sqlTerm,'rootid='.$rootid);
		$sql=DB::sqlSelect(self::DataTableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		self::doFilterTree($treeRS);
		return $treeRS;
	}
	

	protected function doFilterTree(&$treeRS)
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
		self::doFilterTable($tableData);
		return $tableData;
	}
	
	public static function querierData($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		if($ua) $params['query']=DB::sqla($params['query'],'uid='.$ua->id);//$params['query'] 额外条件
		$params['table']=self::DataTableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterDataTable($tableData);
		return $tableData;
	}
	
	
	protected function doFilterDataTable(&$tableData)
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
		
		$vData->addItem('uid',$ua->id);
		$vData->addItem(self::DataTablePX.'tim',DCS::timer());
		$vData->addItem(self::DataTablePX.'status',1);
		
		$_status=self::addData($vData);
		return $_status;
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
	
	protected function addDetails($tData)
	{
		
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
	
	
}

?>