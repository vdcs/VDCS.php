<?
trait PortalEmDepartmentRef
{
	protected $MinID=10000;
	
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
		//$this->FormFile='add';//使用同一个模板form.add.xcml
		if(!$this->isChecked('lock')) return false;
		
		$this->loadPagesForm();
		return true;
	}
	
	//####################
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
			return false;
		}
		//$this->treeRS->addItem('username',ManageExtend::toUserName($this->treeRS->getItemInt('userid')));
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();	
		return true;	
	}
	
}
?>