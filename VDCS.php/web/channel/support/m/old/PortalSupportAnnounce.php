<?
class PortalSupportAnnounce extends PortalSupportBase
{
	
	public function doLoad()
	{
		global $cfg;
		$this->tableChannel=$cfg->getTable('data.'.$this->_p_.'.channel');
		$this->tableSort=$cfg->getTable('data.'.$this->_p_.'.sort');
		
		$subchannel='';
		$this->tableChannel->doBegin();
		while($this->tableChannel->isNext()){
			if($this->subchannel==$this->tableChannel->getItemValue('key')){
				$subchannel=$this->subchannel;
				break;
			}
		}
		$this->subchannel=$subchannel;
	}
	
	public function doFrame()
	{
		global $mpFrame;
		$_names=$this->getLang('names');
		$_content='';
		$this->tableChannel->doBegin();
		while($this->tableChannel->isNext()){
			$_content.='<li><a href="'.$this->getURL('subchannel='.$this->tableChannel->getItemValue('key')).'">'.$this->tableChannel->getItemValue('name').$_names.'</a></li>';
		}
		$mpFrame->addVar('menu.content.links',$_content);
	}
	
	public function doParse()
	{
		switch($this->action){
			case 'add':
				$this->theme->setModule('form');
				$this->doAdd();
				break;
			case 'edit':
				$this->theme->setModule('form');
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
	}
	
	//####################
	protected function doAdd()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=0;
		$subchannel=$ctl->subchannel;if(len($subchannel)<1) $subchannel=OPTION_VALUE_NO1;
		$this->loadPages();
		$this->pages->setFormFile($this->_m_);
		$this->pages->addFormVar('channel',$subchannel);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doEdit()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=$this->id;
		$sqlQuery=$this->getConfig('table.field.id').'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		$this->pages->setFormFile($this->_m_);
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doFormCheck($action='')
	{
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_no=$this->treeData->getItem($this->TablePX.'no');
			if(len($_no)>0){
				if(!utilCheck::isName($_no)){ $this->addError($this->getLang('error.norule.no')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'no=\''.$_no.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.no'));
				}
			}
			$_name=$this->treeData->getItem($this->TablePX.'name');
			if(len($_name)>0){
				if(!utilCheck::isName($_name)){ $this->addError($this->getLang('error.norule.name')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'name=\''.$_name.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.name'));
				}
			}
			$_password=$this->treeData->getItem($this->TablePX.'password');
			if(len($_password)>0){
				if(!utilCheck::isPassword($_password)) $this->addError($this->getLang('error.norule.password'));
			}
		}
	}
	
	
	//####################
	//####################
	protected function doList()
	{
		if(len($this->subchannel)>0){
			$this->doAppendQuery('channel='.DB::q($this->subchannel,1));
		}
		$this->doHandle();
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		//$this->addBoxVar('url.popedoms',$this->getURL('action=popedoms&id=[item:id]'));
	  	$this->doBoxParse();
	}
	
}
?>