<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalRefBase,PortalRefAction;
	use PortalManagerRefPopedom;
	
	
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

		$this->treeData->addItem($this->TablePX.'password',utilCoder::toMD5($this->treeData->getItem($this->TablePX.'password')));
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->doFormCheck($this->action);
		if($this->isRaiseError()) return;

		if($this->treeData->getItem($this->TablePX.'password')!=$this->treeRS->getItem($this->TablePX.'password')) $this->treeData->addItem($this->TablePX.'password',utilCoder::toMD5($this->treeData->getItem($this->TablePX.'password')));
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function doFormCheck($action='')
	{
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_no=$this->treeData->getItem($this->TablePX.'no');
			if(len($_no)>0){
				if(!utilCheck::isName($_no)){ $this->addError($this->getLang('error.norule.no')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'no=\''.$_no.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.no'));
				}
			}
			$_name=$this->treeData->getItem($this->TablePX.'name');
			if(len($_name)>0){
				if(!utilCheck::isName($_name)){ $this->addError($this->getLang('error.norule.name')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'name=\''.$_name.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.name'));
				}
			}
			$_password=$this->treeData->getItem($this->TablePX.'password');
			if(len($_password)>0){
				if(!utilCheck::isPassword($_password)) $this->addError($this->getLang('error.norule.password'));
			}
		}
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_username=$this->treeData->getItem($this->TablePX.'username');
			if(len($_username)>0){
				if(!utilCheck::isName($_username)){ $this->addError($this->getLang('error.norule.username')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'username=\''.$_username.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.username'));
				}
			}
		}
	}
	
	//####################
	protected function parsePopedom()
	{
		if(!$this->refPopedomLoad()) return;
		$this->refPopedomParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseList()
	{
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		/*
		$tableData->doBegin();
		while($tableData->isNext()){
			
			debugx($tableData->getItemValue('id'));
		}
		*/
	}
	
	//####################
	protected function handleExtend($handle,$ids)
	{
		switch($handle){
			case 'delete':
				//if($this->attrm->is()) $this->attrm->doDataRemove($ids);
				break;
		}
	}
	
}
?>