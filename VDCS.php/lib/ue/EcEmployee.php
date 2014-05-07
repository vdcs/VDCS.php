<?
class EcEmployee extends StructData
{
	const TableName			= 'dbe_employee';
	const TablePX			= '';
	const FieldID			= 'empid';

	
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
	
	
	public static function add($tData)
	{
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		return self::insert($tData);
	}
	public static function edit($id,$tData)
	{
		$tData->addItem('tim_up',DCS::timer());
		return self::update($id,$tData);
	}
	
}
