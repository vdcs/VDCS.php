<?php
class UcaRemit
{
	const TableName			= 'dbu_remit';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'uurc,uuid,money,type,summary,checkid,status,tim,tim_up,explain';
	const RowDef			=10;
	

	public static function getTree($id){
		$treeRS=newTree();
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
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
		}else if($treeRS->getItemInt('uuid')!=$ua->id){
			$_status=6;//没权限
		}
		return $_status;
	}
	
	//添加
	public static function add($tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function create($ua,$tData)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$tData->addItem('ischeck',0);
		$statu=$tData->getItem('statu');
		if($statu)
		{
			$tData->addItem('status',1);
		}else{
			$tData->addItem('status',0);
		}		
		$_status=self::add($tData);
		return $_status;
	}
	
	//改
	public static function edit($tData,$tableUpfields,$sqlQuery)
	{
		$_status=0;
		$sql=DB::sqlUpdate(self::TableName,$tableUpfields,$tData,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function alter($id,$tData)
	{
		$_status=0;
		$tableUpfields='ischeck,checkid,status,tim_up';
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$_status=self::edit($tData,$tableUpfields,$sqlQuery);
		return $_status;
	}
	
	//查询 添加分页
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
	
	public static function doFilterData(&$tableData){
		$tableData->doAppendFields('status.name,bankname');
		$tableData->doBegin();
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.remit.bank');
		$tableTypes=VDCSDTML::getConfigTable('common.channel/account/data.assets.status');
		while($tableData->isNext()){
			$bank=$tableData->getItemValue('type');
			$status=$tableData->getItemValue('status');

			$bankname=$tableType->getTermsValue('value='.$bank,'name');
			$tableData->setItemValue('bankname',$bankname);

			$statusname=$tableTypes->getTermsValue('value='.$status,'name');
			$tableData->setItemValue('status.name',$statusname);

		}
		return $tableData;
	}
}

?>