<?php
class ChannelForumQueryX extends ChannelForumBaseX
{
	public function parseComment()
	{
		$rootid=queryi('rootid');
		if(!$rootid){
			$this->setStatus('failed');
			$this->setMessage('查询对象不存在');
			return;
		}
		$this->tableList=newTable();
		$params=[];
		$params['query']='rootid='.DB::q($rootid,1).' and type>0';
		$params['order']='d_id desc';
		$this->tableList=ForumQuery::querierData('',$this->p,$params);
		$this->setTable($this->tableList);
		if(!$total) $total=$this->tableList->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();
		
		$this->setSucceed();
	}
}

?>
