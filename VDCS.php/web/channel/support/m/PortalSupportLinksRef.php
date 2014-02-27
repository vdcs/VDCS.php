<?
trait PortalSupportLinksRef
{
	protected $DefaultPrepageNum=10000;
	
	protected function refLoad()
	{
		$this->__module=queryx('module');
		//$this->channel=$this->getChannel();
		$this->tableMenu=$this->getMenuTable();
		$this->doAppendURL('module='.$this->__module);
	}
	
	
	protected function getMenuTable()
	{
		$reTable=newTable();
		$reTable=VDCSDTML::getConfigCacheTable('common.channel/support/data.links.module');
		$reTable->doAppendFields('module,linkurl');
		$reTable->doBegin();
		while($reTable->isNext()){
			$reTable->setItemValue('module',$reTable->getItemValue('value'));
			$_menu=$reTable->getItemValue('value');
			$linkurl=$this->getURL('action=list').'&module='.$_menu;
			$reTable->setItemValue('linkurl',$linkurl);
		}
		return $reTable;
	}
	
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
	}
	
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$this->loadPages();
		$this->pages->addFormVar('module',$this->__module);
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.DB::q($id,1);
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$this->loadPages();
		//##########
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		return true;
	}
	
}
?>