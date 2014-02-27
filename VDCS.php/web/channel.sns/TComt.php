<?
class TComt
{
	const TableName			= 'db_mcomment';
	const FieldID			= 'id';
	
	const RowDef			= 5;
	const RowMin			= 1;
	const RowMax			= 20;
	
	
	public static function getTree($id)
	{
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1) $_status=-1;
		elseif($treeRS->getItemInt('uuid')!=$ua->id) $_status=-2;
		return $_status;
	}
	
}
?>