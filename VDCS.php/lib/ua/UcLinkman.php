<?
class UcLinkman
{
	const TableName			= 'dbu_linkman';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= '';


	public static function getDataTree($ua,$id=0)
	{
		$reTree=newTree();
		$reTree->addItem('type',1);
		$reTree->addItem('gender',0);
		$reTree->addItem('idtype',1);
		if($id) $reTree->doAppendTree(self::getTree($id));
		return $reTree;
	}

	public static function getTree($id)
	{
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}
		else if($treeRS->getItemInt('uuid')!=$ua->id){
			$_status=6;//没权限
		}
		return $_status;
	}
	
	//添加联系人
	public static function add($ua,$tData)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlInsertx(self::TableName,self::TableFields,$tData,null,false);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem('id',$id);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//编辑联系人
	public static function edit($ua,$id,$tData)
	{
		$_status=0;	
		$tData->addItem('tim_up',DCS::timer());		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdatex(self::TableName,self::TableFields,$tData,$sqlQuery,null);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//删除联系人
	public static function del($ua,$id)
	{
		$_status=self::isCheck($ua,$id,$treeRS);
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//查询联系人
	public static function query($sqlQuery='',$order='',$limit=0)
	{
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		//self::doFilterData($tableData);
		return $tableData;
	}
	public static function doFilterData($tableData)
	{
		$tableData->doAppendFields('tag');
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$uuid=$tableData->getItemValue('uuid');
			$uurc=$tableData->getItemValue('uurc');
			//$tableData->setItemValue('names',$names);
			if($uuid==$ua->id && $uurc==$ua->rc){
				$tableData->setItemValue('tag',1);
			}
		}	
	}
	//分页
	public static function querier($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		$params['query']=DB::sqla($params['query'],'uuid='.$ua->id);//$params['query'] 额外条件
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterData($tableData);
		return $tableData;
	}
	
	public static function setDefault($id,$ua)
	{
		$_status=0;
		$uuid=$ua->id;
		$sql_clear='update '.self::TableName.' set type="" where uuid='.DB::q($uuid,1);
		$_status=DB::exec($sql_clear);
		if($_status){
			$sql='update '.self::TableName.' set type="default" where id='.DB::q($id,1);
			$_status=DB::exec($sql);
		}
		return $_status;
	}
}
