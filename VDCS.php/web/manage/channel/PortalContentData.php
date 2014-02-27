<?
class PortalContentData extends ManagePortalBase
{
	public $isRoot=false;
	public $RootID=-1;
	public $totalData=0;
	public $channel='';
	public $RootSQL='';
	public $treeRoot;
	
	
	####################
	####################
	public function doInit()
	{
		$this->data['themes.page']='content.data';
	}
	
	public function doLoad()
	{
		$this->treeRoot=newTree();
		$this->theme->setPage($this->data['themes.page']);
		if(len($this->getChannel())>0) { $this->setChannel($this->getChannel()); }
		if(len($this->getVar('form.mode'))<1) { $this->setVar('form.mode',$this->getConfig('form.mode')); }
		//$this->channel=$this->getChannel();
	}
	
	public function doParse()
	{
		$this->doBase();
		if(toNum($this->RootID)>0){
			switch($this->action){
			case 'add':
				$this->doAdd();
				break;
			case 'edit':
				$this->doEdit();
				break;
			case 'handle':
				$this->doHandles();
				break;
			default:
				$this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
			}
		}else{
			go($this->getURL('portal=&action=list!ip'));
		}
	}
	
	####################
	####################
	public function doBase()
	{
		$this->initRoot();
		$sql=DB::sqlSelect($this->getConfig('','table.name'),'','*',$this->getConfig('','table.field.id').'='.$this->RootID);
		debugx($sql);
		$this->treeRoot=DB::queryTree($sql);
		if($this->treeRoot->getCount()<1){
			$this->RootID=0;
			$this->_m_='';
			$this->doMessages('',$this->getLang('error.not.exist'),$this->getURL('portal=&action=list!ip'));
			return;
		}
		$this->isRoot=true;
		$this->setAppendURL('rootid='.$this->RootID);
		//$this->setActionVar('rootid',$this->RootID);
		
		$treeTemp=newTree();
		$treeTemp->setArray($this->treeRoot->getArray());
		
		$treeTemp->doFilter($this->getConfig('','table.px'));
		$treeTemp->doAppendPrefix('root.');
		//debugTree($treeTemp);
		//$mpFrame->addVarTree($treeTemp);		//?????
		
		$this->treeData->addItem('rootid',$this->RootID);
	}

	####################
	####################
	public function initRoot()
	{
		$this->RootID=queryi('dataid');
		if($this->RootID<1) $this->RootID=$this->dataid;
		$this->RootSQL='rootid='.$this->RootID;
	}
	
	public function doDataTotalUpdate()
	{
		$FieldRootTotalData=$this->getConfig('','table.field.total.data');
		
		if((!$this->s->isQuery()) &&(len($FieldRootTotalData)>0)){
			if((len($this->treeRoot->getItem($FieldRootTotalData))<1) ||(toInt($this->totalData)<>$this->treeRoot->getItemInt($FieldRootTotalData))){
				$sql=$this->getConfig('update.sql.root.data');
				if(len($sql)<1) { $sql='update '.$this->getConfig('','table.name').' set '.$FieldRootTotalData.'={$total.data} where '.$this->getConfig('','table.field.id').'={$rootid}'; }
				$sql=rd($sql,'total.data',$this->totalData);
				$sql=rd($sql,'rootid',$this->RootID);
				//debugx($sql);
				DB::exec($sql);
			}
		}
	}
	
	//####################
	protected function doAdd()
	{
		if(!$this->isChecked('lock')) return;
		$this->loadPages();
		if(len($this->getVar('form.mode'))>0){
			$this->pages->setFormFile('data.'.$this->getVar('form.mode'));
		}else{
			$this->pages->setFormFile('data');
		}
		$this->loadPagesForm();
		if(!$this->ready(true)) return;
		
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		else{
			$this->treeData->addItem('rootid',$this->RootID);
			$this->treeData->addItem('sp_emoney',0);
			$this->treeData->addItem('sp_points',0);
			$this->treeData->addItem('sp_poll_agree',0);
			$this->treeData->addItem('sp_poll_oppose',0);
			DB::executeInsert($this->getConfig('table.name'),$this->getConfig('table.fields.add'),$this->treeData);
			
			$this->doActionParse();
			$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
			return;
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doEdit()
	{
		if(!$this->isChecked('lock')) return;
		$sqlQuery=$this->RootSQL.' and '.$this->getConfig('table.field.id').'='.$this->id;
		$sql=DB::sqlSelect($this->getConfig('table.name'),'','*',$sqlQuery);
		
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		if(len($this->getVar('form.mode'))>0){
			$this->pages->setFormFile('data.'.$this->getVar('form.mode'));
		}else{
			$this->pages->setFormFile('data');
		}
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if(!$this->ready(true)) return;
		
		$this->doPagesParse();
		
		if($this->isRaiseError()) return;
		else{
			$this->treeData->addItem('rootid',$this->RootID);
			DB::executeUpdate($this->getConfig('table.name'),$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
			$this->doActionParse();
			$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
			return;
		
		}
		$this->doPagesFormParse();
	}
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->loadPaging();
		$this->p->setDB('query',DB::sqla($this->p->getDB('query'),$this->RootSQL));
		$this->doPaging();
	 	$this->loadBox();
	  	$this->doListFilter($this->box->tableData);
	  	$this->doBoxParse();
	  	
	 	$this->totalData=$this->p->getTotal();
	  	$this->doDataTotalUpdate();
	}
	protected function doListFilter(&$tableData)
	{
 		$tableData->doAppendFields('channel.name,user');
 		$tableData->doBegin();
 		while($tableData->isNext()){
 			if(len($tableData->getItemValue($this->getConfig('table.px').'topic'))<1){
 				$tableData->setItemValue($this->getConfig('table.px').'topic',$this->getLang('title.name').$tableData->getI());
 			}
 		}
 	}
}
?>