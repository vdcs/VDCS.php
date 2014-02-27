<?
trait PortalDataRef
{
	protected function refLoad()
	{
		$this->rootid=queryi('rootid');
		$this->theme->setPage($this->getChannel().'.data');
		$this->doAppendURL('rootid='.$this->rootid);
		//$this->queryRelate='channel='.DB::q($this->_chn_,1);
	}
	
	//####################
	protected function isRoot()
	{
		global $cfg;
		if(!$this->rootid){
			$this->doMessages('!handle',$this->getLang('error.no.root'),$this->getURL('action=list'));
			return false;
		}
		
		$rootTableName=$cfg->vp('table.name');
		$pre=$cfg->vp('table.px');
		$this->rootTree=DB::queryTree('select * from '.$rootTableName.' where '.$pre.'id='.DB::q($this->rootid,1));
		//debugTree($this->rootTree);
		if($this->rootTree->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.no.root'),$this->getURL('action=list'));
			return false;	
		}
		return true;
	}
	
	public function refThemeCache()
	{
		$this->theme->doCacheFilterTree('root','cpo.rootTree');
	}
	
	protected function sqlRelate($sql='')
	{
		$sqlTerm=DB::sqla('channel='.DB::q($this->_chn_,1),'rootid='.DB::q($this->rootid));
		$sql=DB::sqla($sql,$sqlTerm);
		return $sql;
	}
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
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
	
	protected function refListLoad()
	{
		if(!$this->isRoot()) return false;
		if(!$this->isChecked('lock')) return false;
		$sql=$this->sqlRelate();
		$this->doAppendQuery($sql);
		
		return true;
	}
	
	
	protected function doFilterTree(&$treeRs)
	{
	
	}
}
?>