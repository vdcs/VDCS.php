<?php
class ChannelAccountAssetsRemitX extends ChannelAccountBaseX
{
	//转账
	public function parseApply(){
		global $ctl;
		if(!$this->ready(true)) return;
		$this->doFormData();
		if($ctl->e->isCheck()){
			$this->doRaiseError();
		}else{
			$_status=UcaRemit::create($this->ua,$this->treeData);
			$_status=1;
			if($_status==1){
				$this->setStatus('succeed');
			}else{
				$this->setStatus('failed');
			}
		}
	}
	
	protected function doFormData(){
		$money=postn('money');
		$bankname=post('bankname');
		$bankno=post('bankno');
		$summary=post('summary',250);
		
		if(!$money || $money<=0) $this->addError('金额不能为空 或 不符合规则');
		if(!$bankname) $this->addError('转账银行不能为空');
		$this->addData('money',$money);
		$this->addData('type',$bankname);
		$this->addData('bankno',$bankno);
		$this->addData('summary',$summary);
	}
	
	//转账记录
	public function parseList()
	{
		$params=[];
		$params['listnum']=queryi('listnum');
		$params['order']='id desc';
		$this->tableList=UcaRemit::querier($this->ua,$this->p,$params);
		$this->doListFilter($this->tableList);
		$this->setTable($this->tableList);
		$this->addVarPaging();
		$this->setSucceed();
	}
	protected function doListFilter(&$tableData)
	{
		/*
		UaExtend::appendInfo($tableData);
		*/
	}
}
?>