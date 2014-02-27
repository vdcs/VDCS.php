<?
class PortalFaqX extends ManagePortalBaseX
{
	use PortalFaqRef;


	public function doLoad()
	{
		$this->refLoad();
	}
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		
		$this->tableFaq->addItem($this->treeData);
		
		$xml=VDCSXCML::toTableXML($this->tableFaq);
		$treeXCML=VDCSXCMLExtend::toTree($xml);
		
		VDCSXCMLExtend::doFileUpdate($this->dataPath,$treeXCML);
		
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		
		$xml=VDCSXCML::toTableXML($this->tableFaq);
		$treeXCML=VDCSXCMLExtend::toTree($xml);
		$treeContent=VDCSXCMLExtend::doItemUpdate($treeXCML,$this->treeData,'' ,$this->VarItemNum);
		VDCSXCMLExtend::doFileUpdate($this->dataPath,$treeContent);

		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	//####################
	protected function parseList()
	{
		$this->refListServe();
	}
	protected function doListFilter(&$tableData)
	{
		$tableData->doBegin();
		while($tableData->isNext()){
			$content=$tableData->getItemValue('content');
			$content=utilCode::toHTMLExplain($content);
			$content=utilCode::toHTMLTags($content);
			//$tableData->setItemValue('content',$content);
			//debugx($tableData->getItemValue('id'));
		}
	}
	
}
