<?php
class ShopPaProductView extends ChannelPaBase
{
	public function doParse(&$that)
	{
		$p_id=queryi('p_id');
		$that->treeView=ShopProduct::getTree('p_id='.DB::q($p_id,1));
		//下架商品到提示页面
		//if(!$that->treeView->getItem('p_status')) go();
		
	}
	
	
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('view','cpo.treeView');
	}
	
}
?>