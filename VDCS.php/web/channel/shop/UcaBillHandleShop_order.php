<?
class UcaBillHandleShop_order extends UcaBillHandle
{
	
	public static function parserPayed($treeBill)
	{
		$_status=0;
		$ispay=$treeBill->getItemInt('ispay');
		$pid=$treeBill->getItemInt('rootid');
		$module=$treeBill->getItem('module');
		$pay_tim=DCS::timer();
		
		$sql='update db_'.$module.' set ispay='.DB::q($ispay,1).',pay_tim='.DB::q($pay_tim,1).',status=3 where id='.DB::q($pid,1);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function parserInpay($treeBill)
	{
		$_status=0;
		$ispay=$treeBill->getItemInt('ispay');
		$pid=$treeBill->getItemInt('rootid');
		$module=$treeBill->getItem('module');
		
		$sql='update db_'.$module.' set ispay='.DB::q($ispay,1).' where id='.DB::q($pid,1);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function parserUnpay($treeBill)
	{
		$_status=0;
		$ispay=$treeBill->getItemInt('ispay');
		$pid=$treeBill->getItemInt('rootid');
		$module=$treeBill->getItem('module');
		
		$sql='update db_'.$module.' set ispay='.DB::q($ispay,1).' where id='.DB::q($pid,1);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
}
