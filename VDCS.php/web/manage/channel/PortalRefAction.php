<?
trait PortalRefAction
{
	
	//####################
	protected function refFormLoad()
	{
		
	}
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$this->loadPages();
		$this->loadPagesForm();
		$this->refFormLoad();
		return true;
	}
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			$this->setStatus('nodata');
			return false;
		}
		//UaExtend::appendTreeInfo($this->treeRS);

		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		$this->refFormLoad();
		return true;
	}
	
}
?>