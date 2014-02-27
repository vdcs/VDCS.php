<?php
class ChannelShopFlowX extends ChannelShopBaseX{
	public function parseList()
	{
		global $cfg;
		$this->tableList=ShopCart::query($this->ua,'','id desc');
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$moneys=$this->tableList->getItemValue('moneys');
		if(!$moneys) $moneys=0;
		$this->addVar('moneys',$moneys);
		$this->setSucceed();
	}
	
	
	//添加订单
	public function parseAdd()
	{
		global $ctl;
		$_status=0;
		$this->doFormData();
		if($ctl->e->isCheck()){
			$this->doRaiseError();
			return;
		}else{
			$_status=ShopOrder::addData($this->ua,$this->treeData);
			//清空购物车
			if($_status) ShopCart::del($this->ua);
		}
		
		if($_status){
			$this->addVar('orderid',$this->treeData->getItem('orderid'));
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('提交订单失败');
		}
	}
	
	protected function doFormData()
	{
		$linkid=queryi('linkman');//id
		$shipping=querys('shipping');
		$shipping_price=queryn('shipping_price');
		$dtime=querys('dtime');
		$message=querys('message');
		$money=queryn('money');
		//$invoice_is=posts('invoice_is');
		
		//优惠
		$cid=posti('cid');
		
		if(!$linkid) $this->addError('请选择收货人');
		if(!$shipping) $this->addError('运送方式 不能为空');
		
		
		$linkInfo=newTree();
		$linkInfo=UcLinkman::getTree($linkid);
		$this->addData('linkid',$linkid);
		$this->addData('linkman',$linkInfo->getItem('name'));
		$this->addData('address',$linkInfo->getItem('address'));
		$this->addData('postcode',$linkInfo->getItem('postcode'));
		$this->addData('mobile',$linkInfo->getItem('mobile'));
		$this->addData('email',$linkInfo->getItem('email'));
		
		$this->addData('shipping',$shipping);
		$this->addData('shipping_price',$shipping_price);
		$this->addData('dtime',$dtime);
		$this->addData('message',$message);
		$this->addData('money',$money);
		
		$this->addData('cid',$cid);
		
		$moneys=ShopCart::getMoneys($this->ua);
		$moneys+=$shipping_price;
		$this->addData('moneys',$moneys);
		
		$this->addData('ispay',0);
		$this->addData('orderno',ShopOrder::getOrderno());//账单号
	}
}
?>