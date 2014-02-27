<?
class PagePortal extends ManagePortalBaseX
{
	use PortalContentDataRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	//####################
	protected function parseAdd()
	{
		global $cfg;
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		$this->treeData->addItem('rootid',$this->rootid);
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		
		
		$tableName=$cfg->vp('table.name');
		$pre=$cfg->vp('table.px');
		$sql='update '.$tableName.' set '.$pre.'total_data='.$pre.'total_data+1 where '.$pre.'id='.$this->rootid;
		DB::exec($sql);
		
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
		
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseList()
	{
		global $cfg;
		$this->doAppendQuery('rootid='.DB::q($this->rootid));
		$this->doListServe();
		
		$num=$this->tableList->getRow();
		$tableName=$cfg->vp('table.name');
		$pre=$cfg->vp('table.px');
		$sql='update '.$tableName.' set '.$pre.'total_data='.DB::q($num,1).' where '.$pre.'id='.$this->rootid;
		DB::exec($sql);
	}
	
	//####################
	protected function handleExtend($handle,$ids)
	{
		switch($handle){
			case 'delete':
				if($this->attrm->is()) $this->attrm->doDataRemove($ids);
				break;
		}
	}
	
}
