<?php
class ChannelAccountLinkmanX extends ChannelAccountBaseX
{

	public function parseAdd()
	{
		if(!isPost()){
			$this->setStatus('nopost');
			return;
		}
		
		$this->doFormData();
	
		if($this->isRaiseError()) return;
		
		$_status=UcLinkman::add($this->ua,$this->treeData);
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
		}
	}
	public function parseEdit()
	{	
		$id=queryi('id');
		$this->treeRS=UcLinkman::getTree($id);
		if($this->treeRS->getCount()<1){
			$this->setStatus('noexist');
			return;
		}
		if(!isPost()) return;
		$this->doFormData();
		if($this->isRaiseError()) return;
		$_status=UcLinkman::edit($this->ua,$id,$this->treeData);
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
		}
	}
	protected function doFormData()
	{
		$this->treeData->addItem('type',post('type',20));
		$this->treeData->addItem('idtype',post('idtype',20));
		$this->treeData->addItem('idcard',post('idcard',50));
		$this->treeData->addItem('gender',posti('gender'));
		$this->treeData->addItem('names',post('names',50));
		$this->treeData->addItem('mobile',post('mobile',20));
		$this->treeData->addItem('phone',post('phone',20));
		$this->treeData->addItem('email',post('email',100));
		$this->treeData->addItem('company',post('company',200));
		$this->treeData->addItem('address',post('address',250));

		if(len($this->treeData->getItem('names'))<2) $this->addError('请填写完整姓名');
		if($this->treeData->getItem('email') && !utilCheck::isEmail($this->treeData->getItem('email'))) $this->addError('邮件地址为空或不符合规则');
		if($this->treeData->getItem('mobile') && !utilCheck::isMobile($this->treeData->getItem('mobile'))) $this->addError('手机格式不正确');
	}
	
	public function parseDel()
	{
		$this->setStatus('parser');
		
		$id=queryi('id');
		if($id<1) $this->addError('it is empty');
		
		$this->addVar('id',$id);
		if($this->isRaiseError()) return;
		
		$_status=UcLinkman::del($this->ua,$id);
		
		switch($_status){
			case 1:		$this->setSucceed();break;
			case 5:		$this->setStatus('noexist');break;
			case 6:		$this->setStatus('nopermission');break;
			default:	$this->setStatus('failed');break;
		}
	}
	
	public function parseView()
	{
	
		$id=queryi('lid');
		if(!$id)
		{
			$this->setStatus('failed','亲爱的客户，您没有添加联系人哦！！');
			return;
		}
		$tree=UcLinkman::getTree($id);
		$this->addVar('info.names',$tree->getItem('names'));
		$this->addVar('info.email',$tree->getItem('email'));
		$this->addVar('info.mobile',$tree->getItem('mobile'));
		$this->addVar('info.company',$tree->getItem('company'));
		$this->addVar('info.address',$tree->getItem('address'));
		$this->setSucceed();	
	}

	public function parseLinkman()
	{
		$ua=$this->ua;
		$uid=$ua->getData('uid');
		$sqlterm='uuid='.$uid;
		$table=UcLinkman::query($sqlterm);
		$this->setTable($table);
		$this->setSucceed();		
	}

	public function parseList()
	{
		$uid=$this->ua->id;
		$params=array();
		$params['order']='id desc';
		//$params['query']='uuid='.$uid;
		$listnum=queryi('listnum');
		if(!$listnum) $listnum=10;
		$params['listnum']=$listnum;
		$this->tableList=UcLinkman::querier($this->ua,$this->p,$params);//,$sqlTerm='',$order='',$limit=0
		$this->doListFilter($this->tableList);
		$this->setTable($this->tableList);
		$this->addVarPaging();
		$this->setSucceed();
	}
	protected function doListFilter(&$tableData)
	{
		//UaExtend::appendInfo($tableData);
		$tableType=newTable();
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.linkman.type');
		$tableData->doAppendFields('type.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$type=$tableData->getItemValue('type');
			$tableData->setItemValue('type.name',$tableType->getTermsValue('type='.$type,'name'));
		}
	}

}
?>