<?
class PagePortal extends ManagePortalBaseX
{
	use PortalSystemModelServerRef;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	protected function doHandle()
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
		if(!$this->refListLoad()) return;
		$this->_var['paging.listnum']=999;
		$this->_var['paging.show']=false;
		$this->doListServe();
	}
	
	protected function doListFilter(&$tableData)
	{
		
	}
	
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->treeData->addItem('channel',$this->channeli);
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('tim_up',DCS::timer());
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return false;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		$this->treeData->addItem('tim_up',DCS::timer());
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
}
