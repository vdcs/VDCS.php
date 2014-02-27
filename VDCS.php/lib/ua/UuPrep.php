<?
class UuPrep
{
	const TableName				= 'dba_prep';
	
	
	public static function randID()
	{
		$uid=-1;
		$sql='select * from '.self::TableName.' where isuse=0 order by rand() limit 0,1';
		$treePrep=DB::queryTree($sql);
		if($treePrep->getCount()>0) $uid=$treePrep->getItemInt('uid');
		return $uid;
	}
	public static function useID($uid)
	{
		$sql='update '.self::TableName.' set isuse=1,tim_use='.DCS::timer().' where uid='.$uid;
		DB::exec($sql);
	}
	public static function unuseID($uid)
	{
		$sql='update '.self::TableName.' set isuse=0,tim_use=0 where uid='.$uid;
		DB::exec($sql);
	}
	
}
?>