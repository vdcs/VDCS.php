<?
trait PortalSystemModelSpecialRef
{
	
	protected function refLoad()
	{
		$this->channeli=queryx('channeli');
		if(!$this->channeli) $this->channeli=queryx('subchannel');
		if(!$this->channeli) $this->channeli=queryx('channel');
		$this->queryRelate='channel='.DB::q($this->channeli,1);
		$this->doAppendQuery($this->queryRelate);
		$this->doAppendURL('channeli='.$this->channeli);
	}
	
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('class','cpo.tableClass');
	}
	
	protected function sqlRelate($sql)
	{
		$sql=DB::sqla($this->queryRelate,$sql);
		return $sql;
	}
	//####################
	protected function isRoot()
	{
		if(!$this->channeli){
			$this->doMessages('!handle',$this->getLang('error.no.root'),$this->getURL('action=list'));
			return false;
		}
		return true;
	}
	
	//####################
	protected function refEditLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$this->sqlQuery=$this->sqlRelate($this->sqlQuery);
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);				
		$this->loadPagesForm();
		$this->theme->setModule($this->module);
		return true;
	}
	
	protected function refAddLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		$sqlTerm=$this->sqlRelate('');
		$sql_max=DB::sqlSelect($this->TableName,'max','specialid',$sqlTerm);
		$maxid=DB::queryInt($sql_max)+1;
		
		$this->specialid=$maxid;
		
		$this->loadPages();
		//$this->pages->addFormPre('channel',$this->channel);
		$this->pages->addFormVar('specialid',$this->specialid);
		$this->pages->addFormVar('orderid',$this->orderid);
		$this->loadPagesForm();
		//$this->theme->setModule($this->module);
		return true;
	}
	
	protected function refViewLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeView=DB::queryTree($sql);
		if($this->treeView->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$levelid=$this->treeView->getItem('levelid');
		$sqlTerm=DB::sqla('levelid<='.DB::q($levelid,1),'id!='.DB::q($id,1));//'channel='.DB::q($this->_chn_,1)  'fatherid!='.DB::q($this->treeView->getItem('specialid'))
		$sqlc=DB::sqlSelect($this->TableName,'','*',$sqlTerm);
		//debugx($sqlc);
		$this->tableClass=DB::queryTable($sqlc);
		return true;
	}
	
	protected function refListLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		return true;
	}
	
	protected function doFilterTree(&$treeRs)
	{
	
	}
}
?>