<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalAssetsTransactionRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	

	public function parseSearchi()
	{
		$uuid=queryi('uuid');
		$listnum=queryi('listnum');
		$ua=Ua::instance('account');
		$ua->setID($uuid);
		$parmar=[];
		if(!$listnum) $listnum=10;
		$parmar['listnum']=$listnum;
		$parmar['query']="uuid=$uuid";
		$parmar['order']='id desc';
		$table=UcaTransaction::querier($ua,$this->p,$parmar);
		$this->addVarPaging();
		$this->setTable($table);
		$this->setStatus('succeed');
	}

	protected function parseList()
	{
		$this->doHandle();
		if($this->s->isQuery()){}
		$modulename=$this->tablemodule->getTermsValue('value='.$this->__module,'value');
		$modulenames=$this->tablemodule->getValues('value');
		$modulenames=str_replace(',','","',$modulenames);
		if(len($modulenames)) $modulenames='"'.$modulenames.'"';
		if($this->__module){
			if(!$modulename) $this->doAppendQuery('module not in('.$modulenames.')');
			else $this->doAppendQuery('module='.DB::q($this->__module,1));
		}
		$this->doListServe();

	}

	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		
		$tableData->doAppendFields('money_smb,module.name');		
		$tableType=VDCSDTML::getConfigTable('common.channel/account/data.transaction.module');
		//debugTable($tableType);
		$tableData->doBegin();
		while($tableData->isNext()){
			$type=$tableData->getItemValueInt('type');
			$money_smb=$tableData->getItemValue('money');
			$module=$tableData->getItemValue('module');
			if($type==2){
				$money_smb='-'.$money_smb;
			}
			$tableData->setItemValue('money_smb',$money_smb);
			
			$modulename=$tableType->getTermsValue('value='.$module,'name');
			if(!$modulename) $modulename='消费';
			$tableData->setItemValue('module.name',$modulename);
		}
	}
}

?>