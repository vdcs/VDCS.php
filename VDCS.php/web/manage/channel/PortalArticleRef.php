<?
trait PortalArticleRef
{
	protected $isclass=true;
	protected $DefaultPrepageNum=10000;
	
	protected function refLoad()
	{
		$this->chn->setVar('handle.upload','true');
		//attrtype
		$this->attrm=new ModelAttrManage();
		//debugx($this->getChannel());
		$this->attrm->setChannel($this->getChannel());
		$this->attrm->doLoad();
	}
	public function refThemeCache()
	{
		$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
	}
	
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		//########## attrtype
		$attrtype=queryx('attrtype');
		$this->attrm->setType($attrtype);
		if($this->attrm->is() && !$this->attrm->isType()){			//attrtype
			$this->theme->setPage('attr');
			$this->theme->setAction('select');
			$this->setStatus('noattr');
			return false;
		}
		//##########
		$this->loadPages();
		if($this->attrm->is()){		//attrtype
			$this->pages->addFormVar('attrtype',$this->attrm->getType());
			$this->pages->addFormXCML('attr',$this->attrm->getFormXCML());
		}
		if($this->isFieldMode('prepage')){
			$prepageNum=$this->DefaultPrepageNum;
			$this->pages->addFormVar($this->TablePX.'prepage_num',$prepageNum);
		}
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
			$this->setStatus('nodata');
			return false;
		}
		//UaExtend::appendTreeInfo($this->treeRS);
		
		//########## attrtype
		$attrtype=$this->treeRS->getItem($this->TablePX.'attrtype');
		if(len($attrtype)<1){
			$attrtype=query('attrtype');
			$this->treeRS->setItem($this->TablePX.'attrtype',$attrtype);
		}
		$this->attrm->setType($attrtype);
		if($this->attrm->is() && !$this->attrm->isType()){			//attrtype
			$this->theme->setPage('attr');
			$this->theme->setAction('select');
			$this->setStatus('noattr');
			return false;
		}
		if($this->attrm->is()){		//attrtype
			$this->attrm->setRootID($id);
		}
		//##########
		if($this->isFieldMode('prepage')){
			$prepageNum=$this->treeRS->getItemInt($this->TablePX.'prepage');
			if($prepageNum<2) $prepageNum=$this->DefaultPrepageNum;
			else $this->treeRS->setItem($this->TablePX.'prepage',2);
		}
		$this->loadPages();
		if($this->attrm->is()){		//attrtype
			$this->pages->addFormVar('attrtype',$this->attrm->getType());
			$this->pages->addFormXCML('attr',$this->attrm->getFormXCML());
			$this->treeRS->doAppendTree($this->attrm->getFormDataTree());
		}
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		return true;
	}
	
}
?>