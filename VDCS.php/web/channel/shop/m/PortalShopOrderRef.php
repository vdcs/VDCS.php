<?php
trait PortalShopOrderRef
{
	protected $DefaultPrepageNum=10000;
	
	protected function refLoad()
	{
		
	}
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		//$this->theme->doCacheFilterLoop('record','cpo.tableRecord');
	}
	
	
	//####################
	protected function refAddLoad()
	{
		//$this->FormFile='add';//使用同一个模板form.add.xcml
		if(!$this->isChecked('lock')) return false;
		
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refEditLoad()
	{
			
	}
	
	protected function refViewLoad(){
		$orderid=queryi('id');
		$this->treeView=ShopOrder::getTree($orderid);
		$uua=&Ua::instance(APP_UA);
		$uTree=newTree();
		$uTree=$uua->queryTree($this->treeView->getItemInt('uuid'));
		$orderno=$this->treeView->getItem('orderno');
		$this->tableList=ShopOrder::queryDetails('orderno='.DB::q($orderno,1),'id desc');
	}
	
}
?>