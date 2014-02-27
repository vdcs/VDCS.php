<?php
class ShopPaCheckout extends ChannelPaBase
{
	public function doParse(&$that)
	{
		global $cfg;
		$id=queryi('orderid');
		if(!$id){
			go('/shop/order');
			return;	
		}
		$that->treeView=ShopOrder::getTree($id,'uuid='.$that->ua->id);
		$status=$that->treeView->getItem('status');
		if($status){
			go('/shop/order/track?orderid='.$id);
			return;
		}
		$orderno=$that->treeView->getItem('orderno');
		$that->tableList=ShopOrder::queryDetails('','orderno='.DB::q($orderno,1),'id desc');
		$that->Payment=$cfg->getTable('data.order.payment');
	}
	
	
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('view','cpo.treeView');
		$that->theme->doCacheFilterLoop('list','cpo.tableList');
		$that->theme->doCacheFilterLoop('payment','cpo.Payment');
	}
	
}
?>