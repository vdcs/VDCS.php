<?
class PortalForumClass extends PortalForumBase
{
	
	public function doLoad()
	{
		$this->theme->setPage('class');		//$this->module
	}
	
	public function doParse()
	{
		$this->channelValue=$this->_chn_;
		$this->channelTableName=$cfg->vp('topic:table.name');
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
			case 'unite':
				$this->theme->setModule('form');
				$this->doUnite();
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
	public function toSQL($type){return '';}
	
	protected function doUpdateCache()
	{
		ModelClassExtend::doCacheUpdate($this->channelValue);
	}
	
	
	protected function doDataDispose($action)
	{
		switch($action){
			case 'add':
				$this->classid=$this->treeData->getItemInt('classid');
				$this->fatherid=$this->treeData->getItemInt('fatherid');
				$fatherStatus=0;
				$fatherOrderMax=-1;
				if($this->fatherid>0){
					$sql='select * from '.$this->TableName.' where classid='.$this->fatherid;
					$treeFather=DB::queryTree($sql);
					if($treeFather->getCount()>0){
						$this->levelid=$treeFather->getItemInt('levelid')+1;
						$this->rootid=$treeFather->getItemInt('rootid');
						$fatherOrderMax=$this->getFatherOrderMaxid($fatherStatus,$this->rootid,$this->fatherid);
						$this->orderid=$fatherOrderMax+1;
						$this->parents=$treeFather->getItem('parents').','.$this->classid;
						//debugx($fatherStatus);
					}
					else{
						$this->fatherid=0;
					}
				}
				if(len($this->parents)<1) $this->parents=$this->classid;
				if(left($this->parents,1)==',') $this->parents=toSubstr($this->parents,2);
				if($this->fatherid<1){
					$this->levelid=1;
					$sql='select max(rootid) from '.$this->TableName.'';
					$this->rootid=DB::queryNum($sql)+1;
					$this->orderid=0;
				}
				
				$this->treeData->setItem('fatherid',$this->fatherid);
				$this->treeData->addItem('channel',$this->channelValue);
				$this->treeData->addItem('levelid',$this->levelid);
				$this->treeData->addItem('rootid',$this->rootid);
				$this->treeData->addItem('orderid',$this->orderid);
				$this->treeData->addItem('configure','');
				$this->treeData->addItem('parents',$this->parents);
				break;
			case 'edit':
				
				
				$this->treeData->addItem('parents',$this->parents);
				break;
		}
	}
	
	protected function getFatherOrderMaxid(&$status,$rootid,$fatherid)
	{
		$re=-1;
		$isFather=false;
		$fatherlevel=-1;
		$sql='select * from '.$this->TableName.' where rootid='.$rootid.' order by rootid,orderid';
		$tableData=DB::queryTable($sql);
		$tableData->doBegin();
		while($tableData->isNext()){
			if($fatherid==$tableData->getItemValueInt('classid')){
				$isFather=true;
				$fatherlevel=$tableData->getItemValueInt('levelid');
				$re=$tableData->getItemValueInt('orderid');
				//continue
			}
			if($isFather){
				if($tableData->getItemValueInt('levelid')<=$fatherlevel) break;
				$re=$tableData->getItemValueInt('orderid');
			}
		}
		if(re>-1){
			$status=1;
		}
		else{
			$tableData->doItemBegin();
			$re=$tableData->getItemValueInt('orderid');
		}
		return $re;
	}

	
	//####################
	//####################
	protected function doAdd()
	{
		$this->levelid=1;
		$maxid=DB::toQueryInt($this->TableName,'max',$this->DefineID,$this->sqlRelate)+1;
		$this->fatherid=queryi('fatherid');
		if($this->fatherid<1) $this->fatherid=queryi('id');
		if($this->fatherid>0){
			$this->classid=DB::toQueryInt($this->TableName,'max',$this->DefineID,$this->sqlRelates.' fatherid='.$this->fatherid);
			if($this->classid>0) $this->classid++;
		}
		if($this->classid<1){
			$this->classid=$this->fatherid*10+1;
			if(DB::toQueryInt($this->TableName,'count',$this->DefineID,$this->sqlRelates.' classid='.$this->classid)>0){
				$this->classid=0;
			}
		}
		if($this->classid<1) $this->classid=$maxid;
		$this->loadPages();
		$this->pages->setFormModule($this->module);
		$this->pages->addFormPre('channel',$this->channelValue);
		$this->pages->addFormVar('classid',$this->classid);
		$this->pages->addFormVar('orderid',$this->orderid);
		$this->pages->addFormVar('fatherid',$this->fatherid);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->fatherid=$this->treeData->getItemInt('fatherid');
			$this->classid=$this->treeData->getItemInt('classid');
			$checknext=!$this->isErrorCheck();
			if($checknext){
				if($this->classid<1 || DB::toQueryInt($this->TableName,'count','*',$this->sqlRelates.' classid='.$this->classid)>0) $this->addError($this->getLang('error.exist.id'));
			}
			
			$checknext=!$this->isErrorCheck();
			if($checknext){
				if(($this->fatherid<1)){
					$this->fatherid=0;
					$this->rootid=DB::toQueryInt($this->TableName,'max','rootid',$this->sqlQuery)+1;
				}
				else{
					$sql='select * from '.$this->TableName.' where '.$this->sqlQuerys.' classid='.$this->fatherid;
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
					unsetr($treeTmp);
				}
			}
			
			if($this->isRaiseError()) return;
			else{
				$this->doDataDispose($this->action);
				//debugTree($this->treeData,'treeData');
				
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				
				$sql='update '.$this->TableName.' set orderid=orderid+1 where '.$this->sqlQuerys.' rootid='.$this->rootid.' and orderid>='.$this->orderid.' and classid<>'.$this->classid;
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
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;		//classid
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		$this->pages->setFormModule($this->module);
		$this->pages->addFormPre('channel',$this->channelValue);
		$this->pages->setFormTree($this->treeRS);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
				$this->doDataDispose($this->action);
				//debugTree($this->treeData,'treeData');
				
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
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
		$sqlQuery=''.$this->DefineID.'='.$this->id;
		if(DB::toQueryNum($this->channelTableName,'count','*',$sqlQuery)>0){
			$this->doMessages('!handle',$this->getLang('error.exist.data'),$this->getURL('action=list'));
			return;
		}
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
		$isUpdate=false;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'rootid,orderid');
		$tableClass=DB::queryTable($sql);
		$tableClass->doItemBegin();
		for($t=0;$i<$tableClass->getRow();$i++){
			$classid=$tableClass->getItemValue('classid');
			$orderid=post('orderid'.$classid);
			//debugx(($forumid.' - '.$orderid.' - '.toInt($orderid));
			if(len($orderid)>0 && isInt($orderid)){
				$orderid=toInt($orderid);
				if($orderid!=$tableClass->getItemValueInt('orderid')){
					$sql='update '.$this->TableName.' set orderid='.$orderid.' where '.$this->sqlQuerys.' classid='.$classid;
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
				$classid=$this->treeData->getItemInt('classid');
				$sql='update '.$this->channelTableName.' set classid='.$classid.' where classid='.$sourceid.'';
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
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	}
	protected function doListFilter(&$tableData)
	{
		$tableData->doAppendFields('nid,_space,_ordericon');
		$tableData->doBegin();
		while($tableData->isNext()){
			$classid=$tableData->getItemValueInt('classid');
			$tableData->setItemValue('nid',$classid);
			$levelid=$tableData->getItemValueInt('levelid');
			$_ordericon='';
			if($levelid==1) $_ordericon='s';
			$_space='';
			for($l=2;$l<=$levelid;$l++){
				$_space.='&nbsp; &nbsp;';
			}
			$tableData->setItemValue('_space',$_space);
			$tableData->setItemValue('_ordericon',$_ordericon);
			//debugx($tableData->getItemValue('classid'));
		}
	}
}
?>