<?
class ChannelShopPay extends ChannelShopCart
{
	public $iorder;
	public function __construct()
	{
		$this->mode='normal';		//normal high
	}
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->iorder);
		
	}
	
	
	public function doLoad()
	{
		parent::doLoad();
		$this->iCart->setMode('readin');
		
		$this->iorder=new ShopOrder();
		$this->iorder->doLoad();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doParseModule()
	{
		if($this->getTotal()<1) go($cfg->toLinkURL('cart'));
		if(!$this->iFlow->isAddr() || !$this->iFlow->isUnite()) go($cfg->toLinkURL('cart.flow'));
		
		$ctl->treeDat->doAppendTree($this->iFlow->getAddrTree(),'addr.');
		$ctl->treeDat->addItem('_shipping',$this->iFlow->getShippingID());
		$ctl->treeDat->addItem('_payment',$this->iFlow->getPaymentID());
		$ctl->treeDat->addItem('_dtime',$this->iFlow->getDtimeID());
		
		$this->prices=$this->iCart->getVar('prices');
		$this->price_shipping=toNum($this->iFlow->tableShipping->getTermsValue('name='.$this->treeDat->getItem('_shipping'),'price'));
		$this->prices_shipping=$this->price_shipping;
		$this->prices_total=$this->prices+$this->prices_shipping;
		$this->setVar('prices.shipping',$this->prices_shipping);
		$this->setVar('prices.total',$this->prices_total);
		
		$this->doParsePay();
	}
	
	protected function doParsePay()
	{
		$this->treeData->addItem('o_invoice_is',0);
		$this->treeData->addItem('_invoice_type','');
		
		if($this->mode='normal'){
			$this->treeData->addItem('o_linkman',$this->ua->getData('realname'));
			$this->treeData->addItem('o_address',$this->ua->getData('address'));
			$this->treeData->addItem('o_postcode',$this->ua->getData('postcode'));
			$this->treeData->addItem('o_phone',$this->ua->getData('phone'));
			$this->treeData->addItem('o_email',$this->ua->getData('email'));
		}
		
		if(!isFormPost()) return;
		if($this->mode='normal'){
			$this->treeData->addItem('o_linkman',postc('o_linkman',50));
			$this->treeData->addItem('o_address',postc('o_address',200));
			$this->treeData->addItem('o_postcode',postc('o_postcode',10));
			$this->treeData->addItem('o_phone',postc('o_phone',20));
			$this->treeData->addItem('o_mobile',postc('o_mobile',20));
			$this->treeData->addItem('o_email',postc('o_email',100));
			//##########
			$this->treeData->addItem('o_contacts',postc('o_contacts',200));
			$this->treeData->addItem('o_message',postc('o_message',250));
		}
		if($this->mode='high'){
			$this->treeData->addItem('o_linkman',$this->treeDat->getItem('addr.linkman'));
			$this->treeData->addItem('o_address',r($this->treeDat->getItem('addr.areas'),'-',' ').' '.$this->treeDat->getItem('addr.address'));
			$this->treeData->addItem('o_postcode',$this->treeDat->getItem('addr.postcode'));
			$this->treeData->addItem('o_phone',$this->treeDat->getItem('addr.phone'));
			$this->treeData->addItem('o_mobile',$this->treeDat->getItem('addr.mobile'));
			$this->treeData->addItem('o_email',$this->treeDat->getItem('addr.email'));
			//##########
			$this->treeData->addItem('o_contacts',$this->treeDat->getItem('addr.contacts'));
			$this->treeData->addItem('o_message',postc('o_message',250));
		}
		//##########
		$this->treeData->addItem('o_payment',$this->treeDat->getItem('_payment'));
		$this->treeData->addItem('o_shipping',$this->treeDat->getItem('_shipping'));
		$this->treeData->addItem('o_dtime',$this->iFlow->tableDtime->getTermsValue('value='.$this->treeDat->getItem('_dtime'),'explain'));
		//##########
		$this->treeData->addItem('o_invoice_is',posti('o_invoice_is'));
		$this->treeData->addItem('_invoice_type',post('_invoice_type'));
		$this->treeData->addItem('o_invoice_type',$this->treeData->getItem('_invoice_type'));
		$this->treeData->addItem('o_invoice_explain',postc('o_invoice_explain',250));
		//$this->treeData->addItem('o_invoice_type',$cfg->getTable('data.invoice.type')->getTermsValue('value='.$this->treeData->getItem('_invoice_type'),'name'));
		//##########
		
		//debugx($this->prices.','.$this->PriceScoreSum);
		if(!$this->PriceScoreSum) $this->PriceScoreSum=0;
		if($this->PriceScoreSum>0){
			if($this->PriceScoreSum>$this->usera->getScore()){
				$ctl->e->addItem('您目前的积分不足（需扣除'.$this->PriceScoreSum.'积分）');
			}
		}
		if(!$ctl->e->isCheck()){
			if(!utilCheck::isName($this->treeData->getItem('o_linkman'))) $ctl->e->addItem('收货人姓名 不能为空');
			if(len($this->treeData->getItem('o_address'))<1) $ctl->e->addItem('收货人地址 不能为空');
			if(len($this->treeData->getItem('o_postcode'))<1) $ctl->e->addItem('邮政编码 不能为空');
			if(len($this->treeData->getItem('o_phone'))<1) $ctl->e->addItem('联系电话 不能为空');
			if(!utilCheck::isEmail($this->treeData->getItem('o_email'))) $this->treeData->addItem('o_email','');
			if(len($this->treeData->getItem('o_shipping'))<1) $ctl->e->addItem('运送方式 不能为空');
			if(len($this->treeData->getItem('o_payment'))<1) $ctl->e->addItem('付款方式 不能为空');
			if($this->treeData->getItemInt('o_invoice_is')==1 && len($this->treeData->getItem('o_invoice_explain'))<1) $ctl->e->addItem('发票抬头 不能为空');
		}
		
		$this->orderno=VDCSTIME::toConvert('',10).utilCode::getRand(2,0);
		if(!$ctl->e->isCheck()){
			$sql='select count(*) from '.$this->iorder->TableName.' where '.$this->iorder->FieldNO.'='.utilCode::toSQL($this->orderno,1).'';
			if(len($this->orderno)<1 || DB::queryInt($sql)>0) $ctl->e->addItem('当前订购商品的人数过多，请稍候再尝试订购！');
		}
		
		if($ctl->e->isCheck()) $this->doRaiseError();
		else{
			if($this->ua->isLogin()){
				$this->treeData->addItem('uuid',$this->ua->id);
				//$this->treeData->addItem('username',$this->ua->name);
			}
			else{
				$this->treeData->addItem('uuid',0);
				//$this->treeData->addItem('username',$this->ua->name);
			}
			$this->treeData->addItem('o_realname',$this->treeData->getItem('username'));
			//debugTree($this->treeData);
			//return;
			
			$timValue=DCS::timer();
			$this->setVar($this->iorder->FieldNO,$this->orderno);
			$this->treeData->addItem($this->iorder->FieldNO,$this->orderno);
			//##########
			$multiple=0;
			if($this->usera){
				$multiple=$this->usera->getMultiple();
				$this->treeData->addItem('o_discount',$this->usera->getDiscount());
				$this->treeData->addItem('o_multiple',$multiple);	//??
			}
			else{
				$this->treeData->addItem('o_discount',0);
				$this->treeData->addItem('o_multiple',0);
			}
			//##########
			$this->treeData->addItem('o_feetype','');			//商品付费方式 市场货币/站内虚拟币
			$this->treeData->addItem('o_price',$this->prices);
			$this->treeData->addItem('o_emoney',0);
			$this->treeData->addItem('o_points',0);
			$this->treeData->addItem('o_score',$this->PriceScoreSum);
			$this->treeData->addItem('o_con_emoney',0);
			$this->treeData->addItem('o_con_points',0);
			$this->treeData->addItem('o_con_score',$this->getVarInt('scores.con'));
			$this->treeData->addItem('o_con_gift','');
			//##########
			$this->treeData->addItem('o_shipping_type',$this->treeData->getItem('o_shipping'));
			$this->treeData->addItem('o_shipping_no','');
			$ctl->treeData->addItem('o_shipping_price',$this->price_shipping);
			$ctl->treeData->addItem('o_shipping_emoney',0);
			$ctl->treeData->addItem('o_shipping_points',0);
			$ctl->treeData->addItem('o_shipping_score',0);
			$ctl->treeData->addItem('o_shipping_message','');
			$ctl->treeData->addItem('o_shipping_status',0);
			$ctl->treeData->addItem('o_shipping_explain','');
			//##########
			$ctl->treeData->addItem('o_ispayment',0);
			$ctl->treeData->addItem('o_ispay',0);
			$ctl->treeData->addItem('o_pay_tim',0);
			if($ctl->treeData->getItem('o_payment')=='onlinepay'){		//是否在线支付
				$this->isPayment=true;
				$this->setVar('ispayment','yes');
				$ctl->treeData->addItem('o_ispayment',1);
			}
			//##########
			$ctl->treeData->addItem('o_type','');
			$ctl->treeData->addItem('o_status',1);
			$ctl->treeData->addItem('o_date',DCS::today());
			$ctl->treeData->addItem('o_tim',$timValue);
			$ctl->treeData->addItem('o_tim_updated',0);
			$ctl->treeData->addItem('o_explain','');
			//##########
			$ctl->treeData->addItem('o_trans',0);
			$ctl->treeData->addItem('o_trans_tim',0);
			$ctl->treeData->addItem('o_trans_message','');
			//debugTree($ctl->treeData);
			//return;
			
			DB::executeInsert($this->iorder->TableName,$this->iorder->FieldsAdd,$ctl->treeData);
			$this->orderid=DB::toQueryInt($this->iorder->TableName,'max',$this->iorder->FieldID,'');
			
			$treeOrderI=newTree();
			//$treeOrderI->addItem('orderid',$this->orderid);
			$treeOrderI->addItem($this->iorder->FieldNO,$this->orderno);
			$treeOrderI->addItem('i_type','');
			$treeOrderI->addItem('i_status',1);
			$treeOrderI->addItem('i_tim',$timValue);
			$treeOrderI->addItem('i_tim_updated',0);
			$treeOrderI->addItem('i_explain','');
			$this->iCart->tableCarts->doBegin();
			while($this->iCart->tableCarts->isNext()){
				$treeCart=$this->iCart->tableCarts->getItemTree();
				$treeOrderI->addItem('resid',$treeCart->getItemInt('id'));
				$treeOrderI->addItem('i_topic',$treeCart->getItem('name'));
				$treeOrderI->addItem('i_serial',$treeCart->getItem('serial'));
				$treeOrderI->addItem('i_prop1',$treeCart->getItem('prop1'));
				$treeOrderI->addItem('i_prop2',$treeCart->getItem('prop2'));
				$treeOrderI->addItem('i_prop3',$treeCart->getItem('prop3'));
				$treeOrderI->addItem('i_prop4',$treeCart->getItem('prop4'));
				$treeOrderI->addItem('i_prop5',$treeCart->getItem('prop5'));
				//##########
				$treeOrderI->addItem('i_amount',$treeCart->getItem('amount'));
				$treeOrderI->addItem('i_discount',$treeCart->getItemInt('discount'));
				$treeOrderI->addItem('i_multiple',$multiple);
				//##########
				$treeOrderI->addItem('i_feetype','');
				$treeOrderI->addItem('i_price',$treeCart->getItemNum('price'));
				$treeOrderI->addItem('i_emoney',$treeCart->getItemNum('sp_emoney'));
				$treeOrderI->addItem('i_points',$treeCart->getItemInt('sp_points'));
				$treeOrderI->addItem('i_score',$treeCart->getItemInt('score'));
				$treeOrderI->addItem('i_con_emoney',$treeCart->getItemNum('con_emoney'));
				$treeOrderI->addItem('i_con_points',$treeCart->getItemInt('con_points'));
				$treeOrderI->addItem('i_con_score',$treeCart->getItemInt('con_score'));
				$treeOrderI->addItem('i_con_gift',$treeCart->getItem('con_gift'));
				//##########
				$treeOrderI->addItem('i_shipping','');
				//##########
				//debugTree($treeOrderI);
				DB::executeInsert($this->iorder->iTableName,$this->iorder->iFieldsAdd,$treeOrderI);
			}
			unsetr($treeOrderI);
			
			if($this->usera){
				$this->usera->doScoreConsume($this->PriceScoreSum,$this->orderno);
				//$this->usera->doScoresUpdate($this->treeCarts->getItemInt('con_score_sum_multiple'),$this->orderno);
			}
			
			$this->iCart->doClear();
			//$this->iFlow->doClear();
			
			$this->setVar('_flow','pay.order');
			$theme->setModule('order');
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		parent::doThemeCache();
	}
}
?>