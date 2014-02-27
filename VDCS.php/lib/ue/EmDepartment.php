 <?php
class EmDepartment
{
	const TableName			= 'dbe_department';
	const TablePX			= '';
	const FieldID			= 'deptid';
	const TableFields		='deptid,corpid,orderid,levelid,rootid,fatherid,types,no,name,shortname,mode,sort,type,level,names,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,logo,summary,isclock,status,tim,tim_up,tim1,tim2,tim3,tim4,tim5,explain,total_emp,total_staff,total1,total2,total3,total4,total5';
	

	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		if($id<1) return $treeRS;
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=self::doFilterTree(DB::queryTree($sql));
		return $treeRS;
	}
	//添加
	public static function addData($ua,&$tData)
	{
		$_status=0;
		$tim=DCS::timer();
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('status',1);
		$tData->addItem('tim',$tim);
		$_status=self::add($tData);
		
		//改变question状态
		$rootid=$tData->getItem('rootid');
		$process=$tData->getItem('process');

		$_status=TicketQuestion::changeProcess($rootid,$process);

		return $_status;
	}
	//添加
	public static function add(&$tData)
	{	
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		
		if($isexec) $_status=1;
	
		return $_status;
	}
	//查询
	public static function query($ua,$sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		self::doFilterData($tableData,$ua);
		return $tableData;
	}

	//查询 添加分页
	public static function querier($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		//$params['query']=DB::sqla($params['query']);//$params['query'] 额外条件
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterData($tableData,$ua);
		return $tableData;
	}
	
	
	protected function doFilterData(&$tableData,$ua)
	{
		
	}
	
}

?>