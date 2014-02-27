<?
class PortalSystemReport extends PortalSystemBase
{
	
	public function doLoad()
	{
		// event
		$this->mevent=new UcEventManage();
		$this->mevent->setChannel($this->_p_);
		$this->mevent->setURC($this->UARC);
		$this->mevent->setUID($this->UID);
		$this->mevent->init();
	}
	
	public function doParse()
	{
		switch($this->action){
			case 'trans':
				$this->theme->setModule('form');
				$this->doTrans();
				break;
			case 'handle':
				$this->doHandles();
				break;
			default:
				if(!$this->action) $this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
		}
	}
	
	
	//####################
	protected function doTrans()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=$this->id;
		$sqlQuery=$this->getConfig('table.field.id').'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeDat=DB::queryTree($sql);
		if($this->treeDat->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		//##########
		
		//########## event
		$this->mevent->setAction('trans');
		if($this->mevent->is()){
			$this->mevent->setRootid($this->id);
			$this->treeDat->doAppendTree($this->mevent->getFormDataTree());
		}
		//##########
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->pages->setFormTree($this->treeDat);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery);
				//########## event
				if($this->mevent->is()){
					$this->mevent->setRootid($this->id);
					$this->mevent->doFormSave($this->treeData);
				}
				//##########
				$this->doMessages('!handle',$this->chn->toHandleStatReplace($this->getLang('handle.ok.'.$this->action)),$this->getURL('action=list'));
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
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		$this->addBoxVar('url.trans',$this->getURL('action=trans&id=[item:id]'));
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		$tableData->doAppendFields('channel.name,uname');
		$tableData->doBegin();
		while($tableData->isNext()){
			$channelname=$tableData->getItemValue('channel');
			$tableData->setItemValue('channel.name',$channelname);
			
			$uname=$tableData->getItemValue($this->TablePX.'realname');
			if(!$uname) $uname=$tableData->getItemValue('username');
			$tableData->setItemValue('uname',$uname);
		}
	}
}
?>