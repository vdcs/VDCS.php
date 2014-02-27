<?
class PortalSystemModelSort extends PortalSystemBase
{
	public function doLoad()
	{
		global $dcs,$ctl,$mpa;
		
		$mpa=new ManageAppModel();
		$mpa->setModel('sort');
		$mpa->setUnite(false);
		$mpa->setClass(true);
		$ctl->classid=0;
	}
	
	public function doParse()
	{
		global $dcs,$cfg,$ctl,$mpa;
		$this->channelValue=$ctl->subchannel;
		$this->classid=queryID('classid');
		$this->doAppendURL('classid='.$this->classid);
		$mpa->classid=$this->classid;
		$mpa->doLoadFrame($this->channelValue);
		
		if(!$mpa->isClass()){
			$this->doMessages('!','!model.no.class','');
			return;
		}
		
		$this->channelTableName=$cfg->getChannelValue($this->channelValue,'configure','pre.table.name');
		if(!$this->channelValue || !$this->channelTableName){
			$this->doMessages('!','!model.no.subchannel','');
			return;
		}
		
		$mpFrame->addVar('menu.links'a->getMenuLinks());
		$this->DefineID=$this->getConfig('table.field.defineid');
		$this->sqlQuery=$this->toSQL('query');
		$this->sqlQuerys=$this->sqlQuery?$this->sqlQuery.' and ':'';
		$this->sqlRelate=$this->toSQL('relate');
		$this->sqlRelates=$this->sqlRelate?$this->sqlRelate.' and ':'';
		$this->levelid=0;$this->rootid=0;$this->orderid=0;$this->fatherid=0;
		switch($this->action){
			case 'add':
				$this->theme->setModule('form');
				$this->doAdd();
				break;
			case 'edit':
				$this->theme->setModule('form');
				$this->doEdit();
				break;
			case 'del':
				$this->theme->setModule('form');
				$this->doDel();
				break;
			case 'order':
				$this->doOrder();
				break;
			default:
				$this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
		}
	}
	
	
	//####################
	//####################
	public function toSQL($type)
	{
		$re='channel=\''.$this->channelValue.'\'';
		if($type=='relate'){
			if(inp($this->ChannelUnite,$this->channelValue)>0) $re='channel in (\''.r($this->ChannelUnite,',','\',\'').'\')';
		}
		else{
			$re=DB::sqlAppend($re,'classid='.$this->classid.'');
		}
		return $re;
	}
	
	protected function doUpdateCache()
	{
		global $dcs,$cfg,$ctl;
		ModelSortExtend::doCacheUpdate($this->channelValue,$this->classid);
	}
	
