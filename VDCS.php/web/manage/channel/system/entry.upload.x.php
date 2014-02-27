<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalRefBase;
	
	
	public function doLoad()
	{
		$this->refLoad();
		//$this->chn->setVar('handle.debug.exec','true');
	}
	
	
	//####################
	protected function parseList()
	{
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		$tableData->doAppendFields('channel.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('channel.name',$tableData->getItemValue('channel'));
		}
	}
	
	//####################
	protected function handleExtend($handle,$ids,$tableData)
	{
		//debugTable($tableData);
		switch($handle){
			case 'delete':
				if($tableData) CommonUploadExtend::parseDeleted($tableData);
				break;
		}
	}
	
}
?>