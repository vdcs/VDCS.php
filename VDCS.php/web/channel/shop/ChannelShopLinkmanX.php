<?
class ChannelShopLinkmanX extends ChannelShopBaseX
{
	
	//分页
	public function parseList()
	{
		$uuid=$this->ua->id;
		$params=array();
		$params['order']='type desc,id desc';
		$params['query']='uuid='.$uuid;
		$listnum=queryi('listnum');
		if(!$listnum) $listnum=10;
		$params['listnum']=$listnum;
		$this->tableList=UcLinkman::querier($this->ua,$this->p,$params);//,$sqlTerm='',$order='',$limit=0
		//$this->tableList=$this->doFilterOrderData($this->tableList);
		$this->setTable($this->tableList);
		$this->addVarPaging();//在treeVar中增加相关的分页信息		
		$this->setSucceed();
	}
	
	//添加用户
	public function parseAdd()
	{
		$this->treeData=newTree();
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		
		$this->doFormData();
	
		if($this->isRaiseError()) return;
		
		$_status=UcLinkman::add($this->ua,$this->treeData);
		$_status=1;
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
		}
		
	}
	
	//删除联系人
	public function parseDel()
	{
		$this->setStatus('parser');
		
		$id=queryi('id');
		if($id<1) $this->e->addItem('it is empty');
		
		$this->addVar('id',$id);
		if($this->isRaiseError()) return;
		
		$_status=UcLinkman::delete($this->ua,$id);
		
		switch($_status){
			case 1:		$this->setSucceed();;break;
			case 5:		$this->setStatus('noexist');break;
			case 6:		$this->setStatus('nopermission');break;
			default:	$this->setStatus('failed');break;
		}
	}
	
	/**编辑联系人**/
	public function parseEdit()
	{
		$id=posti('id');
		$this->treeData=newTree();
		$this->treeData->addItem('id',$id);
		$this->treeData->addItem('uurc',$this->ua->rc);
		$this->treeData->addItem('uuid',$this->ua->id);
		$this->doFormData();
		$_status=UcLinkman::edit($this->ua,$id,$this->treeData);
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
			return;
		}
	}
	
	//对数据进行相关处理
	protected function doFormData()
	{
		$name=posts('name');
		$mobile=posts('mobile');
		$postcode=posts('postcode');
		$address=posts('address');
		
		if(len($name)<3) $this->addError('请填写完整姓名');
		if(!utilCheck::isMobile($mobile)) $this->addError('手机格式不正确');
		if(len($address)<1) $this->addError('请填写完整地址');
		
		$this->treeData->addItem('name',$name);
		$this->treeData->addItem('mobile',$mobile);
		$this->treeData->addItem('address',$address);
		$this->treeData->addItem('postcode',$postcode);
	}
	
	//设为默认
	protected function parseDefault()
	{
		$id=queryi('id');
		if(!$id){
			$this->setStatus('error');
			return;
		}
		$_status=UcLinkman::setDefault($id,$this->ua);
		if($_status){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
			return;
		}
	}
	
	public function parseView()
	{
		$lid=queryi('lid');
		if(!$lid){
			$this->setStatus('failed');
			$this->setMessage('lid 不能为空');
			return;
		}
		$tree=UcLinkman::getTree($lid);
		$this->addVar('info.name',$tree->getItem('name'));
		$this->addVar('info.email',$tree->getItem('email'));
		$this->addVar('info.mobile',$tree->getItem('mobile'));
		$this->addVar('info.company',$tree->getItem('company'));
		$this->addVar('info.address',$tree->getItem('address'));
		$this->setSucceed();	
	}
	
	public function parseContect()
	{
		$ua=$this->ua;
		$uid=$ua->getData('uid');
		$sqlterm="uuid=$uid";
		$table=UcLinkman::query($sqlterm);
		$this->setTable($table);
		$this->setSucceed();		
	}
}
?>