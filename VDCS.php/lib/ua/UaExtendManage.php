<?
class UaExtendManage extends UaExtend
{
	
	public static function appendInfo(&$tableData,$opt=[],$rc=APP_UA)
	{
		$opt['infok']=true;
		self::appendTableInfo($tableData,$opt,$rc);
	}
	
}
