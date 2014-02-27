<?
class PagePortal extends ManagePortalBaseX
{
	use PortalEmRoleRef;
	
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
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		$this->refPopedomParse();
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		
		$this->id=DB::insertid();
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
		
		$this->refPopedomParse();
		$this->treeData->addItem('tim_up',DCS::timer());//更新时间
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
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
		if($this->deptid) $this->doAppendQuery('staffid in (select staffid from dbe_employee where deptid='.$this->deptid.')');
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		
	}
	
	//权限
	protected function parsePopedom()
	{
		if(!$this->refPopedomLoad()) return;
		$this->refPopedomParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
}
?>