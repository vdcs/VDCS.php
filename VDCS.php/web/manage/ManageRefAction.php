<?
trait ManageRefAction
{
	
	protected function parseHandle()
	{
		$this->doHandle();
		if($this->isHandle()){
			$ids=$this->chn->getVar('handle.ids');
			$this->handleExtend($this->chn->getVar('handle'),$ids,$this->chn->tableDataHandle);
			$this->setSucceed();
		}
		else{
			$this->setStatus('failed');
		}
	}
	protected function handleExtend($handle,$ids,$tableData)
	{
		switch($handle){
			case 'delete':
				//if($this->attrm->is()) $this->attrm->doDataRemove($ids);
				break;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function setHandle($s){return $this->chn->setHandle($s);}
	protected function doHandler($mod=null,$put=1)
	{
		$this->chn->doHandle($mod);
		switch($put){
			case 1:		$this->addDTML('_debug.handle',$this->chn->getVar('handle.debug'));break;
		}
		$this->addVar('handle',$this->chn->getVar('handle'));
		$this->addVar('handle.ids',$this->chn->getVar('handle.ids'));
		$this->addVar('handle.total',$this->chn->getVar('handle.total'));
		$this->addVar('handle.message',$this->chn->getVar('handle.message'));
		$this->addVar('handle.backurl',$this->chn->getVar('handle.backurl'));
		/*
		if($this->chn->isHandle()){
			//##########
			$actionValue=$this->v('action.handle.value');
			if(len($actionValue)<1) $actionValue=$this->v('action.value');
			if(len($actionValue)<1 && $actionValue!='null'){
				$actionValue='delete';
				$this->addVar('action.value',$actionValue);
			}
			//debugx($actionValue);
			if(len($actionValue)>0){
				if($actionValue==$this->chn->getVar('handle.value')) $this->doActionParse();
			}
			//##########
			//doEventLog('',1,getLangs('handle.option.'.tmpHandle).' '.getLang(ctl.module,'title.name'),getLangs('title.channel').': '.var_channel.'  ids: '.tmpIDS)
			//##########
		}
		*/
	}
	protected function isHandle(){return $this->chn->isHandle();}
	

	protected function doHandle($mod=null,$put=1)
	{
		if(!$this->ma->isLocked()){
			$this->doHandler($mod,$put);
		}
		$this->ui->setActionURL($this->getURL('action=handle'));
	}
	protected function doHandles($mod=null,$put=2)
	{
		if(!$this->isChecked('lock')) return;
		$this->doHandle($mod,$put);
		if($this->chn->isHandle()){
			$this->theme->setPage('page');
			$this->theme->setModule('');
			$this->theme->setAction('message');
			$this->doMessages('!',$this->chn->getVar('handle.message'),$this->chn->getVar('handle.backurl'));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doList()
	{
		//$this->action='list';
		//$this->theme->setAction($this->action);
		if($this->isclass && strlen($this->classid)>0){
			$this->doAppendURL('classid='.$this->classid);
		}
	}
	protected function doListServe()
	{
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
	  	$this->doBoxParse();
	  	$this->listServeSet();
	}
	protected function listServeSet()
	{
		$this->setSucceed();
	  	if($this->_var['paging.show']) $this->addVarPaging();
	  	$this->doListFilter($this->tableList);
	  	$this->addTable('list',$this->tableList);
	  	$this->dictServe();
	}

	protected function doListFilter(&$tableData)
	{
		/*
		$tableData->doBegin();
		while($tableData->isNext()){
			
			debugx($tableData->getItemValue('id'));
		}
		*/
	}
	
	protected function doUpdateTotal($channel_='')
	{
		return false;
		if(!ManageExtend::isUpdateTotalTerm()) return false;
		$totalKey='total.'.($channel_?$channel_:$this->getChannel());
		if(len($this->_p_)>0) $totalKey.='.'.$this->_p_;
		$_total=$this->p->getTotal();
		if(('-'.$cfg->getData($totalKey))!=('-'.$_total)){
			$this->doMessageHint('<div class="gray">Auto update ('.$totalKey.'): '.$_total.'</div>','append');
			$cfg->setData($totalKey,$_total);
		}
	}
	

	protected function dictServe($dicts=null)
	{
		$_items=PagesCommon::dictItemString();
		//debugx($_items);
		$this->addVar('dicts.string',$_items);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadPaging($t=0)
	{
		$page=queryi('page');
		$listnum=queryi('listnum');
		if($listnum<1) $listnum=$this->_var['paging.listnum'];
		if($listnum<1) $listnum=$this->_var['list.num'];
		$this->ctl->loadPaging();
		$this->p->setPage($page);
		$this->p->setPageNum(5);
		$this->p->setListNum($listnum);
		$this->p->setConfig('url',$this->getURL('action!s'));
		if($this->chn->is()){
			$tablename=$this->getConfiga('table.name',true);
			$this->p->setDB('table',$tablename);
			//debugx('tablename='.$tablename);
			$fields=$this->getConfiga('table.fields',true);
			if(!$fields) $fields=$this->getConfiga('table.field',true);
			//debugx('fields='.$fields);
			$this->p->setDB('field',$fields);
			$tmpkey='table.query';
			if($this->mode) $tmpkey.='.'.$this->mode;
			$tmpQuery=$this->getConfiga($tmpkey,true);
			$tmpQuery=DB::sqla($tmpQuery,$this->_var['AppendQuery']);
			//debugx($tmpQuery);
			if($this->classid>0) $tmpQuery=DB::sqla($tmpQuery,ModelClassExtend::toSQLQuery($this->chn->get(),$this->classid));
			if($this->classid<0) $tmpQuery=DB::sqla($tmpQuery,'classid<1');
			//debugx('=='.$tmpQuery);
			if($this->_var['SearchMode']==1) $tmpQuery=$this->toSearchQuery($tmpQuery);
			//debugx('=='.$tmpQuery);
			$this->p->setDB('query',$tmpQuery);
			$tmpkey='table.order';
			if($this->taxis) $tmpkey.='.'.$this->taxis;
			//debugx('=='.$tmpkey);
			$this->p->setDB('order',$this->getConfiga($tmpkey,true));
			if($t==1) $this->doPaging();
		}
	}
	
	public function doPaging()
	{
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
	}
	

	/*
	########################################
	########################################
	*/
	public function doActionParse()
	{
		$_channel=$this->getVar('action.channel');
		if(!$_channel) $_channel=$this->_chn_;
		$_action=$this->getVar('action.value');
		if(!$_action) $_action=$this->action;
		$_relate=$this->getVar('action.relate');
		switch($_action){
			case 'add':
			case 'edit':
			case $_relate:
				$rootid=toi($this->getVar('action.rootid'));
				$dataid=-1;
				if(len($this->getVar('action.dataid'))>0) $dataid=toi($this->getVar('action.dataid'));
				if($rootid>0){
					if($dataid<0){
						$dataid=$this->id;
						if($dataid<1) $dataid=DB::toQueryInt($this->getConfig('table.name'),'max',$this->getConfig('table.field.id'),'');
					}
				}else{
					$rootid=$this->id;
					if($rootid<1) $rootid=DB::toQueryInt($this->getConfig('table.name'),'max',$this->getConfig('table.field.id'),'');
				}
				//treeData.addItem 'action.dataid',DB::toQueryInt($this->getConfig('table.name'),'max',$this->getConfig('table.field.id'),'');
				CommonUploadExtend::doParseRelate($_channel,$rootid,$dataid);
				break;
			case 'delete':
				$rootids=$this->getVar('action.rootid');
				$dataids=$this->getVar('action.dataid');
				if(!$rootids){
					$rootids=$this->getVar('handle.ids');				//dcs.request.getFormsID('_select_id')
				}else{
					if(!$dataids) $dataids=$this->getVar('handle.ids');		//dcs.request.getFormsID('_select_id')
				}
				CommonUploadExtend::doParseDelete($_channel,$rootids,$dataids);
				break;
		}
	}

}
