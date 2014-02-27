<?
class ChannelAccountLinkmanDomainX extends ChannelAccountBaseX{
	
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
		//$_status=UcLinkmanDomain::add($this->ua,$this->treeData);
		$oData=$this->treeData->getFilterTree('owner_');
		$tData=$this->treeData->getFilterTree('technical_');
		$mData=$this->treeData->getFilterTree('admin_');
		$pData=$this->treeData->getFilterTree('billing_');
		$_status=UcLinkmanDomain::add($this->ua,$oData);
		$_status=UcLinkmanDomain::add($this->ua,$tData);
		$_status=UcLinkmanDomain::add($this->ua,$mData);
		$_status=UcLinkmanDomain::add($this->ua,$pData);
		
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
		}
		
	}
	
	public function parseEdit()
	{
		$this->treeData=newTree();
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		
		$this->doFormData();
		
		if($this->isRaiseError()) return;
		
		$oData=$this->treeData->getFilterTree('owner_');
		$tData=$this->treeData->getFilterTree('technical_');
		$mData=$this->treeData->getFilterTree('admin_');
		$pData=$this->treeData->getFilterTree('billing_');
		$_status=UcLinkmanDomain::edit($this->ua,$oData->getItem('id'),$oData);
		$_status=UcLinkmanDomain::edit($this->ua,$tData->getItem('id'),$tData);
		$_status=UcLinkmanDomain::edit($this->ua,$mData->getItem('id'),$mData);
		$_status=UcLinkmanDomain::edit($this->ua,$pData->getItem('id'),$pData);
		
		
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
		$this->setStatus('parser');
		
		$id=queryi('id');
		if($id<1) $this->addError('it is empty');
		
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
	
	public function parseList()
	{
		$tableData=newTable();
		$sqlTerm='';
		$order='';
		$limit=0;
		$sqlTerm=queryx('sqlTerm');
		$order=queryx('order');
		$limit=queryi('limit');
		$tableData=UcLinkmanDomain::query($this->ua,$sqlTerm,'id desc',$limit);
		$this->setTable($tableData);
		$this->setSucceed();
	}
	
	//对数据进行相关处理
	protected function doFormData()
	{
		$arr=$_POST;
		foreach($arr as $k=>$v){
			$this->addData($k,posts($k));
		}
		
	}
}
?>