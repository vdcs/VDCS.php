<?php
class ChannelAccountAssetsRefundX extends ChannelAccountBaseX
{
	//提现记录
	public function parseList()
	{
		$parme=array();
		$parme['order']='id desc';
		$this->tableList=UcaRefund::querier($this->ua,$this->p,$parme);
		$this->setTable($this->tableList);
		$this->addVarPaging();
		$this->setSucceed();
	}
	//提现
	public function parseApply(){
		global $ctl;
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		$this->checkAccount();
		if($ctl->e->isCheck()){
			$this->doRaiseError();
		}else{
			$_status=UcaRefund::create($this->ua,$this->treeData);
			$_status=1;
			if($_status==1){
				$this->setStatus('succeed');
			}else{
				$this->setStatus('failed');
			}
		}
	}
	
	protected function checkAccount(){
		global $dcs,$cfg,$ctl;
		$payee=posts('payee');
		$money=postn('money');
		$bank=posts('bank');
		$bankno=posts('bankno');
		$summary=posts('summary');
		
		//账户余额
		$moneys=UcaMoney::getRest($this->ua);
		
		//if(!$payee) $this->addError('提现人不能为空');
		if(!$money || $money<=0) $this->addError('金额不能为空 或 不符合规则');
		if($moneys<$money) $this->addError('账户余额不足');
		if(!$bank) $this->addError('退款银行名称不能为空');
		if(!$bankno) $this->addError('账号不能为空');
		
		$this->addData('payee',$payee);
		$this->addData('money',$money);
		$this->addData('bank',$bank);
		$this->addData('bankno',$bankno);
		$this->addData('summary',$summary);
	}
	
}
?>