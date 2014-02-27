<?php
class ChannelAccountLinkmanX extends ChannelAccountBaseX{
	//分页
	public function parseList()
	{
		$this->initPaging();//初始化分页
		$uuid=$this->ua->id;
		$this->tableList=UcLinkman::queryData('uuid='.$uuid,'id desc',$this->p);//,$sqlTerm='',$order='',$limit=0
		//$this->tableList=$this->doFilterOrderData($this->tableList);
		$this->setTable($this->tableList);
		
		if(!$total) $total=$this->tableList->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();//在treeVar中增加相关的分页信息
		
		
		$this->setSucceed();
	}
	
	protected function initPaging()
	{
		$listnum=queryi('listnum');//每页显示的数目
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
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
	
	public function parseEdit()
	{
		$this->setStatus('ready');
		$id=queryi('id');
		if($id<1){
			$this->setStatus('noexist');
			return;
		}
		$this->addVar('id',$id);
		$treeRS=null;
		if(!$ctl->e->isCheck()){
			$_status=UcLinkman::isCheck($this->ua,$id,$treeRS);//检测编辑的人是否存在,若存在则获取相关记录
			if($_status!=1){
				switch($_status){
					case 5:		$this->setStatus('noexist');break;
					case 6:		$this->setStatus('nopermission');break;
					default:	$this->setStatus('failed');break;
				}
				return;
			}
		}
		//$fieldsAr=toSplit('id,uuid,uurc,name,email,mobile');
		$fieldsAr=toSplit($treeRS->getFields());
		foreach($fieldsAr as $field){
			$this->addVar($field,$treeRS->getItem($field));
		}
		$this->setStatus('succeed');
		if(!isPost()) return;
		$this->setStatus('parser');
		$this->treeData=newTree();
		
		$this->doFormData();
		
		if($this->isRaiseError()) return;
		
		$_status=UcLinkman::edit($this->ua,$id,$this->treeData);
		
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
		}
		unset($treeRS);
	}
	
	//删除联系人
	public function parseDel()
	{
		global $ctl;
		$this->setStatus('parser');
		
		$id=queryi('id');
		if($id<1) $ctl->e->addItem('it is empty');
		
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
	
	
	//对数据进行相关处理
	protected function doFormData()
	{
		$name=posts('name');
		$email=post('email');
		$mobile=post('mobile');

		if(len($name)<3) $this->addError('请填写完整姓名');
		if(!utilCheck::isEmail($email)) $this->addError('邮件地址为空或不符合规则');
		if(!utilCheck::isMobile($mobile)) $this->addError('手机格式不正确');
		
		$this->treeData->addItem('name',$name);
		$this->treeData->addItem('email',$email);
		$this->treeData->addItem('mobile',$mobile);
	}	
}
?>