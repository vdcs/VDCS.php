<?
class PortalContentX extends ManagePortalBaseX
{
	use PortalContentRef;
	
	
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
		if($this->isRaiseError()) return;
		
		if($this->isFieldMode('prepage')){
			$prepageNum=$this->treeData->getItemInt($this->TablePX.'prepage_num');
			if($this->treeData->getItemInt($this->TablePX.'prepage')==2 && $prepageNum>0){
				$this->treeData->setItem($this->TablePX.'prepage',$prepageNum);
			}
		}
		//$this->treeData->addItem('uuid',UaExtend::queryID($this->treeData->getItem('uname')));
		//if($this->isFieldMode('pic')) $this->treeData->addItem($this->TablePX.'ispic',len($this->treeData->getItem($this->TablePX.'pic'))>0?1:0);
		//##########
		//$this->treeData->addItem('sp_poll_agree',0);
		//$this->treeData->addItem('sp_poll_oppose',0);
		//$this->treeData->addItem('sp_user_define','');
		//##########
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		//########## attrtype
		$this->attrm->setRootID($id);
		if($this->attrm->is()){
			$this->attrm->doFormSave($this->treeData);
		}
		//##########
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
		
		if($this->isFieldMode('prepage')){
			$prepageNum=$this->treeData->getItemInt($this->TablePX.'prepage_num');
			if($this->treeData->getItemInt($this->TablePX.'prepage')==2 && $prepageNum>0){
				$this->treeData->setItem($this->TablePX.'prepage',$prepageNum);
			}
		}
		//$this->treeData->addItem('uuid',UaExtend::queryID($this->treeData->getItem('uname')));
		//if($this->isFieldMode('pic')) $this->treeData->addItem($this->TablePX.'ispic',len($this->treeData->getItem($this->TablePX.'pic'))>0?1:0);
		//##########
		//$this->treeData->addItem(DB_SQL_TERM_KEY,$sqlQuery);
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		//########## attrtype
		if($this->attrm->is()){
			$this->attrm->doFormSave($this->treeData);
		}
		//##########
		//$this->doActionParse();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseList()
	{
		$this->doListServe();
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
