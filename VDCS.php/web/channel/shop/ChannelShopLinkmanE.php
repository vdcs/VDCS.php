<?php
class ChannelShopLinkmanE extends WebPortalBase{
	use WebServeRefEle;
	use WebPortalRefControl;
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		$id=queryi('id');
		$this->treeView=newTree();
		if($id) $this->treeView=UcLinkman::getTree($id);
		$this->theme->setPage('linkman.tpl');
	}
	
	public function doThemeCache()
	{
		global $cfg,$theme;
		$theme->doCacheFilterTree('view','cpo.treeView');
	}	
}
?>