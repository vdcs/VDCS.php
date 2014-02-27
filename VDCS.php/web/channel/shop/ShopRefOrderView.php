<?
trait ShopRefOrderView
{
	
	public function refParse(&$that)
	{
		$id=queryi('orderid');
		$that->treeView=ShopOrder::getTree($id,'uuid='.$that->ua->id);
		$orderno=$that->treeView->getItem('orderno');
		$that->tableItems=newTable();
		if($orderno) $that->tableItems=ShopOrder::queryDetails('orderno='.DB::q($orderno,1),'id desc');
	}
	
	public function refThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('view','cpo.treeView');
		$that->theme->doCacheFilterLoop('list','cpo.tableItems');
		$that->theme->doCacheFilterLoop('items','cpo.tableItems');
	}
	
}
