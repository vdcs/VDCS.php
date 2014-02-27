<?php
class PagePortal extends ManagePortalBaseX
{
	use PortalSystemCommentRef;
	
	
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
	
	protected function parseReply()
	{
		$_status=0;
		$id=posti('id');
		$trans_message=posts('trans_message');
		$trans_name=posts('trans_name');
		if(!$trans_name) $trans_name=$this->ma->name;
		$trans_date=posts('trans_date');
		$trans_tim=strtotime($trans_date);
		if(!$trans_date) $trans_tim=DCS::timer();
		
		if(!$id){
			$this->setStatus('failed');
			$this->setMessage('缺少ID');
			return;	
		}
		
		if(len($trans_message)<1){
			$this->setStatus('failed');
			$this->setMessage('回复内容不能为空');
			return;
		}
		$tData=newTree();
		//$tData->addItem('id',$id);
		$tData->addItem('trans_name',$trans_name);
		$tData->addItem('trans_message',$trans_message);
		$tData->addItem('trans_tim',$trans_tim);
		$tData->addItem('trans',1);
		$sqlQuery=$this->FieldID.'='.$id;
		$_status=DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$tData,$sqlQuery);
		
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
			$this->setMessage('对不起，你的咨询回答提交失败');
		}
	}
	
}
?>