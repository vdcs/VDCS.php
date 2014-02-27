<?
class PaymentNotify
{
	const TableName			= 'dbd_payment_notify';
	
	
	public static function getTree($tradeno)
	{
		$sqlQuery='tradeno='.DB::q($tradeno,1);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRecord=DB::queryTree($sql);
		return $treeRecord;
	}
	public static function is($query)
	{
		$status=0;
		$sql=DB::sqlSelect(self::TableName,'','*',$query,'',1);
		$treeNotify=DB::queryTree($sql);
		if($treeNotify->getCount()>0) $status=1;
		return $status;
	}
	
	public static function save($tData)
	{
		$status=0;
		$sql=DB::sqlInsert(self::TableName,'',$tData);
		$isexec=DB::exec($sql);
		if($isexec) $status=1;
		return $status;
	}
	
	
}
