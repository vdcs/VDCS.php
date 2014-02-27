<?
class PortalSystemUpload extends PortalSystemBase
{
	
	public function doParse()
	{
		switch($this->action){
			case 'handle':
				$this->doHandles();
				break;
			default:
				$this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
		}
	}
	
	protected function doList()
	{
		$this->doHandle();
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		$this->addBoxVar('url.ua',$this->toURLCommon('url','channel=ua&id=[item:uuid]'));
		$this->addBoxVar('url.download',$this->toURLCommon('download','module=upload&id=[item:id]'));
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		$tableData->doAppendFields('channel.name,user,name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$channelname=$tableData->getItemValue('channel');
			$tableData->setItemValue('channel.name',$channelname);
			$_user=$tableData->getItemValue($this->TablePX.'realname');
			if(len($_user)<1) $_user=$tableData->getItemValue('username');
			$tableData->setItemValue('user',$_user);
			$_name=$tableData->getItemValue($this->TablePX.'filename');
			if(len($_name)<1) $_name='(无)';
			$tableData->setItemValue('name',$_name);
		}
	}
}
?>