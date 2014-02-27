<?
trait PortalUaRefCorp
{
	use PortalUaRefBase;
	
	public function initVar()
	{
		$this->UURC='corp';
		$this->MinID=UaCorp::MinID;
	}
	
	
	//####################
	protected function doAdd()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=DB::queryInt('select max('.$this->FieldID.') from '.$this->TableName)+1;
		if($this->id<$this->MinID) $this->id=$this->MinID;
		$this->no=$this->id;
		$this->loadPages();
		$this->pages->setFormModule($this->_m_);
		$this->pages->addFormVar('id',$this->id);
		$this->pages->addFormVar('no',$this->no);
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
		}
		//##########
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->id=$this->treeData->getItemInt($this->FieldID);
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				$this->treeData->addItem($this->FieldID,$this->id);
				
				$this->doDataFilter($this->action);
				//##########
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				DB::executeInsert($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData);
				//##########
				//########## pivotal
				if($this->mpivotal->is()){
					$this->mpivotal->setUID($this->id);
					$this->mpivotal->doFormSave($this->treeData);
				}
				//##########
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doEdit()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=$this->id;
		$sqlQuery=$this->FieldID.'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$isInfo=false;
		$sql=DB::sqlSelect($this->getConfig('info:table.name'),'','*',$sqlQuery,'',1);
		$treeInfo=DB::queryTree($sql);
		if($treeInfo->getCount()>0){
			$this->treeRS->doAppendTree($treeInfo);
			$isInfo=true;
		}
		unsetr($treeInfo);
		//$this->id=$this->treeRS->getItemInt($this->FieldID);
		$this->loadPages();
		$this->pages->setFormModule($this->_m_);
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->treeRS->doAppendTree($this->mpivotal->getFormDataTree());
		}
		//##########
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				$this->treeData->addItem($this->FieldID,$this->id);
				
				$this->doDataFilter($this->action);
				//##########
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
				if($isInfo) DB::executeUpdate($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
				else DB::executeInsert($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData,$this->treeRS);
				//##########
				//########## pivotal
				if($this->mpivotal->is()){
					$this->mpivotal->setUID($this->id);
					$this->mpivotal->doFormSave($this->treeData);
				}
				//##########
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	  	$this->doUpdateTotal($this->UURC);
	}
	protected function doListFilter(&$tableData)
	{
		$relateid=$this->FieldID;
		$relateids=$tableData->getValues($relateid);
		if(len($relateids)>0){
			$_fields=$this->getConfig('info:list.table.fields');
			if(len($_fields)>0) $tableData=CommonExtend::toExtendTable($tableData,$relateid,'',$this->getConfig('info:table.name'),$relateid.','.$_fields,$relateid.' in ('.$relateids.')');
		}
		return;
		$tableData->doBegin();
		while($tableData->isNext()){
			
			debugx($tableData->getItemValue('uuid'));
		}
	}
	
}
?>