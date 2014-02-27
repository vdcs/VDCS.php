<?
class PortalSystemComment extends PortalSystemBase
{
	public function doParse()
	{
		global $dcs,$ctl;
		switch($this->action){
			case 'reply':
				$this->theme->setModule('form');
				$this->doReply();
				break;
			case 'handle':
				$this->doHandles();
				break;
			default:
				if(!$this->action) $this->action='list';
				$this->theme->setModule($this->action);
				$this->doList();
				break;
		}
	}
	
	//####################
	protected function doReply()
	{	
		global $dcs,$ctl;
		$sqlQuery=$this->FieldID.'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeDat=DB::queryTree($sql);
		if ($this->treeDat->getCount()<1) {
			$this->doMessages('',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->treeDat->addItem('trans_tim_last',$this->treeDat->getItem($this->TablePX.'trans_tim'));
		$this->treeDat->addItem($this->TablePX.'trans_tim',DCS::timer());
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->pages->setFormTree($this->treeDat);
		$this->pages->loadForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.reply'),$this->treeData,$sqlQuery);
				$this->doMessages('',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
				
			}
		}
		$this->doPagesFormParse();
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
	  	//debugTable($this->box->tableData);
	}
	protected function doListFilter(&$tableData)
	{
		UaExtendManage::appendInfo($tableData);
		$tableData->doAppendFields('channel.name,user');
		$tableData->doBegin();
		while($tableData->isNext()){
			$channelname=$tableData->getItemValue('channel');
			$tableData->setItemValue('channel.name',$channelname);
			$_user=$tableData->getItemValue($this->TablePX.'realname');
			if(len($_user)<1) $_user=$tableData->getItemValue('username');
			$tableData->setItemValue('user',$_user);
		}
	}
}
?>