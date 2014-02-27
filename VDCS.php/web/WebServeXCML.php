<?
class WebServeXCML
{
	use WebServeBaseRef;
	const HeaderType	= 'xml';
	
	
	public function output($strs)
	{
		$this->putHead();
		if($this->ishead){
			$strs=r($strs,VDCSXCML::XMLHeader(),'');
		}
		echo $strs;
	}
	public function putHead($head=true)
	{
		if(!$this->isHeader){
			pageHeader(self::HeaderType);
			$this->isHeader=true;
		}
		if(!$this->ishead && $head){
			echo VDCSXCML::XMLHeader().NEWLINE;
			$this->ishead=true;
		}
	}
	public function putData()
	{
		$this->putMaps();
	}
	public function putDefault()
	{
		$this->put(VDCSXCML::getXMLDefault());
	}
	public function putMaps()
	{
		if($this->maps){
			$this->maps->addItem('var',$this->treeVar,'tree');
			$this->maps->addItem('item',$this->tableData,'table');
			$this->put(VDCSXCML::toMapsXML($this->maps));
		}
		else if($this->treeVar->getCount()>0 || $this->tableData->getRow()>0){
			$this->maps=newMap();
			$this->maps->addItem('var',$this->treeVar,'tree');
			$this->maps->addItem('item',$this->tableData,'table');
			$this->put(VDCSXCML::toMapsXML($this->maps));
			//$this->put(VDCSXCML::toMapXML($this->treeVar,$this->tableData,'',$this->_tableFields));
		}
		else{
			$this->put(VDCSXCML::getXMLDefault());
		}
	}
	public function putMap($otree,$otable)
	{
		$this->put(VDCSXCML::toMapXML($otree,$otable));
	}
	
}
