<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalRefBase;
	
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	//####################
	protected function parseList()
	{
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		/*
		$tableData->doBegin();
		while($tableData->isNext()){
			
			debugx($tableData->getItemValue('id'));
		}
		*/
	}
	
	//####################
	protected function handleExtend($handle,$ids)
	{
		switch($handle){
			case 'delete':
				//if($this->attrm->is()) $this->attrm->doDataRemove($ids);
				break;
		}
	}
	
}
?>