<?
trait PortalTMlogRef
{
	
	protected function refLoad()
	{
		//$this->addDTML('x.params','apptype='.$this->apptype);
	}
	
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$this->loadPages();
		
		
		//$this->pages->addFormVar('date_expire',VDCSTime::toDateAdd(DCS::today(),'y',1));
		/*
		*/
		$id=DB::queryInt('select max('.$this->FieldID.') from '.$this->TableName.'');
		$id++;
		$this->pages->addFormVar('id',$id);
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
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		//debugTree($this->treeRS);
		//##########
		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();	
		return true;
	}
	
	protected function refViewLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;

		//debugTree($this->treeView);
		
		return true;
	}
	
	public function refThemeCache()
	{
		$this->theme->doCacheFilterLoop('apptype','cpo.tableAppType');
	}
	
}
?>