<?
class PagePortal extends ManagePortalBaseX
{
	use PortalEmStaffRef;
	
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
		$this->doFormCheck($this->action);
		
		$isman=$this->treeData->getItemInt('isman');
		if($isman){
			$names=$this->treeData->getItem('names');
			if(!$names) $this->addError('简称不能为空');
			//$manpass=$this->treeData->getItem('manpass');
			//if(!$manpass) $this->addError('请填写管理员密码');
			
			$sql_exists='name='.DB::q($names,1);
			$tree=newTree();
			$tree=StaffManage::getTree($sql_exists);
			$exists=$tree->getCount();
			if($exists) $this->addError('简称已存在，请重新填写');
		}
		if($this->isRaiseError()) return;
		
		
		
		//必填简称
		//员工级别
		
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$this->id=DB::insertid();
		$this->treeData->addItem('staffid',$this->id);
		$_status=DB::execInsertx($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData,null,true);
		
		$vtree=newTree();
		$vtree=$this->treeData->getFilterTree('emp_');
		/*
		dcsLog('fs',$vtree->getFields());
		dcsLog('vs',$vtree->getValues());
		*/
		$this->treeData->doAppendTree($vtree);
		$this->treeData->addItem('empid',$this->id);
		if($_status) EcEmployee::add($this->treeData);
		
		//关联管理员
		if($_status && $isman){
			$manTree=newTree();
			$manTree->addItem('uuid',$this->id);
			$manTree->addItem('uurc','staff');
			$manTree->addItem('name',$names);
			$manTree->addItem('email',$this->treeData->getItem('email'));
			$manpass=$this->treeData->getItem('manpass');
			if(!$manpass) $manpass='test';
			$manTree->addItem('password',utilCoder::toMD5($manpass));
			$manTree->addItem('roles',$this->treeData->getItem('roles'));
			$_status=StaffManage::add($manTree);
		}
		
		if(!$_status){
			$this->setStatus('failed');
			return;
		}
		
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->doFormCheck($this->action);
		if($this->isRaiseError()) return;
		
		$maid=$this->treeData->getItemInt('maid');
		$isman=$this->treeData->getItemInt('isman');
		if($isman){
			$names=$this->treeData->getItem('names');
			if(!$names) $this->addError('简称不能为空');
			
			$sql_exists='name='.DB::q($names,1).' and id!='.$maid;
			$tree=newTree();
			$tree=StaffManage::getTree($sql_exists);
			$exists=$tree->getCount();
			if($exists) $this->addError('简称已存在，请重新填写');
		}
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('staffid',$this->id);
		$this->treeData->addItem('tim_up',DCS::timer());//更新时间
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		if($this->isInfo) DB::execUpdatex($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		else DB::execInsertx($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData,$this->treeRS,true);
		if($this->isEmployee){
			$vtree=newTree();
			$vtree=$this->treeData->getFilterTree('emp_');
			$this->treeData->doAppendTree($vtree);
			$this->treeData->addItem('empid',$this->id);
			EcEmployee::edit($this->id,$this->treeData);
		}else{
			$vtree=newTree();
			$vtree=$this->treeData->getFilterTree('emp_');
			$this->treeData->doAppendTree($vtree);
			$this->treeData->addItem('empid',$this->id);
			EcEmployee::add($this->treeData);	
		}
		
		//关联管理员
		$manpass=$this->treeData->getItem('manpass');
		if($maid && $isman){ //一直是
			$manTree=newTree();
			$manTree->addItem('uuid',$this->id);
			$manTree->addItem('uurc','staff');
			$manTree->addItem('name',$names);
			$manTree->addItem('email',$this->treeData->getItem('email'));
			$manTree->addItem('roles',$this->treeData->getItem('roles'));
			if($manpass){
				$manTree->addItem('password',utilCoder::toMD5($manpass));
				$fields='uuid,uurc,name,email,password,roles';
			}else{
				$fields='uuid,uurc,name,email,roles';
			}
			$_status=StaffManage::edit($fields,$manTree,'id='.$maid);
		}
		
		if(!$maid && $isman){//现在是
			$manTree=newTree();
			$manTree->addItem('uuid',$this->id);
			$manTree->addItem('uurc','staff');
			$manTree->addItem('name',$names);
			$manTree->addItem('email',$this->treeData->getItem('email'));
			if(!$manpass) $manpass='test';
			$manTree->addItem('password',utilCoder::toMD5($manpass));
			$manTree->addItem('roles',$this->treeData->getItem('roles'));
			$_status=StaffManage::add($manTree);
		}
		
		//现在不是
		if($maid && !$isman) StaffManage::del($maid);
		
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
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
		$this->setSearchMode(0);
		if($this->s->isQuery()){
			//$this->doAppendQuery(ManageExtend::toUserSearchQuery('username',true));
		}
		//if($this->deptid) $this->doAppendQuery('staffid in (select staffid from dbe_employee where deptid='.$this->deptid.')');
		if($this->deptid) $this->doAppendQuery('staffid in (select staffid from dbe_employee where deptid='.$this->deptid.')');
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		$relateid=$this->getConfig('table.field.relateid');
		if(!$relateid) $relateid=$this->FieldID;
		$relateids=$tableData->getValues($relateid);
		if($relateids){
			$_tablename=$this->getConfig('info:table.name');
			$_fieldid=$this->getConfig('info:table.field.id');
			$_fields=$this->getConfig('info:list.table.fields');
			if(!$_fieldid) $_fieldid=$relateid;
			if($_tablename && $_fields) $tableData=CommonExtend::toExtendTable($tableData,$relateid.'='.$_fieldid,'',$_tablename,$_fieldid.','.$_fields,$_fieldid.' in ('.$relateids.')');
		}
		$tableMan=newTable();
		$tableMan=StaffManage::query();
		
		
		$staffids=$tableData->getValues('staffid');
		$staffExt=newTable();
		if($staffids) $staffExt=DB::queryTable('select e.staffid,e.deptid,e.grade,e.type,d.name from dbe_employee e,dbe_department d where e.staffid in('.$staffids.') and e.deptid=d.deptid');
		
		
		$tableData->doAppendFields('manid,dept.name,type');
		$tableData->doBegin();
		while($tableData->isNext()){
			$staffid=$tableData->getItemValueInt('staffid');
			$manid=$tableMan->getTermsValue('uuid='.$staffid,'id');
			$tableData->setItemValue('manid',$manid);
			$grade=$staffExt->getTermsValue('staffid='.$staffid,'grade');
			if(!$grade) $grade=1;
			$tableData->setItemValue('grade',$grade);
			$tableData->setItemValue('type',$staffExt->getTermsValue('staffid='.$staffid,'type'));
			$tableData->setItemValue('dept.name',$staffExt->getTermsValue('staffid='.$staffid,'name'));
		}
	}
	
	//权限
	protected function parsePopedom()
	{
		if(!$this->refPopedomLoad()) return;
		$this->refPopedomParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function parseViewi()
	{
		$uuid=queryi('uuid');
		$staffid=DB::queryInt('select amid from db_account where uid='.$uuid);
		$staffTree=newTree();
		$staffTree=EcStaff::getTree($staffid);
		if($staffTree->getCount()<1){
			$this->setStatus('failed');
			$this->setMessage('用户不存在');
			return;
		}
		$this->addVarTree($staffTree,'staffinfo.');
		$this->setSucceed();
	}
	
}
?>