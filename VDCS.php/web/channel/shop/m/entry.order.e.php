<?php
class PagePortal extends ManagePortalBaseE
{
	public function parseModifyprice(){
		$orderid=queryi('orderid');
		if($orderid) $this->treeData=ShopOrder::getTree($orderid);
	}
	
	
	public function doThemeCache(){
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		//$this->theme->doCacheFilterLoop('uagroup','cpo.tableUaGroup');
	}
}
?>