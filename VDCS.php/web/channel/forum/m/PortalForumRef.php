<?php
trait PortalForumRef
{
	
	protected function refLoad()
	{
		
	}
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
	}
	
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		//$this->no=DB::queryInt('select max('.$this->FieldID.') from '.$this->TableName)+10001;
		$this->loadPages();
		//$this->pages->addFormVar('no',$this->no);
		
		//##########
		$this->loadPagesForm();
		return true;
	}
	
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();
		return true;
	}
	
	protected function refViewLoad()
	{
		
	}
	
	protected function doFilterTree(&$treeRs)
	{
		
	}
}
?>