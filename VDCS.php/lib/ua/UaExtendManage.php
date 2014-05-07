<?
class UaExtendManage extends UaExtend
{
	
	public static function appendInfo(&$tableData,$opt=[],$rc=APP_UA)
	{
		$opt['info']=true;
		self::appendTableInfo($tableData,$opt,$rc);
	}
	
}
