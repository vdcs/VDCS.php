<?php
trait PortalAssetsRemitRef
{
	
	protected function refLoad()
	{
		$this->uuid=queryi('uuid');
		if($this->uuid){
			$ua=Ua::instance(APP_UA);
			$this->uTree=newTree();
			$this->uTree=$ua->queryTree($this->uuid);
			$this->unames=UaExtendManage::toNames($this->uTree);
		}
	}
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
		$this->theme->doCacheFilterTree('view','cpo.treeView');
	}
	
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		//$this->no=DB::queryInt('select max('.$this->FieldID.') from '.$this->TableName)+10001;
		$this->loadPages();
		$this->pages->addFormVar('uuid',$this->uuid);
		$this->pages->addFormVar('unames',$this->unames);
		//$this->pages->addFormVar('no',$this->no);
		
		//##########
		$this->loadPagesForm();
		return true;
	}
	
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		//debugTree($this->treeRS);
		$settings=$this->treeRS->getItem('settings');
		$setary=VDCSData::deCode($settings,true);
		$this->treeRS->addItem('name',$setary['product']['name']);
		$renewround=$this->treeRS->getItem('renewround')+1;
		$this->treeRS->setItem('renewround',$renewround);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		
		UaExtendManage::appendTreeInfo($this->treeRS);
		//$this->treeRS->addItem('unames', $this->treeRS->getItem('ua.unames'));
		
		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();
		return true;
	}
	
	protected function refViewLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeView=DB::queryTree($sql);
		$this->doFilterTree($this->treeView);
		//debugTree($this->treeRS);
		/*
		$sqlTerm=DB::sqla('module='.DB::q('remit',1),'moduleid='.DB::q($id,1));
		$recordTree=newTree();
		$recordTree=ManageRecordModify::view($sqlTerm);
		if($recordTree->getCount()>0) $this->treeView->doAppend($recordTree,'record.');
		*/
		return true;
		
	}
	
	protected function doFilterTree(&$treeRs)
	{   		
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.remit.bank');
		$tableTypes=VDCSDTML::getConfigTable('common.channel/account/data.assets.status');
		$status=$treeRs->getItem('status');
		$bank=$treeRs->getItem('type');
		$statusname=$tableTypes->getTermsValue('type='.$status,'name');
		$treeRs->addItem('status.name',$statusname);
		$bankname=$tableType->getTermsValue('type='.$bank,'name');
		$treeRs->addItem('bank.name',$bankname);
		$uuid=$treeRs->getItem('uuid');
		$ua=Ua::instance(APP_UA);
		$table=$ua->TableName;
		unset($ua);
		$names=DB::queryValue('select names from '.$table.' where uid='.DB::q($uuid,1));
		$treeRs->addItem('unames',$names);
	}	
}
?>