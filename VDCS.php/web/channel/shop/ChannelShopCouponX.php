<?php
class ChannelShopCouponX extends ChannelShopBaseX{
	public function parseList()
	{
	
	}
	
	
	public function parseCoupon()
	{
		global $cfg;
		$this->tableList=ShopCart::queryCoupon('uuid='.DB::q($this->ua->id,1),'id desc');
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$moneys=$this->tableList->getItemValue('moneys');
		if(!$moneys) $moneys=0;
		$this->addVar('moneys',$moneys);
		$this->setSucceed();
	}
	
	public function parseGet()
	{
		$type=queryi('type');
		$p_id=queryi('p_id');
		
		$money=queryn('money');
		
		if($type==1) $this->getCouponProduct($p_id);
		if($type==2) $this->getCouponOrder($money);
		
		$this->setTable($this->tableList);
		/*
		if($this->tableList->getRow()>0) $this->setSucceed();
		else $this->setMessage('暂无可以使用的优惠券');
		*/
		$this->setSucceed();
	}
	
	protected function getCouponProduct($p_id)
	{
		$sqlTerm=DB::sqla('type=1','p_id='.DB::q($p_id,1));
		$this->tableList=ShopCoupon::query($sqlTerm,'date_expire');
	}
	
	protected function getCouponOrder($money)
	{
		$oTree=newTree();
		$sqlTerm=DB::sqla('type=2','price_lowest<='.DB::q($money,1));
		$this->tableList=ShopCoupon::query($sqlTerm,'date_expire');
	}
	
	public function parseCheck()
	{
		$type=queryi('type');
		$id=queryi('cid');
		$code=querys('code');
		
		$cartid=queryi('cartid');
		$p_id=queryi('p_id');
		
		if($type==1){
			$sqlTerm=DB::sqla('p_id='.DB::q($p_id,1),'id='.DB::q($id,1));
			$_status=ShopCoupon::check($id,$code,$cartid,$sqlTerm);
		}
		
		if($type==2){
			$_status=ShopCoupon::checkOrder($id,$code);
		}
		
		if($_status) $this->setSucceed();
		else $this->setMessage('优惠码不正确');
	}
	
	public function parseTest()
	{
		$tree=DB::queryTree('select * from db_coupon');
		$f=$tree->getFields();
		debugx($f);
	}
}
?>