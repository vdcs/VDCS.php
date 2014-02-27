<?php
class ShopPaFlow extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		global $cfg;
		$that->Shipping=$cfg->getTable('data.order.shipping');
		$that->Payment=$cfg->getTable('data.order.payment');
		$that->Dtime=$cfg->getTable('data.order.dtime');
	}
	
	
	public function doThemeCache(&$that)
	{
		//$that->theme->doCacheFilterTree('view','cpo.treeView');
		$that->theme->doCacheFilterLoop('shipping','cpo.Shipping');
		$that->theme->doCacheFilterLoop('payment','cpo.Payment');
		$that->theme->doCacheFilterLoop('dtime','cpo.Dtime');
	}
	
}
?>