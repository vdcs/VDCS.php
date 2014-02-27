<?
class ChannelMmSettingsPhotoLogoX extends ChannelMmSettingsBase
{
	use WebServeRefXML;
	
	
	public function doParse()
	{
		$this->doAuthCert();
	}
	
	protected function doAuthCert()
	{
		$this->sorts='photo';
		$this->types='logo';
		$modeAction='save';
		$this->resCert();
		if($this->treeResource->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		if(len($this->ua->getData('logo'))>0){
			$this->addVar($modeAction.'.is','yes');
		}
		if($this->mode==$modeAction){
			$treeSet=newTree();
			$treeSet->addItem($this->ua->TablePX.'logo',$this->treeResource->getItem('origin.url'));
			$this->ua->updateTree($treeSet);
			$this->ua->updateTree($treeSet,null,'info');
			$this->addVar($modeAction.'.status','succeed');
		}
		$this->setStatus('succeed');
	}
	
	
	protected function resCert()
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
			$_type='';	//$this->tableResource->getItemValue('types');
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