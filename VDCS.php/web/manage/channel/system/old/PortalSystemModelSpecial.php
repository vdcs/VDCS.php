<?
class PortalSystemModelSpecial extends PortalSystemBase
{
	public function doLoad()
	{
		parent::doLoad();
		global $mpa;
		$mpa=new ManageAppModel();
		$mpa->setModel('special');
	}
	
	public function doParse()
	{
		global $ctl,$mpa;
		$mpa->doLoadFrame($ctl->subchannel);
		
		if(len($ctl->subchannel)<1){
			$this->doMessages('!','!model.no.channel','');
			return;
		}
		
		$mpFrame->addVar('menu.links'a->getMenuLinks());
		$this->sqlRelate='channel=\''.$ctl->subchannel.'\'';
		$this->cid='id';
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
	/*
	protected function doUnite()
	{
		$TableName=$cfg->getChannelValue($ctl->subchannel,'configure','pre.table.name');
		$this->loadPages();
		$this->pages->setFormFile($this->module.'.'.$this->action);
		$this->pages->addFormPre('channel',$ctl->subchannel);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$checknext=!$this->isErrorCheck();
		if($checknext){
				//debugx('channel='.$TableName);
				if(len($TableName)<1) $this->addError($this->getLang('error.'.$this->action.'.channel'));
			}
			
			if($this->isRaiseError()) return;
			else{
				$sourceid=$this->treeData->getItemInt('sourceid');
				$classid=$this->treeData->getItemInt('classid');
				$sql='update '.$TableName.' set classid='.$classid.' where classid='.$sourceid.'';
				//debugx($sql);
				DB::exec($sql);
				$this->doUpdateCache();
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	*/
	//####################
	protected function doAdd()
	{
		//$levelid=1;$rootid=0;
		$orderid=0;
		$specialid=DB::toQueryNum($this->TableName,'max',$this->getConfig('table.field.defineid'),$this->sqlRelate)+1;
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->pages->addFormPre('channel',$this->subchannel);
		$this->pages->addFormVar('specialid',$specialid);
		$this->pages->addFormVar('orderid',$orderid);
		//$this->pages->addFormVar('fatherid',$fatherid);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			//$fatherid=$this->treeData->getItemInt('fatherid');
			$specialid=$this->treeData->getItemInt('specialid');
			$checknext=!$this->isErrorCheck();
			if($checknext){
				if(toInt($specialid)<1 || DB::toQueryNum($this->TableName,'count','*',$this->sqlRelate.' and specialid='.$specialid)>0) $this->addError($this->getLang('error.exist.add'));
			}
			
			$checknext=!$this->isErrorCheck();
			if($checknext){
				$sql='select * from '.$this->TableName.' where '.$this->sqlRelate.' and specialid='.$specialid;
				$treeTmp=DB::queryTree($sql);
				if($treeTmp->getCount()<1){ $this->addError($this->getLang('error.not.fatherid')); }
				else{
					$levelid=$treeTmp->getItemInt('levelid')+1;
					//$rootid=$treeTmp->getItemInt('rootid');
					$orderid=$treeTmp->getItemInt('orderid');
					$sql='select orderid from '.$this->TableName.' where '.$this->sqlRelate.' and orderid>'.$orderid.' order by orderid asc limit 0,1';
					$orderid=DB::queryInt($sql);
					if(toInt($orderid)<1){
						$sql='select max(orderid) from '.$this->TableName.' where '.$this->sqlRelate;
						$orderid=DB::queryInt($sql)+1;
					}
				}
				unsetr($treeTmp);
			}
			
			$checknext=!$this->isErrorCheck();
			if($checknext){
				//$this->treeData->setItem('fatherid',$fatherid);
				$this->treeData->addItem('channel',$ctl->subchannel);
				//$this->treeData->addItem('levelid',$levelid);
				//$this->treeData->addItem('rootid',$rootid);
				$this->treeData->addItem('orderid',$orderid);
				$this->treeData->addItem('popedom','');
				$this->treeData->addItem('configure','');
				$this->treeData->addItem('issp',0);
				//debugTree($this->treeData,'treeData');
			}
			
			if($this->isRaiseError()) return;
			else{
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				
				$sql='update '.$this->TableName.' set orderid=orderid+1 where '.$this->sqlRelate.' and orderid>='.$orderid.' and specialid<>'.$specialid;
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
		$sqlQuery=$this->sqlRelate.' and '.$this->getConfig('table.field.defineid').'='.$this->id;		
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->pages->addFormPre('channel',$ctl->subchannel);
		$this->pages->setFormTree($this->treeRS);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
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
		global $dcs,$ctl;
		$sqlQuery=$this->sqlRelate.' and '.$this->getConfig('table.field.defineid').'='.$this->id;
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery=$this->sqlRelate;
		/*
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)>0){
			$this->doMessages('!handle',$this->getLang('error.del.exist.child'),$this->getURL('action=list'));
			return;
		}
		*/
		//##########
		/*
		$chnTreeSQL=$cfg->getChannelTree($ctl->subchannel,'sql',false);
		$chnTable=$chnTreeSQL->getItem('struct.table.name');
		$sqlQuery=''.$this->getConfig('table.field.defineid').'='.$this->id.'';
		if(DB::toQueryNum(chnTable,'count','*',$sqlQuery)>0){
			$this->doMessages('!handle',$this->getLang('error.exist.data'),$this->getURL('action=list'));
			return;
		}
		*/
		//##########
		$sqlQuery=$this->sqlRelate.' and '.$this->getConfig('table.field.defineid').'='.$this->id;
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
		$sql='select * from '.$this->TableName.' where '.$this->sqlRelate.' order by orderid';
		$tableClass=DB::queryTable($sql);
		$tableClass->doItemBegin();
		for($t=0;$i<$tableClass->getRow();$i++){
			$specialid=$tableClass->getItemValue('specialid');
			$orderid=post('orderid'.$specialid);
			//debugx(($forumid." - ".$orderid." - ".toInt($orderid));
			if(len($orderid)>0 && isInt($orderid)){
				$orderid=toInt($orderid);
				if($orderid!=$tableClass->getItemValueInt('orderid')){
					$sql='update '.$this->TableName.' set orderid='.$orderid.' where '.$this->sqlRelate.' and specialid='.$specialid;
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
	//####################
	protected function doUpdateCache()
	{
		global $cfg;
		$cfg->clas->doChannelUpdate($this->subchannel);
	}
	
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->setVar('list.num',200);
		$this->doAppendQuery($this->sqlRelate);
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
			$specialid=$tableData->getItemValueInt('specialid');
			$tableData->setItemValue('nid',$specialid);
			$levelid=$tableData->getItemValueInt('levelid');
			$_ordericon='';
			if($levelid==1) $_ordericon='s';
			$_space='';
			for($l=2;$l<=$levelid;$l++){
				$_space.='&nbsp; &nbsp;';
			}
			$tableData->setItemValue('_space',$_space);
			$tableData->setItemValue('_ordericon',$_ordericon);
			//debugx($tableData->getItemValue('specialid'));
		}
	}
}
?>