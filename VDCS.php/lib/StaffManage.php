<?php
class StaffManage
{
	const TableName			= 'dbs_manager';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'uurc,uuid,name,email,password,home,names,location,marks,sign,avatar,prop1,prop2,prop3,prop4,prop5,grade,roles,popedom,popedoms,islock,status,tim';
	const RowDef			= 10;
	
	public static function getTree($sTerm)
	{
		$treeRS=newTree();
		$sql=DB::sqlSelect(self::TableName,'','*',$sTerm,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	public static function view($id)
	{
		$treeRs=newTree();
		$sTerm=slef::FieldID.'='.$id;
		$treeRs=self::getTree($sTerm);
		return $treeRs;
	}
	
	public static function add(&$tData)
	{
		$_status=0;
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		return $_status;
	}
	
	public static function edit($cFields,$tData,$sqlTerm)
	{
		if(!$cFields) $cFields=self::TableFields;
		$sql=DB::sqlUpdate(self::TableName,$cFields,$tData,$sqlTerm);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function create(&$tData)
	{
		$_status=0;
		
		$tData->addItem('tim',DCS::timer());
		
		$_status=self::add($tData);
		return $_status;
	}
	
	public static function alter($id,$tData)
	{
		$_status=0;
		
		$cFields='uuid,uurc';
		$sqlTerm=self::FieldID.'='.$id;
		
		$_status=self::edit($cFields,$tData,$sqlTerm);
		return $_status;
	}
	
	public static function del($id)
	{
		$_status=0;
		$sTerm=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sTerm);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function query($sqlTerm='',$order='',$limit=0)
	{
		$table=newTable();
		$sql=DB::sqlQuery(self::TableName,null,$sqlTerm,$order,$limit);
		$table=DB::queryTable($sql);
		return $table;
	}
}
?>