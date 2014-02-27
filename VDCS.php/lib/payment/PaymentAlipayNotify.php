<?
class PaymentAlipayNotify
{
	const SP='alipay';
	
	public static function is($type,$notifyid,$tradeno='')
	{
		$sqlQuery='sp='.DB::q(self::SP,1);
		$sqlQuery=DB::sqla($sqlQuery,'type='.DB::q($type,1));
		if($notifyid) $sqlQuery=DB::sqla($sqlQuery,'data1='.DB::q($notifyid,1));
		elseif($tradeno) $sqlQuery=DB::sqla($sqlQuery,'data4='.DB::q($tradeno,1));
		return PaymentNotify::is($sqlQuery);
	}
	
	public static function save($type,$ary)
	{
		$tData=newTree();
		$tData->addItem('tradeno',$ary['out_trade_no']);
		$tData->addItem('sp',self::SP);
		$tData->addItem('type',$type);
		$tData->addItem('data1',$ary['notify_id']);
		$tData->addItem('data2',$ary['notify_type']);
		$tData->addItem('data3',$ary['notify_time']);
		$tData->addItem('data4',$ary['trade_no']);
		$tData->addItem('data5',$ary['trade_status']);
		$tData->addItem('datas',VDCSDATA::enCode($ary));
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		return PaymentNotify::save($tData);
	}
	
}
