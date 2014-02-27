<?php
class ChannelShopCheckoutX extends ChannelShopBaseX{
	public function parseList()
	{
		$this->tableList=ShopCart::query($this->ua,'','id desc');
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$moneys=$this->tableList->getItemValue('moneys');
		if(!$moneys) $moneys=0;
		$this->addVar('moneys',$moneys);
		$this->setSucceed();
	}
	
	//付款
	public function parsePay()
	{
		$orderid=queryi('orderid');
		$payment=querys('payment');
		
		//0=待付款 1=成功 2=付款确认中 3=已付款,待发货 4=已发货，待收货 5=已确认收货，待评价 9=订单取消
		 
		$tData=newTree();
		$tData->addItem('orderid',$orderid);
		$tData->addItem('payment',$payment);
		if($payment=='balance' || $payment=='onlinepay'){
			$_status=ShopPayment::payProcess($this->ua,$tData);
			if($_status==1){
				$this->setSucceed();
				return;	
			}else{
				switch($_status){
					case 2:
						$this->setMessage('账户余额不足');
						$money=UcaMoney::balance($this->ua);
						$total_price=ShopOrder::getTree($orderid)->getItemNum('money');
						$recharge=$total_price-$money;
						$this->addVar('recharge',$recharge);
						break;
					case 3:
						$this->setMessage('products添加失败');
						break;
					case 4:
						$this->setMessage('bill_order添加失败');
						break;
					case 5:
						$this->setMessage('bill_order_data添加失败');
						break;
					case 6:
						$this->setMessage('account更新或者bill_record记录失败');
						break;
					case 7:
						$this->setMessage('product_order更新失败');
						break;
				}
				$this->setStatus($_status);
			}
		}else{
			$tData->addItem('status',2);
			$_status=ShopPayment::updateShopOrder($this->ua,$tData);//不生成bill
			if($_status) $this->setSucceed();
			else $this->setStatus('failed');
		}
	}
	
	public function parseCheck()
	{
		$_status=0;
		$orderid=queryi('orderid');
		if(!$orderid){
			$this->setStatus('noorderid');
			return;
		}
		$oTree=newTree();
		$_status=ShopOrder::isCheck($this->ua,$orderid,$oTree);
		if($_status!=1){
			$this->setStatus('error');
			$this->setMessage($_status);
			return;
		}
		if($oTree->getItemInt('ispay')==1){
			$this->setStatus('payed');
			return;
		}
		$moneys=$oTree->getItemNum('moneys');
		$money=UcaMoney::getRest($this->ua);
		if($moneys<=$money) $this->setSucceed();
	}
}
?>