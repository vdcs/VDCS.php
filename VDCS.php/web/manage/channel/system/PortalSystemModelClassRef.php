<?
trait PortalSystemModelClassRef
{
	
	protected function refLoad()
	{
		$this->channeli=queryx('channeli');
		if(!$this->channeli) $this->channeli=queryx('subchannel');
		if(!$this->channeli) $this->channeli=queryx('channel');
		$this->queryRelate='channel='.DB::q($this->channeli,1);
		$this->doAppendQuery($this->queryRelate);
		$this->doAppendURL('channeli='.$this->channeli);
		//$this->addDTML('x.params','channeli='.$this->channeli);
		//$this->addVar('subchannel',$this->channeli);
	}
	
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('target_class','cpo.tableClass');
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
	protected function refListLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		return true;
	}
	
	protected function refAddLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		$sqlTerm=$this->sqlRelate('');
		$sql=DB::sqlSelect($this->TableName,'max','classid',$sqlTerm);//,'channel='.DB::q('system')
		$maxid=DB::queryInt($sql)+1;
		$this->fatherid=queryi('fatherid');
		if($this->fatherid<1) $this->fatherid=queryi('id');
		if($this->fatherid>0){
			$sql=DB::sqlSelect($this->TableName,'max','classid','fatherid='.$this->fatherid);//$this->sqlRelates.' fatherid='.$this->fatherid);
			$this->classid=DB::queryInt($sql);
			if($this->classid>0) $this->classid++;
		}
		if($this->classid<1 && $this->fatherid>0){
			$this->classid=$this->fatherid*10+1;
		}
		if($this->classid>0){
			$sqlTerm=$this->sqlRelate('classid='.$this->classid);
			$sql=DB::sqlSelect($this->TableName,'count','classid',$sqlTerm);//$this->sqlRelates.
			if(DB::queryInt($sql)>0) $this->classid=0;
		}
		if( $this->classid<1) $this->classid=$maxid;
		$this->loadPages();
		$this->pages->addFormVar('channeli',$this->channeli);
		$this->pages->addFormVar('classid',$this->classid);
		$this->pages->addFormVar('orderid',$this->orderid);
		$this->pages->addFormVar('fatherid',$this->fatherid);
		$this->loadPagesForm();
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
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
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
		$this->sqlQuery=$this->sqlRelate($this->sqlQuery);
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeView=DB::queryTree($sql);
		if($this->treeView->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$levelid=$this->treeView->getItem('levelid');
		//$sqlTerm=DB::sqla('levelid<='.DB::q($levelid,1),'id!='.DB::q($id,1));//'channel='.DB::q($this->_chn_,1)  'fatherid!='.DB::q($this->treeView->getItem('classid'))
		$sqlTerm='id!='.DB::q($id,1).' and channel='.DB::q($this->_chn_,1).' ';
		$sqlc=DB::sqlSelect($this->TableName,'','*',$sqlTerm);
		//debugx($sqlc);
		$this->tableClass=DB::queryTable($sqlc);
		return true;
	}
	
	protected function doFilterTree(&$treeRs)
	{
	
	}
}
?>