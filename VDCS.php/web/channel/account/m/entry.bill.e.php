<?
class PagePortal extends ManagePortalBaseE
{
	
	public function parsePay()
	{
		$id=queryi('id');
		$this->treeView=UcaBill::getTree($id);
		if($this->treeView->getCount()<1) return;
		if($this->treeView->getItem('ispay')==1) return;
		$money=$this->treeView->getItemNum('money');
		$moneys=$this->treeView->getItemNum('moneys');
		$paymoney=$moneys-$money;
		$this->treeView->addItem('paymoney',$paymoney);
	}
	
	public function doThemeCache()
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
	}

}
