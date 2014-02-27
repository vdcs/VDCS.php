<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalForumRef;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		
	
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		$this->treeData->addItem('tim_up',DCS::timer());//更新时间
		//$this->treeData->addItem('userid',ManageExtend::toUserID($this->treeData->getItem('username')));
		//if($this->isFieldMode('pic')) $this->treeData->addItem($this->TablePX.'ispic',len($this->treeData->getItem($this->TablePX.'pic'))>0?1:0);
		//##########
		//$this->treeData->addItem(VDCSDB::SQL_TERM_KEY,$sqlQuery);
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		
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
			$this->doAppendQuery(ManageExtend::toUserSearchQuery('username',true));
		}
		
		$this->doListServe();
	}
	
	protected function doListFilter(&$tableData)
	{
		$opt=[];
		$opt['relateid']='uid';
		UaExtendManage::appendInfo($tableData,$opt);
	}
}
?>