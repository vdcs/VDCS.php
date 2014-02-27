<?
trait PortalFaqRef
{
	public $VarItemNum=1;
	

	public function refLoad()
	{
		//$this->channel=$this->getChannel();
		$this->sort=queryx('sort');
		if(!$this->sort) $this->sort='about';
		$this->key=queryx('key');
		if(!$this->key) $this->key=queryx('id');
		$this->addVar('sort',$this->sort);
		$this->addVar('key',$this->key);

		$this->tableMenu=$this->getMenuTable();

		$this->doAppendURL('sort='.$this->sort);
		//$this->addDTML('x.params','sort='.$this->sort);
		//$this->addDTML('a.params','sort='.$this->sort);
	}
	public function refThemeCache()
	{
	}
	

	protected function getMenuTable()
	{
		$reTable=newTable();
		$reTable->setFields('sort,name,linkurl');
		$menusValue=$this->cfg->getChannelValue($this->_chn_,'configure','config.faq.menu');
		$menusAry=toSplit($menusValue,',');
		$menuLinks='';
		foreach($menusAry as $_menu){
			$treeItem=newTree();
			$treeItem->addItem('sort',$_menu);
			$treeItem->addItem('name',$this->cfg->getChannelValue($this->_chn_,'configure','var.title.'.$_menu));
			$treeItem->addItem('linkurl',$this->getURL('action=list&sort='.$_menu));
			$reTable->addItem($treeItem);
		}
		//<li><a href="'.$this->getURL('action=add&sort='.$this->sort).'">添加节点</a></li>
		return $reTable;
	}
	

	protected function getTable2Tree($strTable,$strKey,$strTree)
	{
		$reTree=newTree();
		$t=1;
		$strTable->doBegin();
		while($strTable->isNext()){
			$tmpKey=$strTable->getItemValue($strKey);
			if($tmpKey == $strTree){
				$this->VarItemNum=$t;
				$reTree=$strTable->getItemTree();
			}
			$t++;
		}
		return $reTree;
	}


	//####################
	protected function loadData()
	{
		$this->dataPath=appFilePath('common.channel/'.$this->_chn_.'/faq.'.$this->sort);
		if(!isFile($this->dataPath)){
			$this->setMessages('!handle',$this->getLang('error.not.exist.file'),$this->getURL('action=list'));
			$this->setStatus('noexist');
			return false;
		}
		$this->tableFaq=getFile2Table($this->dataPath);
		return true;
	}
	public function loadPages()
	{
		parent::loadPages();
		$this->pages->setFormChannel('system');
		//$this->pages->setFormFile('faq');
		//if($this->treeRS) $this->pages->setFormTree($this->treeRS);
		//$this->loadPagesForm();
	}
	
	//####################
	protected function refAddLoad()
	{
		if(!$this->isChecked('lock')) return false;
		if(!$this->loadData()) return false;
		$this->loadPages();
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		if(!$this->loadData()) return false;
		$this->treeRS=$this->getTable2Tree($this->tableFaq,'key',$this->key);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist.key'),$this->getURL('action=list'));
			$this->setStatus('nodata');
			return false;
		}
		$this->loadPages();
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		return true;
	}
	
	//####################
	protected function refListServe()
	{
		if(!$this->loadData()) return false;
		//$this->tableList=$this->tableFaq;
	 	$this->loadBox();
		$this->box->setDataTable($this->tableFaq);
	  	$this->doBoxParse();
	  	$this->listServeSet();
	}

}