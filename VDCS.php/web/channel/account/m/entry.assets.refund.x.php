<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalAssetsRefundRef;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->doFormCheck();
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('tim',DCS::timer());
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		
		$money=$this->treeData->getItemNum('money');
		$this->treeRS=newTree();
		$this->treeRS->addItem('uuid',$uuid);
		$this->treeRS->addItem('id',$id);
		$this->treeRS->addItem('money',$money);
		$this->treeRS->addItem('status',1);
		$this->treeRS->addItem('summary',$this->treeData->getItem('summary'));
		
		if($_status){
			//$this->transferAccountRecord();
			//记录到审核表中
			$this->recordAudit();
			//更新用户账户金额，并记录下来
			$uua=&Ua::instance(APP_UA);
			$uua->setID($this->treeData->getItem('uuid'));
			UcaMoney::consume($uua,$money,[
				'module'=>'refund','rootid'=>$id,
				'type'=>2,'payment'=>'系统帮助提现'
			]);
			unset($uua);	
		}
		//##########
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	protected function parseList()
	{
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		
		$tableData->doAppendFields('statusname,bankname');
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.assets.status');
		$tableTypes=VDCSDTML::getConfigTable('common.channel/account/data.remit.bank');
		$tableData->doBegin();
		while($tableData->isNext()){
			$id=$tableData->getItemValue('id');
			$status=$tableData->getItemValue('status');
			$bank=$tableData->getItemValue('bank');
			$statusname=$tableType->getTermsValue('type='.$status,'name');
			$tableData->setItemValue('statusname',$statusname);
			$bankname=$tableTypes->getTermsValue('type='.$bank,'name');
			$tableData->setItemValue('bankname',$bankname);
		}
	}

	//审核转账操作记录
	protected function recordAudit()
	{
		$rootid=$this->treeRS->getItemInt('id');
		$value=$this->treeRS->getItemInt('status');
		$summary=$this->treeRS->getItem('summary');
		
		$vTree=newTree();
		$vTree->addItem('module','refund');
		$vTree->addItem('rootid',$rootid);
		$vTree->addItem('value',$value);
		$vTree->addItem('summary',$summary);
		
		$_status=ManageRecordAudit::create($this->ma,$vTree);
		if($_status) $this->setSucceed();
	}
	
	protected function parseCheck()
	{
		$status=posti('status');
		$id=posti('id');
		$money=postn('money');
		$uuid=posti('uuid');
		$summary=posts('summary');
		$this->treeRS=newTree();
		$this->treeRS->addItem('uuid',$uuid);
		$this->treeRS->addItem('id',$id);
		$this->treeRS->addItem('money',$money);
		$this->treeRS->addItem('summary',$summary);
		$this->treeRS->addItem('status',$status);
		
		$this->treeData->addItem('status',$status);
		$this->treeData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlUpdate($this->TableName,'status,tim_up',$this->treeData,'id='.$id);
		$isexec=DB::exec($sql);
		if($isexec) $this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		if(!$isexec){
			$this->setStatus('failed');
			$this->setMessage('操作失败，请联系管理员');
			return;	
		}
		$this->recordAudit();
		if($status==1){	//如果审核通过
			//$this->transferAccountRecord();
			//更新用户账户金额，并记录下来
			$uua=&Ua::instance(APP_UA);
			$uua->setID($uuid);
			UcaMoney::consume($uua,$money,[
				'module'=>'refund','rootid'=>$id,
				'type'=>2,'payment'=>'提现'
			]);
			unset($uua);	
		}
		$this->setSucceed();
	}
	
	
	protected function doFormCheck()
	{
		$money=postn('money');
		$summary=posts('summary');
		$bank=posts('bank');
		$bankno=posts('bankno');
		$payee=posts('payee');
		$uuid=posti('uuid');
		$uua=Ua::instance(APP_UA);
		$uua->setID($uuid);
		$moneys=UcaMoney::getRest($uua);
		if(!$payee) $this->addError('提现人不能为空');
		if(!$money || $money<=0) $this->addError('金额不能为空 或 不符合规则');
		if($moneys<$money) $this->addError('账户余额不足');
		if(!$bank) $this->addError('退款银行名称不能为空');
		if(!$bankno) $this->addError('账号不能为空');		
		$this->addData('money',$money);
		$this->addData('summary',$summary);
		$this->addData('bank',$bank);
		$this->addData('bankno',$bankno);
		$this->addData('payee',$payee);
		
		
		$exist=DB::queryInt('select count(uid) from db_user where uid='.DB::q($uuid,1));
		if(!$exist) $this->addError('转账用户不存在');
		

		$this->treeData->addItem('uuid',$uuid);
		$this->treeData->addItem('status',1);
	
	}
}
?>