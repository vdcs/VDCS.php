<?
class ChannelMcSettingsAuthCertifX extends ChannelAccountSettingsBase
{
	use WebServeRefXML;
	
	
	public function doParse()
	{
		$this->doAuthCert();
	}
	
	protected function doAuthCert()
	{
		$this->sorts='certif';
		$modeAction='apply';
		$this->resCertif();
		if($this->treeResource->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		// auth
		$this->mauth=new UcAuthManage();
		$this->mauth->setChannel($this->UCHANNEL);
		$this->mauth->setSorts($this->sorts);
		$this->mauth->setURC($this->ua->rc);
		$this->mauth->setUID($this->ua->id);
		$this->mauth->init();
		$this->mauth->loadData();
		if($this->mauth->isData()){
			$this->addVar($modeAction.'.is','yes');
			$this->addVar('auth.status',$this->mauth->getAuthStatus());
		}
		if($ctl->mode==$modeAction){
			$cert_name=post('cert_name');
			if(!$cert_name) $cert_name=query('cert_name');
			$cert_no=post('cert_no');
			if(!$cert_no) $cert_no=query('cert_no');
			$this->addVar('cert_name',$cert_name);
			$this->addVar('cert_no',$cert_no);
			
			$treeSet=newTree();
			$treeSet->addItem(UcAuthManage::FieldResID,$this->treeResource->getItemInt('origin.id'));
			$treeSet->addItem(UcAuthManage::FieldResValue,$this->treeResource->getItem('origin.url'));
			$treeSet->addItem('trans',0);
			$treeSet->addItem('trans_status',0);
			$treeSet->addItem('value1',$cert_name);
			$treeSet->addItem('value2',$cert_no);
			$this->mauth->doSave($treeSet);
			$this->addVar($modeAction.'.status','succeed');
		}
		$this->setStatus('succeed');
	}
	
	
	protected function resCertif()
	{
		$_sorts=$this->sorts;
		$sqlQuery='channel='.DB::q($this->UCHANNEL,1).' and sorts='.DB::q($_sorts,1);
		$sqlQuery=DB::sqlAppend($sqlQuery,'uurc='.DB::q($this->ua->rc,1).' and uuid='.$this->ua->id);
		//debugx($sqlQuery);
		$this->tableResource=CommonUpload::getQueryTable($sqlQuery);
		//debugTable($tableItem);
		
		$this->treeResource=newTree();
		$this->tableResource->doBegin();
		while($this->tableResource->isNext()){
			$_type=$this->tableResource->getItemValue('types');
			if(!$_type) $_type='origin';
			$_url=$this->tableResource->getItemValue('url');
			$_path=$this->tableResource->getItemValue('path');
			if(!$_url) $_url=appURL('upload').$_path;
			$this->treeResource->addItem($_type.'.id',$this->tableResource->getItemValue(CommonUpload::FieldID));
			$this->treeResource->addItem($_type.'.path',$_path);
			$this->treeResource->addItem($_type.'.url',$_url);
		}
		//debugTree($this->treeResource);
		
		$this->treeResource->doBegin();
		for($t=1;$t<=$this->treeResource->getCount();$t++){
			$this->addVar('res.'.$this->treeResource->getItemKey(),$this->treeResource->getItemValue());
			$this->treeResource->doMove();
		}
	}
	
}
?>