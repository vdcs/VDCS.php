<?
trait PortalUaRefUaX
{
	use PortalUaRefBase;
	
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		
		$this->id=$this->treeData->getItemInt($this->FieldID);
		$this->doFormCheck($this->action);
		
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem($this->FieldID,$this->id);
		/*
		$timezone=$this->treeData->getItemInt($this->TablePX.'timezone');
		if($timezone>0 && $timezone<50) $this->treeData->setItem($this->TablePX.'timezone',$timezone+100);
		*/
		
		$this->doDataFilter($this->action);
		//##########
		DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData,null,true);
		DB::execInsertx($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData,null,true);
		//##########
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->mpivotal->doFormSave($this->treeData);
		}
		//##########
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		
		$this->doFormCheck($this->action);
		
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem($this->FieldID,$this->id);
		/*
		$timezone=$this->treeData->getItemInt($this->TablePX.'timezone');
		if($timezone>0 && $timezone<50) $this->treeData->setItem($this->TablePX.'timezone',$timezone+100);
		*/
		
		$this->doDataFilter($this->action);
		//##########
		
		//记录日志
		$this->doModifyLogs();
		
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		if($this->isInfo) DB::execUpdatex($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		else DB::execInsertx($this->getConfig('info:table.name'),$this->getConfig('info:table.fields.add'),$this->treeData,$this->treeRS,true);
		//##########
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->mpivotal->doFormSave($this->treeData);
		}
		//##########
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	//####################
	protected function parseList()
	{
		if(!$this->refListLoad()) return;
		//$this->umauth=ManageAuth::checkAuth($this->ma);
		
		$this->setSearchMode(0);
		if($this->s->isQuery()){
			$this->doAppendQuery($this->s->getQuery());
		}
		/*
		if(!$this->umauth) return;
		if($this->umauth<10){
			$this->doAppendQuery('uid in '.ManageAuth::sqlIn($this->ma));
		}
		*/
		
		$this->doListServe();
	}
	
	//####################
	protected function parseViewi()
	{
		$uid=queryi('uid');
		$ua=Ua::instance(APP_UA);
		$uTree=newTree();
		$uTree=$ua->queryTree($uid);
		if($uTree->getCount()<1){
			$this->setStatus('failed');
			$this->setMessage($this->getLang('error.no.exist'));
			return;
		}
		$this->addVarTree($uTree,'info.');
		$this->setSucceed();
	}
	
	
	protected function doListFilter(&$tableData)
	{
		$this->doListFilterBase($tableData);
	}
	protected function doListFilterBase(&$tableData)
	{
		$relateid=$this->getConfig('table.field.relateid');
		if(!$relateid) $relateid=$this->FieldID;
		$relateids=$tableData->getValues($relateid);
		if($relateids){
			$_tablename=$this->getConfig('info:table.name');
			$_fieldid=$this->getConfig('info:table.field.id');
			$_fields=$this->getConfig('info:list.table.fields');
			if(!$_fieldid) $_fieldid=$relateid;
			if($_tablename && $_fields) $tableData=CommonExtend::toExtendTable($tableData,$relateid.'='.$_fieldid,'',$_tablename,$_fieldid.','.$_fields,$_fieldid.' in ('.$relateids.')');
		}
		$tableData->doAppendFields('_names');
		$tableData->doBegin();
		while($tableData->isNext()){
			$_names=$tableData->getItemValue('names');
			if(!$_names) $_names=$tableData->getItemValue('name');
			if(!$_names) $_names=$tableData->getItemValue('email');
			if(!$_names) $_names=$tableData->getItemValue('mobile');
			$tableData->setItemValue('_names',$_names);
		}
	}
	
	
	//####################
	//####################
	protected function parseSearch()
	{
		$keyword=queryx('keyword');
		if(!$keyword){
			$this->setMessage('缺少搜索关键字');
			$this->setStatus('keyword');
		}
		$this->addVar('keyword',$keyword);
		//$sqlTerm='email='.DB::q($keyword,1).' or names='.DB::q($keyword,1);
		// %t
		$sqlTerm='uid like '.DB::q($keyword,2).' or name like '.DB::q($keyword,2).' or email like '.DB::q($keyword,2).' or mobile like '.DB::q($keyword,2);
		$sqlTerm.=' or names like '.DB::q($keyword,2).' or realname like '.DB::q($keyword,2).' or nickname like '.DB::q($keyword,2).' or company like '.DB::q($keyword,2).' or url like '.DB::q($keyword,2).'';
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlTerm);
		$tableList=DB::queryTable($sql);
		if($tableList->getRow()<1){
			$this->setMessage('没有搜索到符合要求的记录');
			$this->setStatus('nodata');
		}
		
		$this->setTable($tableList);
		$this->setSucceed();
	}
	
	protected function parseSearchi()
	{
		$this->refSearchiLoad();
	}
	
	protected function refSearchiLoad()
	{
		$keywords = queryx('keyword');
		##########
		$sqlTerm='';
		$sqlTerm=DB::sqla($sqlTerm,'realname like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'nickname like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'company like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'url like '.DB::q($keywords,2),'or');
		$sqlInfo=DB::sqlSelect($this->TableName.'_info','','uid',$sqlTerm);
		//debugx($sqlInfo);
		##########
		$sqlTerm='uid like '.DB::q($keywords,2);
		$sqlTerm=DB::sqla($sqlTerm,'name like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'email like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'mobile like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'names like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'uid in ('.$sqlInfo.')','or');
		if($this->_var['AppendQuery']) $sqlTerm=DB::sqla($this->_var['AppendQuery'],'('.$sqlTerm.')');
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlTerm,'',10);
		$tableUser=DB::queryTable($sql);
		$opt=[];
		$opt['relateid']='uid';
		UaExtendManage::appendInfo($tableUser,$opt);
		$this->setTable($tableUser);
		$this->setSucceed();
	}

	
	//####################
	//####################
	protected function parseAutologin()
	{
		if(!$this->refAutologinLoad()) return;
		if(!$this->isChecked('lock')) return;
		if(!$this->ready(true)) return;
		
		$this->treeData->addItem('name',postx('name'));
		$_id=0;
		$_name=$this->treeData->getItem('name');
		if($_name){
			if(!utilCheck::isName($_name)){ $_message=$this->addError($this->getLang('error.norule.name')); }
			else{
				if(!$this->ua) $this->ua=&Ua::instance(APP_UA);
				//$this->ua->init();
				$_id=$this->ua->queryField('id','name='.DB::q($_name,1));
				if($_id<1) $_message=$this->getLang('error.exist.name');
			}
		}
		$this->treeData->addItem('_message',$_message);
		$this->setStatus('info');
		if(!$_message){
			$this->mpivotal->setUID($_id);
			$this->mpivotal->loadData();
			$_password=$this->mpivotal->getData('password');
			//debugx($_id.','.$_name','.$_email','.$_password);
			$this->ua->setID($_id);
			$this->ua->setData('_password',$_password);
			$this->ua->setData('_remember','');
			$this->ua->doLoginCheck();
			if($this->ua->isLogin()){
				$this->addVar('url',appURL('account'));
				$this->setSucceed();
			}
		}
	}
	
}
?>