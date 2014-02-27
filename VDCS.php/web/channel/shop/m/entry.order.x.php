<?
class PagePortal extends ManagePortalBaseX
{
	use PortalShopOrderRef;
	

	public function doLoad()
	{
		$this->refLoad();
	}
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		
	
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseEdit()
	{
	
	}
	
	protected function doHandle()	//$mod=null,$put=1
	{
		parent::doHandle();
		if($this->isHandle()){
			$ids=$this->chn->getVar('handle.ids');
			switch($this->chn->getVar('handle.value')){
				case 'delete':
					if($this->attrm->is()) $this->attrm->doDataRemove($ids);
					break;
			}
		}
	}
	protected function parseList()
	{
		$this->doHandle();
		$this->doListServe();
	}
	
	
	protected function doListFilter(&$tableData)
	{
		
	}
	
	//修改订单的金额
	protected function parseModifyprice(){
		$id=posti('id');
		$money=postn('money');
		if($money<=0) $this->addError('价格格式不正确');
		$summary=post('summary',250);
		if($this->isRaiseError()) return;
		
		$tData=newTree();
		$tData->addItem('money',$money);
		$_status=ShopOrder::editPrice($id,$tData);
		
		if($_status) $this->setSucceed();
	}
	
	protected function parseProcess()
	{
		$orderid=queryi('orderid');
		$status=queryi('status');
		$tData=newTree();
		$tData=ShopOrder::getTree($orderid);
		$uuid=$tData->getItem('uuid');
		$ua=&Ua::instance(APP_UA);
		$ua->setID($uuid);
		if($status=='3'){//付款，生成bill
			$money=$tData->getItem('money');
			//添加账户余额
			UcaMoney::recharge($ua,$money,[
				'module'=>'shop_order','rootid'=>$orderid,
				'type'=>1,'payment'=>'审核通过用户其他方式付款'
			]);
			
			$billTree=newTree();
			$billTree->addItem('module','shop_order');//新购买
			$billTree->addItem('rootid',$orderid);
			$billTree->addItem('money',$money);
			$_status=UcaBill::build($ua,$billTree);
			
		}else{
			$tData->setItem('status',$status);
			$_status=ShopOrder::editStatus($orderid,$status);
		}
		if($_status) $this->setSucceed();
	}
	
}
?>