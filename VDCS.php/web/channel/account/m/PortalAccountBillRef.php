<?php
trait PortalAccountBillRef
{
	protected $DefaultPrepageNum=10000;
	
	protected function refLoad()
	{
		$this->pid=queryi('pid');
		$this->addDTML('x.params','pid='.$this->pid);
	}

	
	public function refThemeCache()
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('billdata','cpo.billData');
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
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();
		return true;		
	}
	
	protected function refViewLoad()
	{
		$id=queryi('id');
		$this->treeView=newTree();
		$this->treeView=UcaBill::getTree($id);
		if($this->treeView->getCount()<1) return false;
		$this->billData=UcaBill::queryDataByRootid($id,'','id desc');
	}
	
}
?>