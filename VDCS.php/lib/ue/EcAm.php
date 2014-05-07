<?
class EcAm extends EcStaff
{
	


	public static function appendInfo(&$tableData,$opt=[])
	{
		$params=[];
		$params['field.id']=static::FieldID;
		$params['table.name']=static::TableName;
		$params['opt.px']='am.';
		$params['opt.relateid']='uamid';
		$params['opt.fields']='name,email,mobile,names';
		$params['on.names']=true;
		StructExtend::appendTable($tableData,$opt,$params);
	}
	

}
