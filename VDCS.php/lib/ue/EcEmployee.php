<?php
class EcEmployee{
	const TableName			= 'dbe_employee';
	const TablePX			= '';
	const FieldID			= 'empid';
	const TableFields		= 'empid,corpid,orderid,staffid,deptid,posid,date_join,date_formal,value1,value2,value3,value4,value5,value6,value7,value8,value9,value10,defined,remark,roles,grade,type,status,tim,tim_up';

	public static function getTree($id,$sqlTerm=''){
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		//$treeRS=self::doFilterTree(DB::queryTree($sql));
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	protected function doFilterTree($treeRS){
		
	}
	
	//检测记录是否存在
	public static function isCheck($id,&$treeRS=null){
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
	public static function add($tData){
		$_status=0;
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		
		if($isexec) $_status=1;
	
		return $_status;
	}
	
	//编辑
	public static function edit($id,$tData){
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,self::TableFields,$tData,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//删除
	public static function delete($id){
		$_status=self::isCheck($id,$treeRS);
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//查询
	public static function query($ua,$sqlTerm='',$order='',$limit=0){
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		if(!$ua) return $tableData;
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		$tableData=self::doFilterData($tableData);
		return $tableData;
	
	}
	public static function queryr($ua,$sqlTerm='',$order='',$limit=0){
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		//if(!$ua) return $tableData;
		//$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		//$tableData=self::doFilterData($tableData);
		return $tableData;
	
	}
	
	protected function doFilterData($tableData)
	{
	}
}

?>