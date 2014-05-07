<?
class EcStaff extends StructData
{
	const KEY			= 'staff';
	const TableName			= 'dba_staff';
	const TablePX			= '';
	const FieldID			= 'staffid';

	
	public static function filterTree(&$tree)
	{
		return $tree;
	}
	public static function filterList(&$tableData)
	{
		/*
		$tableData->doAppendFields('status.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('status.name',$treeStatus->getItem($tableData->getItemValue('status')));
		}
		*/
		return $tableData;
	}
	
	public static function appendInfo(&$tableData,$opt=[])
	{
		$params=[];
		$params['field.id']=static::FieldID;
		$params['table.name']=static::TableName;
		$params['opt.px']=static::KEY.'.';
		$params['opt.relateid']=static::FieldID;
		$params['opt.fields']='name,email,mobile,names';
		$params['on.names']=true;
		StructExtend::appendTable($tableData,$opt,$params);
	}
	
}
