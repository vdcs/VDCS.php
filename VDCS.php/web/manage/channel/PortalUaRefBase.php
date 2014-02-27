<?
trait PortalUaRefBase
{
	//protected $UURC='';
	protected $MinID=10000;
	
	protected function getMinID(){return $this->MinID;}
	
	
	//####################
	//####################
	public function doInit()
	{
		$this->initVar();
		$this->initSet();
	}
	public function initVar()
	{
		if(!$this->UARC) $this->UARC=APP_UA;
		if(!$this->UURC) $this->UURC=APP_UA;
		$this->MinID=&Ua::instance($this->UURC)->MinID;
	}
	public function initSet()
	{
	}
	
	
	//####################
	//####################
	public function doLoad()
	{
		$this->refLoad();
		$this->doLoadSet();
	}
	public function doLoadSet()
	{
		
	}
	
	
	//####################
	//####################
	protected function refLoad()
	{
		//$this->MinID=$this->getMinID();
		
		$this->theme->setSubdir('ua');
		$this->theme->setPage($this->UURC);
		
		// pivotal
		$this->mpivotal=new UcPivotalManage();
		$this->mpivotal->setURC($this->UURC);
		$this->mpivotal->init();
	}
	public function refThemeCache()
	{
		$this->theme->doCacheFilterTree('view','mpo.treeView');
		$this->theme->doCacheFilterTree('uad','mpo.treeView');
	}
	
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$this->id=DB::queryInt('select max('.$this->FieldID.') from '.$this->TableName)+1;
		if($this->id<$this->MinID) $this->id=$this->MinID;
		$this->no=$this->id;
		$this->loadPages();
		$this->pages->addFormVar('id',$this->id);
		$this->pages->addFormVar('no',$this->no);
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
		}
		//##########
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$this->sqlQuery=$this->FieldID.'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			$this->setStatus('nodata');
			return false;
		}
		$this->isInfo=false;
		$this->inforeal=$this->getConfig('info:table.name')?true:false;
		if($this->inforeal){
			$sql=DB::sqlSelect($this->getConfig('info:table.name'),'','*',$this->sqlQuery,'',1);
			$treeInfo=DB::queryTree($sql);
			if($treeInfo->getCount()>0){
				$this->treeRS->doAppendTree($treeInfo);
				$this->isInfo=true;
			}
			unset($treeInfo);
		}
		//$this->id=$this->treeRS->getItemInt($this->FieldID);
		$this->loadPages();
		$this->pages->setFormModule($this->modules);
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->treeRS->doAppendTree($this->mpivotal->getFormDataTree());
		}
		//##########
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refListLoad()
	{
		return true;
	}
	
	//####################
	protected function refViewLoad()
	{
		//$uid=queryi('id');
		$ua=Ua::instance($this->UURC);
		$this->treeView=$ua->queryTree($this->id,1);
		$this->doViewFilter($this->treeView);
		return true;
	}
	protected function doViewFilter(&$treeData)
	{

	}

	//####################
	protected function refAutologinLoad()
	{
		if(!$this->isChecked('lock')) return false;
	}
	
	
	//####################
	//####################
	protected function doDataFilter($action='')
	{
		
	}
	
	protected function doFormCheck($action='')
	{
		$isukey=false;
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_id=$this->treeData->getItemInt($this->FieldID);
			if($_id<1) $_id=$this->id;
			if($action=='add' && $_id>0){
				$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'='.$_id.'';
				if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.id'));
			}
			$_no=$this->treeData->getItem($this->TablePX.'no');
			if(len($_no)>0){
				$isukey=true;
				if(!utilCheck::isName($_no)){ $this->addError($this->getLangx('error.norule.no')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'no=\''.$_no.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.no'));
				}
			}
			$_name=$this->treeData->getItem($this->TablePX.'name');
			if(len($_name)>0){
				$isukey=true;
				if(!utilCheck::isName($_name)){ $this->addError($this->getLangx('error.norule.name')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'name=\''.$_name.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.name'));
				}
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_email=$this->treeData->getItem($this->TablePX.'email');
			if(len($_email)>0){
				$isukey=true;
				if(!utilCheck::isEmail($_email)){ $this->addError($this->getLangx('error.norule.email')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'email=\''.$_email.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.email'));
				}
			}
			$_mobile=$this->treeData->getItem($this->TablePX.'mobile');
			if(len($_mobile)>0){
				$isukey=true;
				if(!utilCheck::isMobile($_mobile)){ $this->addError($this->getLangx('error.norule.mobile')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'mobile=\''.$_mobile.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.mobile'));
				}
			}
			$_idcard=$this->treeData->getItem($this->TablePX.'idcard');
			if(len($_idcard)>0){
				$isukey=true;
				if(!utilCheck::isIDCard($_idcard)){ $this->addError($this->getLangx('error.norule.idcard')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'idcard=\''.$_idcard.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLangx('error.exist.idcard'));
				}
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			debugTree($this->treeData);
			if(!$isukey) $this->addError($this->getLangx('error.no.ukey'));
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_names=$this->treeData->getItem($this->TablePX.'names');
			if(len($_names)>0){
				if(!utilCheck::isName($_names)) $this->addError($this->getLangx('error.norule.names'));
			}
			$_shortname=$this->treeData->getItem($this->TablePX.'shortname');
			if(len($_shortname)>0){
				if(!utilCheck::isName($_shortname)) $this->addError($this->getLangx('error.norule.shortname'));
			}
			$_realname=$this->treeData->getItem($this->TablePX.'realname');
			if(len($_realname)>0){
				if(!utilCheck::isName($_realname)) $this->addError($this->getLangx('error.norule.realname'));
			}
		}
		
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->mpivotal->doFormCheck();
		}
		//##########
	}
	
	
	//####################
	//####################
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