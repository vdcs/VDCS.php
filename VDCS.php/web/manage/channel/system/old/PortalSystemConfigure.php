<?
class PortalSystemConfigure extends PortalSystemBase
{
	
	public function doLoad()
	{
		//$this->_m_=$this->_p_;		//'configure';
		$fileStruct='';
		switch($this->mode){
			case '_var':
				$fileStruct='manage.config/struct.'.$this->mode;
				break;
			default:
				if(!isFile(appFilePath('manage.config/struct.'.$this->mode))){
					$this->mode='configure';
				}
				if($this->mode=='configure') $this->module=$this->mode;
				$fileStruct='manage.config/struct.'.$this->mode;
				break;
		}
		
		$this->xcmlm=new VDCSXCMLManager();
		//$this->xcmlm->setChannel('manage');
		//$this->xcmlm->addConfigVar('var.title',$this->getLang('title'));
		$this->xcmlm->setConfigVar('var.title',$this->getLang('title'));
		$this->xcmlm->setFileStruct($fileStruct);
		$this->xcmlm->setKey(queryx('key'));
		$this->xcmlm->doLoad();
		//debugx($this->xcmlm->PathSource);
		//debugx($this->xcmlm->PathSourceUp);
	}
	
	public function doParse()
	{
		$this->doFrame();
		if(!$this->channel) $this->action='';
		$this->action='list';
		$this->doList();
	}
	
	//####################
	public function doFrame()
	{
		$tmpTitle=$this->xcmlm->getConfigVar('var.title');
		if($this->xcmlm->tableStruct->isObj()){
			$this->xcmlm->tableStruct->doBegin();
			while($this->xcmlm->tableStruct->isNext()){
				$tmpKey=$this->xcmlm->tableStruct->getItemValue('key');
				$tmpName=$this->xcmlm->tableStruct->getItemValue('name');
				$tmpLinks.='<li><a href="'.$this->getURL('key='.$tmpKey.'').'">'.$tmpName.'</a></li>';
			}
		}
		$mpFrame->addVar('menu.content.title',$tmpTitle);
		$mpFrame->addVar('menu.content.links',$tmpLinks);
	}
	
	//####################
	public function doModifyFile()
	{
		$tmpExtKey='_none';
		$this->loadPages();
		//$this->pages->setConvertFlag(true);
		$this->pages->setFormTitle($this->xcmlm->getName());
		//debugvc($this->xcmlm->getFormXCML());
		$this->pages->setFormXML($this->xcmlm->getFormXCML());
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			//debugTree($this->treeData);
			
			if($this->isRaiseError()) return;
			else{
				$isUpdate=false;
				$this->treeData->doBegin();
				for($t=1;$t<=$this->treeData->getCount();$t++){
					if($this->xcmlm->isFileSourceItem($this->treeData->getItemKey())){
						$this->xcmlm->setFileUpdateItem($this->treeData->getItemKey(),$this->treeData->getItemValue());
						$isUpdate=true;
					}
					$this->treeData->doMove();
				}
				if($isUpdate){
					$this->xcmlm->doFileUpdate();
					VDCSCache::delCaches($this->xcmlm->getCache());
				}
				$tmpMessage=$this->getLang('handle.ok.modify');
				$tmpMessage=toDisp($tmpMessage,'name',$this->xcmlm->getName());
				$this->doMessages($this->xcmlm->getName(),$tmpMessage,DCS::browsePath(true));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	
	//####################
	//####################
	public function doList()
	{
		//debugTree($this->xcmlm->treeFileStruct,'treeStructFile');
		//debugTree($this->xcmlm->getFileSourceTree,'getFileSourceTree');
		//debugvc($this->xcmlm->getFormXCML);
		//$this->xcmlm->doFileUpdate();
		$this->doModifyFile();
	}
}
?>