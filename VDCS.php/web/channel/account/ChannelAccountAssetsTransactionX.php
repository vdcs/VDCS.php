<?php
class ChannelAccountAssetsTransactionX extends ChannelAccountBaseX
{

	public function parseTransaction(){
		$params=array();
		$params['order']='id desc';
		$listnum=queryi('listnum');
		if(!$listnum) $listnum=10;
		$params['listnum']=$listnum;
		$this->tableList=UcaTransaction::querier($this->ua,$this->p,$params);
		$this->setTable($this->tableList);
		$this->addVar('total',$total);
		$this->addVarPaging();//在treeVar中增加相关的分页信息
		$this->setSucceed();
	}
}
?>