<?
class PagePortal extends ManagePortalBase
{
	public function doLoad()
	{
	
	}
	
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		//$this->pages->setFileName('data');
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
			$this->setStatus('nodata');
			return false;
		}
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		return true;
	}
	
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseList()
	{
		$this->doList();
	}
	
	
	//####################
	public function doThemeCache()
	{
		//$this->refThemeCache();
	}
	
}
?>