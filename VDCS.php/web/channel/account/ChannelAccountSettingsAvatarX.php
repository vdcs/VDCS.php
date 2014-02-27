<?
class ChannelAccountSettingsAvatarX extends ChannelAccountBaseX
{
	/*
	public function doParse()
	{
		$this->action='avatar';
		$this->parseAvatar();
	}
	*/
	
	protected function parseAvatar()
	{
		//$this->action='avatar';
		$this->sorts='avatar';
		$modeAction='save';
		$this->resAvatar();
		if($this->treeResource->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		if($this->mode==$modeAction){
			$treeSet=newTree();
			$treeSet->addItem($this->ua->TablePX.'avatar',$this->treeResource->getItem('small.url'));
			$this->ua->updateTree($treeSet);
			$treeSet->addItem($this->ua->TablePX.'face',$this->treeResource->getItem('small.url'));
			$treeSet->addItem($this->ua->TablePX.'photo',$this->treeResource->getItem('big.url'));
			$this->ua->updateTree($treeSet,null,'info');
			$this->addVar($modeAction.'.status','succeed');
		}
		$this->setSucceed();
	}
	
	protected function resAvatar()
	{
		$sorts=$this->sorts;
		$sqlQuery='channel='.DB::q($this->_chn_,1).' and sorts='.DB::q($sorts,1);
		$sqlQuery=DB::sqlAppend($sqlQuery,'uurc='.DB::q($this->ua->rc,1).' and uuid='.$this->ua->id);
		//debugx($sqlQuery);
		$this->tableResource=CommonUpload::getQueryTable($sqlQuery);
		//debugTable($this->tableResource);
		
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

		$typea=['small','middle','big'];
		foreach($typea as $type){
			$this->addVar('avatar.'.$type,appURL('root').'avatar/'.$this->ua->id.'_'.$type.'.pic');
		}
	}
	
}
?>