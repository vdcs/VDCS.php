 <?
class EcDepartment extends StructData
{
	const TableName			= 'dbe_department';
	const TablePX			= '';
	const FieldID			= 'deptid';

	
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

	public static function create(&$tData)
	{
		$tData->addItem('tim',DCS::timer());
		return self::insert($tData);
	}


}
