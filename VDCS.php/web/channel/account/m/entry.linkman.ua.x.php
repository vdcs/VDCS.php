<?
class PagePortal extends ManagePortalBaseX
{

	public function parseAdd()
	{
		$uuid=queryi('uuid');
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		$this->treeData=newTree();
		$this->doFormData();	
		if($this->isRaiseError()) return;
		$ua=Ua::instance(APP_UA);
		$ua->setID($uuid);
		$_status=UcLinkman::add($ua,$this->treeData);
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
		}
	}
	public function parseEdit()
	{
		$id=queryi('id');
		$tree=UcLinkman::getTree($id);
		$uurc=$tree->getItem('uurc');
		$uuid=$tree->getItem('uuid');
		if($id<1){
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

	protected function parseDel()
	{
		$this->setStatus('parser');
		$id=queryi('id');
		$uuid=queryi('uuid');
		if($id<1) $this->addError('it is empty');		
		$this->addVar('id',$id);
		if($this->isRaiseError()) return;
		$ua=Ua::instance(APP_UA);
		$ua->setID($uuid);
		$_status=UcLinkman::del($ua,$id);
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
		}
	}
	
	protected function parseView()
	{
		$id=queryi('id');
		if(!$id){
			$this->setStatus('failed','id 不能为空');
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

	protected function parseSearchi()
	{
		$uid=queryi('uid');
		$listnum=queryi('listnum');
		$parmar=[];
		if(!$listnum) $listnum=10;
		$parmar['listnum']=$listnum;
		//$parmar['query']="uuid=$uid";
		$ua=Ua::instance(APP_UA);
		$ua->setID($uid);
		$tableList=UcLinkman::querier($ua,$this->p,$parmar);
		$this->doListFilter($tableList);
		$this->setTable($tableList);
		$this->addVarPaging();
		$this->setSucceed();
	}
	protected function doListFilter(&$tableData)
	{
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.linkman.type');
		$tableData->doAppendFields('type.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$type=$tableData->getItemValue('type');
			$tableData->setItemValue('type.name',$tableType->getTermsValue('type='.$type,'name'));
		}
	}

}