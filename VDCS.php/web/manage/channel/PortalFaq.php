<?
class PortalFaq extends ManagePortalBase
{
	use PortalFaqRef;
	

	public function doLoad()
	{
		$this->pagex='faq';
		$this->theme->setPage($this->pagex);
		
		$this->refLoad();
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
	
	//####################
	protected function parseDelete()
	{
		if(!$this->loadData()) return false;
		$_place=0;

		if(len($this->key)>0){
			$xml=VDCSXCML::toTableXML($this->tableFaq);
			$treeXCML=VDCSXCMLExtend::toTree($xml);
			
			$_place=VDCSXCMLExtend::getTreePlace($treeXCML,'key',$this->key);
		}
		
		if($_place<1){
			$this->setMessages('!handle',$this->getLang('error.not.key'),$this->getURL('action=list'));
			$this->setStatus('nokey');
		}

		$treeXCML=VDCSXCMLExtend::toTreePlaceFilter($treeXCML,$_place);
		VDCSXCMLExtend::doFileUpdate($this->dataPath,$treeXCML);
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'),'!');
		$this->setSucceed();
		
	}
	
	//####################
	protected function parseList()
	{
		$this->doList();
	}
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}