	protected function doDataDispose($action)
	{
		global $dcs,$ctl;
		switch($action){
			case 'add':
				$this->treeData->addItem('channel',$this->channelValue);
				$this->treeData->addItem('classid',$this->classid);
				$this->treeData->addItem('levelid',$this->levelid);
				$this->treeData->addItem('rootid',$this->rootid);
				$this->treeData->addItem('orderid',$this->orderid);
				$this->treeData->addItem('configure','');
				break;
			case 'edit':
				
				break;
		}
	}
	
	
	//####################
	//####################
	protected function doAdd()
	{
		global $dcs,$ctl;
		$this->sortid=0;
		$this->levelid=1;
		$maxid=DB::toQueryInt($this->TableName,'max',$this->DefineID,$this->sqlRelate)+1;
		$this->fatherid=queryi('fatherid');
		if($this->fatherid<1) $this->fatherid=queryi('id');
		if($this->fatherid>0){
			$this->sortid=DB::toQueryInt($this->TableName,'max',$this->DefineID,$this->sqlRelates.' fatherid='.$this->fatherid);
			if($this->sortid>0) $this->sortid++;
		}
		//debugx($this->sortid);
		if($this->sortid<1 && $this->fatherid>0){
			$this->sortid=$this->fatherid*10+1;
			if(DB::toQueryInt($this->TableName,'count',$this->DefineID,$this->sqlRelates.' sortid='.$this->sortid)>0){
				$this->sortid=0;
			}
		}
		//debugx($this->sortid);
		if($this->sortid<1 && $this->classid>0){
			$this->sortid=$this->classid*10+1;
			if(DB::toQueryInt($this->TableName,'count',$this->DefineID,$this->sqlRelates.' sortid='.$this->sortid)>0){
				$this->sortid=0;
			}
		}
		//debugx($this->sortid);
		if($this->sortid<1) $this->sortid=$maxid;
		//debugx($this->sortid);
		$this->loadPages();
		$this->pages->setFormModule($this->module);
		$this->pages->addFormPre('channel',$this->channelValue);
		$this->pages->addFormPre('classid',$this->classid);
		$this->pages->addFormVar('sortid',$this->sortid);
		$this->pages->addFormVar('orderid',$this->orderid);
		$this->pages->addFormVar('fatherid',$this->fatherid);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->fatherid=$this->treeData->getItemInt('fatherid');
			$this->sortid=$this->treeData->getItemInt('sortid');
			$checknext=!$this->isErrorCheck();
			if($checknext){
				if($this->sortid<1 || DB::toQueryInt($this->TableName,'count','*',$this->sqlRelates.' sortid='.$this->sortid)>0) $this->addError($this->getLang('error.exist.id'));
			}
			
			$checknext=!$this->isErrorCheck();
			if($checknext){
				if(($this->fatherid<1)){
					$this->fatherid=0;
					$this->rootid=DB::toQueryInt($this->TableName,'max','rootid',$this->sqlQuery)+1;
				}
				else{
					$sql='select * from '.$this->TableName.' where '.$this->sqlQuerys.' sortid='.$this->fatherid;
					$treeTmp=DB::queryTree($sql);
					if($treeTmp->getCount()<1){ $this->addError($this->getLang('error.not.fatherid')); }
					else{
						$this->levelid=$treeTmp->getItemInt('levelid')+1;
						$this->rootid=$treeTmp->getItemInt('rootid');
						$this->orderid=$treeTmp->getItemInt('orderid');
						$sql='select orderid from '.$this->TableName.' where '.$this->sqlQuerys.' rootid='.$this->rootid.' and orderid>'.$this->orderid.' and levelid<='.($this->levelid-1).' order by orderid asc limit 0,1';
						$this->orderid=DB::queryInt($sql);
						if($this->orderid<1){
							$sql='select max(orderid) from '.$this->TableName.' where '.$this->sqlQuerys.' rootid='.$this->rootid;
							$this->orderid=DB::queryInt($sql)+1;
						}
					}
					unset($treeTmp);
				}
			}
			
			if($this->isRaiseError()) return;
			else{
				$this->doDataDispose($this->action);
				//debugTree($this->treeData,'treeData');
				
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				
				$sql='update '.$this->TableName.' set orderid=orderid+1 where '.$this->sqlQuerys.' rootid='.$this->rootid.' and orderid>='.$this->orderid.' and sortid<>'.$this->sortid;
				DB::exec($sql);
				
				$this->doUpdateCache();
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doEdit()
	{
		global $dcs,$ctl;
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;		//sortid
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		$this->pages->setFormModule($this->module);
		$this->pages->addFormPre('channel',$this->channelValue);
		$this->pages->addFormPre('classid',$this->classid);
		$this->pages->setFormTree($this->treeRS);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
				$this->doDataDispose($this->action);
				//debugTree($this->treeData,'treeData');
				
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery);
				$this->doUpdateCache();
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doDel()
	{
		if(!$this->isChecked('lock')) return;
		global $dcs,$ctloa;
		$this->id=$this->id;
		//debugx('channel.tablename='.$this->channelTableName);
		if(len($this->channelTableName)<1){
			$this->doMessages('!handle',$this->getLang('error.no.channel'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery=$this->sqlQuerys.' fatherid='.$this->id;
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)>0){
			$this->doMessages('!handle',$this->getLang('error.exist.child'),$this->getURL('action=list'));
			return;
		}
		//##########
		/*
		$sqlQuery=''.$this->DefineID.'='.$this->id;
		if(DB::toQueryNum($this->channelTableName,'count','*',$sqlQuery)>0){
			$this->doMessages('!handle',$this->getLang('error.exist.data'),$this->getURL('action=list'));
			return;
		}
		*/
		//##########
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;
		$sql='delete from '.$this->TableName.' where '.$sqlQuery;
		DB::exec($sql);
		$this->doUpdateCache();
		$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
	}
	
	//####################
	protected function doOrder()
	{
		global $dcs,$ctl;
		$isUpdate=false;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'rootid,orderid');
		$tableClass=DB::queryTable($sql);
		$tableClass->doItemBegin();
		for($t=0;$i<$tableClass->getRow();$i++){
			$sortid=$tableClass->getItemValue('sortid');
			$orderid=post('orderid'.$sortid);
			//debugx(($forumid.' - '.$orderid.' - '.toInt($orderid));
			if(len($orderid)>0 && isInt($orderid)){
				$orderid=toInt($orderid);
				if($orderid!=$tableClass->getItemValueInt('orderid')){
					$sql='update '.$this->TableName.' set orderid='.$orderid.' where '.$this->sqlQuerys.' sortid='.$sortid;
					DB::exec($sql);
					$isUpdate=true;
				}
			}
			$tableClass->doItemMove();
		}
		if($isUpdate) $this->doUpdateCache();
		$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
	}
	
	//####################
	protected function doUnite()
	{
		global $dcs,$cfg,$ctl;
		$this->loadPages();
		$this->pages->setFormModule($this->module);
		$this->pages->setFormFile($this->action);
		$this->pages->addFormPre('channel',$this->channelValue);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$checknext=!$this->isErrorCheck();
			if($checknext){
				//debugx('channel.tablename='.$this->channelTableName);
				if(len($this->channelTableName)<1) $this->addError($this->getLang('error.no.channel'));
			}
			
			if($this->isRaiseError()) return;
			else{
				$sourceid=$this->treeData->getItemInt('sourceid');
				$sortid=$this->treeData->getItemInt('sortid');
				$sql='update '.$this->channelTableName.' set sortid='.$sortid.' where sortid='.$sourceid.'';
				//debugx($sql);
				DB::exec($sql);
				$this->doUpdateCache();
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
		global $dcs,$ctl;
		$this->doHandle();
		$this->setVar('list.num',200);
		$this->doAppendQuery($this->sqlQuery);
		$this->setPageMode('lists');
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		$this->addBoxVar('url.add',$this->getURL('action=add&id=[item:nid]'));
		$this->addBoxVar('url.edit',$this->getURL('action=edit&id=[item:nid]'));
		$this->addBoxVar('url.del',$this->getURL('action=del&id=[item:nid]'));
		$this->addBoxVar('url.moveup',$this->getURL('action=move&mode=up&id=[item:nid]'));
		$this->addBoxVar('url.movedown',$this->getURL('action=move&mode=down&id=[item:nid]'));
		$this->addBoxVar('url.sort',$this->getURL('action=sort&id=[item:nid]'));
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	}
	protected function doListFilter(&$tableData)
	{
		$tableData->doAppendFields('nid,_space,_ordericon');
		$tableData->doBegin();
		while($tableData->isNext()){
			$sortid=$tableData->getItemValueInt('sortid');
			$tableData->setItemValue('nid',$sortid);
			$levelid=$tableData->getItemValueInt('levelid');
			$_ordericon='';
			if($levelid==1) $_ordericon='s';
			$_space='';
			for($l=2;$l<=$levelid;$l++){
				$_space.='&nbsp; &nbsp;';
			}
			$tableData->setItemValue('_space',$_space);
			$tableData->setItemValue('_ordericon',$_ordericon);
			//debugx($tableData->getItemValue('sortid'));
		}
	}
}
?>