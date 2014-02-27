<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalAssetsRemitRef;
	
	
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
		$this->treeData->addItem('uurc',APP_UA);
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		$money=$this->treeData->getItemNum('money');
		$this->treeRS=newTree();
		$this->treeRS->addItem('uuid',$uuid);
		$this->treeRS->addItem('id',$id);
		$this->treeRS->addItem('money',$money);
		$this->treeRS->addItem('summary',$this->treeData->getItem('summary'));
		$this->treeRS->addItem('status',1);
		
		if($_status){
			//记录下审核操作
			$this->recordAudit();
			//更新用户账户金额，并记录下来
			$uua=&Ua::instance(APP_UA);
			$uua->setID($this->treeData->getItem('uuid'));
			UcaMoney::recharge($uua,$money,[
				'module'=>'remit','rootid'=>$id,
				'type'=>1,'payment'=>'系统汇款'
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
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$id=$tableData->getItemValue('id');
			$status=$tableData->getItemValue('status');
			$bank=$tableData->getItemValue('type');
			$statusname=$tableType->getTermsValue('type='.$status,'name');
			$tableData->setItemValue('statusname',$statusname);
			$bankname=$tableTypes->getTermsValue('type='.$bank,'name');
			$tableData->setItemValue('bankname',$bankname);
			$uid=$tableData->getItemValue('uuid');
		}
	}

	
	//审核汇款操作记录
	protected function recordAudit()
	{
		$rootid=$this->treeRS->getItemInt('id');
		$value=$this->treeRS->getItemInt('status');
		$summary=$this->treeRS->getItem('summary');
		
		$vTree=newTree();
		$vTree->addItem('module','remit');
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
			UcaMoney::recharge($uua,$money,[
				'module'=>'remit','rootid'=>$id,
				'type'=>1,'payment'=>'汇款'
			]);
			unset($uua);	
		}
		
		$this->setSucceed();
	}
	
	
	protected function doFormCheck()
	{
		$money=postn('money');
		$bank=posts('bank');
		$summary=posts('summary');
		//$email=posts('email');
		$uuid=posti('uuid');	
		if(!$money) $this->addError('金额不能为空 或 不符合规则');
		if(!$bank) $this->addError('汇款银行不能为空');
		//if(!utilCheck::isEmail($email)) $this->addError('用户邮箱不正确');
		
		$this->addData('money',$money);
		$this->addData('type',$bank);
		$this->addData('summary',$summary);
		//$this->addData('email',$email);
		
		$ua=Ua::instance(APP_UA);
		$exist=DB::queryInt('select count(uid) from '.$ua->TableName.' where uid='.DB::q($uuid,1));
		if(!$exist) $this->addError('汇款用户不存在');
		//$this->treeData->addItem('uurc','account');
		$this->treeData->addItem('uuid',$uuid);
		$this->treeData->addItem('status',1);
	
	}

	public function parseApply()
	{		
		$uuid=queryi('uuid');
		if(!ispost) return;
		$ua=Ua::instance(APP_UA);
		$ua->setID($uuid);

		$rechar=posts('rechar');
		$bankname=posts('bankname');
		$summary=posts('summary');

		$this->treeData=newTree();
		$this->treeData->addItem('uuid',$uuid);
		$this->treeData->addItem('uurc',APP_UA);
		$this->treeData->addItem('money',$rechar);
		$this->treeData->addItem('type',$bankname);
		$this->treeData->addItem('summary',$summary);
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		$this->treeData->addItem('status',1);

		if($rechar<=0)
		{
			$this->setStatus('failed');
			$this->setMessage('金额格式不正确！');
			return;
		}
		
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();

		if($_status==1){
			$this->treeRS=newTree();
			$this->treeRS->addItem('id',$id);
			$this->treeRS->addItem('summary',$summary);
			$this->treeRS->addItem('status',1);
		
			UcaMoney::recharge($ua,$rechar,[
				'module'=>'remit','rootid'=>$id,
				'type'=>1,'payment'=>'系统汇款'
			]);
			$this->recordAudit();
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
			return;
		}

	}
}
?>