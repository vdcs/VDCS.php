<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalAccountBillRef;
	
	
	public function doLoad()
	{
		$this->refLoad();
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
		//##########
		
		if($this->pid){
			$sqlTerm=DB::sqla('module='.DB::q('products',1),'rootid='.DB::q($this->pid,1));//根据情况改变
			$this->doAppendQuery($sqlTerm);
		}
				
		$this->doListServe();
	}
	
	
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		$paynameTable=VDCSDTML::getConfigTable('common.channel/account/data.bill.pay');
		$tableData->doAppendFields('ispay.name,roundall');
		$tableData->doBegin();
		while($tableData->isNext()){
			$ispay=$tableData->getItemValue('ispay');
			$ispays=$paynameTable->getTermsValue('value='.$ispay,'name');
			$tableData->setItemValue('ispay.name',$ispays);
			$round=$tableData->getItemValueInt('round');
			$round+=1;
			$tableData->setItemValue('roundall',$round);
		}
	}
	
	//付款
	protected function parsePay()
	{
		$_status=0;
		$money=postn('money');
		if($money<=0) $this->addError('支付金额不正确');
		if($this->isRaiseError()) return;
		$id=posti('id');//bill id
		$uuid=posti('uuid');
		$summary=postx('summary');
		$ua=Ua::instance(APP_UA);
		$ua->setID($uuid);
		
		$isend=posti('isend');//是否结清
		$set_ispay=false;
		if($isend){
			$ispay=1;
			$set_ispay=true;
		}
		
		$payment='系统支付账单';
		$pid=posti('rootid');
		$module=postx('module');
		$_status=UcaBill::refill($ua,$id,$money,$payment,$ispay,$set_ispay);//支付生成相应的数据
		
		if($_status==1){
			//记录下管理员的操作
			$vTree=newTree();
			$vTree->addItem('module','bill');
			$vTree->addItem('rootid',$id);
			$vTree->addItem('value',1);
			$vTree->addItem('summary',$summary);
			
			$_status=ManageRecordAudit::create($this->ma,$vTree);
			if($_status) $this->setSucceed();
			
			$this->setStatus('succeed');
		}else{
			$this->setMessage('error_status:'.$_status);
			if($_status==6) $this->setMessage('余额不足');
			$this->setStatus('failed');
		}
	}
	
	protected function parseListi()
	{
		$rootid=queryi('rootid');
		$tableBilli=newTable();
		$tableBilli=UcaBill::queryDataByRootid($rootid);
		$this->addTable('billi',$tableBilli);
		$this->setSucceed();
	}
	
}
?>