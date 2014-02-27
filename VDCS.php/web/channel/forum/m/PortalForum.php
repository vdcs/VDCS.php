<?
class PortalForum extends PortalForumBase
{
	
	public function doParse()
	{
		switch($this->action){
			case 'view':
				$this->theme->setModule('view');
				$this->doView();
				break;
			default:
				$this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
		}
	}
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	$this->doUpdateTotal();
	}
	
	protected function doListFilter(&$tableData)
	{
		$tableData->doBegin();
		while($tableData->isNext()){
			//$tableData->setItemValue('_paystatus',$this->toOrderPaymentStatus($tableData->getItemValue('o_ispayment'),$tableData->getItemValue("o_ispay")));
		}
	}
	
}
?>