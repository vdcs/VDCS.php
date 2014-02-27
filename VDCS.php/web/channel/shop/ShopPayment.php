<?php
class ShopPayment{
	
	public static function payProcess($ua,&$tData)
	{
		$isenough=self::checkAccountBalance($ua,$tData);
		if(!$isenough) return 2;//余额不足
		
		$_status=self::addBill($ua,$tData);//新增了rootid
		if(!$_status) return 4;//bill_order添加失败
		
		/*
		$_status=self::editMoney($ua,$tData);
		if(!$_status) return 6;//account更新添加失败
		
		
		$tData->addItem('status',3);//余额支付
		$_status=self::updateShopOrder($ua,$tData);
		if(!$_status) return 7;//productorder添加失败
		*/
		return $_status;
	}
	
	//检测账户余额
	public static function checkAccountBalance($ua,&$tData)
	{
		$isenough=1;
		$orderid=$tData->getItem('orderid');
		$money=UcaMoney::balance($ua);
		$total_price=ShopOrder::getTree($orderid)->getItemNum('money');
		$tData->addItem('money',$total_price);
		
		if($total_price>$money){
			$tData->addItem('recharge',$total_price-$money);//差额
			$isenough=0;	
		}
		
		return $isenough;
	}
	
	
	public static function addBill($ua,&$tData)
	{
		//添加db_shop_order
		$billTree=newTree();
		$billTree->addItem('module','shop_order');//新购买
		$billTree->addItem('rootid',$tData->getItem('orderid'));
		$billTree->addItem('money',$tData->getItem('money'));
		$_status=UcaBill::build($ua,$billTree);
		return $_status;
	}
	
	public static function editMoney($ua,$tData)
	{
		//更新money并记录dbu_record_trans
		$money=$tData->getItem('money');
		$_status=UcaMoney::consume($ua,$money,[
			'module'=>'shop_order','rootid'=>$tData->getItem('orderid'),						
			'type'=>2,'payment'=>$tData->getItem('payment')
		]);
		return $_status;
	}
	
	
	public static function updateShopOrder($ua,$tData)
	{
		//更改db_shop_order的ispay和status
		$orderData=newTree();
		$orderData->addItem('payment',$tData->getItem('payment'));
		$orderData->addItem('status',$tData->getItem('status'));
		$orderData->addItem('ispay',1);
		$orderData->addItem('pay_tim',DCS::timer());
		$_status=ShopOrder::edit($ua,$tData->getItem('orderid'),$orderData);
		
		return $_status;
	}	
}

?>