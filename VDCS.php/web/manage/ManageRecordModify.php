<?
class ManageRecordModify
{
	const TableName			= 'dbd_record_modify';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'module,rootid,umrc,umid,field,value,valueo,info,summary,isaudit,status,tim,tim_up,explain';
	
	public static function create($ma,$paramary){
		$tData=newTree();
		$tData->addItem('module',$paramary['module']);
		$tData->addItem('rootid',$paramary['rootid']);
		
		$tData->addItem('summary',$paramary['summary']);
		$tData->addItem($paramary['field1'],$paramary['value1']);//原来
		$tData->addItem($paramary['field2'],$paramary['value2']);//新的
		if(len($paramary['value1']) && len($paramary['value2'])){
			$info=$paramary['names'].'从 '.$paramary['value1'].' 修改为 '.$paramary['value2'];
		}else{
			$info=$paramary['names'];
		}
		$tData->addItem('info',$info);
		
		//专门记录下修改的字段 ??? value5,暂时
		if($paramary['value5'])  $tData->addItem('value5',$paramary['value5']);
		
		$status=$paramary['status'];
		if($status===0){
			$tData->addItem('status',0);
		}else{
			$tData->addItem('status',1);
		}
		$ischeck=$paramary['ischeck'];
		if($ischeck){
			$tData->addItem('ischeck',$ischeck);
		}else{
			$tData->addItem('ischeck',0);	
		}
		$_status=self::add($ma,$tData);
		return $_status;
	}
	
	
	public static function getTree($id,$sqlTerm=''){
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	public static function view($sqlTerm=''){
		$treeRS=newTree();
		if(!$sqlTerm) return $treeRS;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlTerm,'',1);
		$treeRS=DB::queryTree($sql);
		self::doFilterTree($treeRS);
		return $treeRS;
	}
	
	public static function doFilterTree(&$treeRS)
	{
		if($treeRS->getCount()<0) return;
		$umid=$treeRS->getItemInt('umid');
		$umrc=$treeRS->getItem('umrc');
		$maname=DB::queryValue('select name from dbs_manager where id='.$umid);
		$treeRS->addItem('manname',$maname);
	}
	
	public static function add($ma,$tData){
		$_status=0;
		$tData->addItem('umrc',$ma->rc);
		$tData->addItem('umid',$ma->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		
		if($isexec) $_status=1;
		
		//dbd_record_modify
		
		return $_status;
	}
	
	public static function query($sqlTerm='',$order='',$limit=0){
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=self::doFilterData(DB::queryTable($sql));
		return $tableData;
	}
	
	public static function querier(&$p=null,$params=array())
	{
		$tableData=newTable();
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		self::doFilterData($tableData);
		return $tableData;
	}
	
	public static function doFilterData($tableData){
		$tableData->doAppendFields('name,time,status.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$umid=$tableData->getItemValue('umid');
			$name=DB::queryTree('select name from dbs_manager where id='.$umid.'')->getItem('name');
			$tableData->setItemValue('name',$name);
			$tim=$tableData->getItemValue('tim');
			$tableData->setItemValue('time',datei('Y-m-d',$tim));
			$status=$tableData->getItemValue('status');
			switch($status){
				case 0:
					$sstatus='待审核';
					break;
				case 2:
					$sstatus='审核不通过';
					break;
				default:
					$sstatus='审核通过';
					break;			
			}
			$tableData->setItemValue('status.name',$sstatus);
		}
		return $tableData;
	}
	
	public static function edit($id,$tData){
		$_status=0;
		$updateFields='status,tim_up';
		$tData->addItem('tim_up',DCS::timer());
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$updateFields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
}
