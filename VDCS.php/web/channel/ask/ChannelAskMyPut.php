<?
class ChannelAskMyPut extends ChannelAskMyPutBase
{
	
	public function doParse()
	{
		switch($ctl->action){
			case 'edit':
				$cfg->setTitle('sub','编辑'.$this->Names);
				$this->doQuestionEdit();
				break;
			default:
				$cfg->setTitle('sub','提交'.$this->Names);
				$this->doQuestionAdd();
				break;
		}
	}
	
	protected function doQuestionAdd()
	{
		$ctl->treeData->addItem('sp_code',1);
		$ctl->treeData->addItem('q_timed_day',7);
		
		if(!isFormPost()) return;
		
		initUsere();
		
		$classid=posti('classid');
		$ctl->treeData->addItem('classid',$classid);
		
		$ctl->treeData->addItem('q_topic',post('q_topic',200));
		$ctl->treeData->addItem('q_subtopic',post('q_subtopic',250));
		$ctl->treeData->addItem('q_icon',posti('q_icon'));
		$ctl->treeData->addItem('q_summary',post('q_summary',250));
		$ctl->treeData->addItem('q_remark',post('q_remark',10000));
		
		$reward_money=postn('q_reward_money');
		$reward_emoney=postn('q_reward_emoney');
		$reward_points=postn('q_reward_points');
		$ctl->treeData->addItem('q_reward_money',$reward_money);
		$ctl->treeData->addItem('q_reward_emoney',$reward_emoney);
		$ctl->treeData->addItem('q_reward_points',$reward_points);
		$timed_day=postn('q_timed_day');
		$timed_expire=postn('q_timed_expire');
		$ctl->treeData->addItem('q_timed_day',$timed_day);
		$ctl->treeData->addItem('q_timed_expire',$timed_expire);
		
		if($classid<1) $ctl->e->addItem('问题 分类 不能为空!');
		if(len($ctl->treeData->getItem('q_topic'))<1 || len($ctl->treeData->getItem('q_remark'))<1) $ctl->e->addItem('提问的 标题 和 内容 不能为空!');
		
		$_remard=$cfg->cfgi('put.reward');
		if($_remard>0 && !$ctl->e->isCheck()){
			if($reward_money<0.01 && $reward_emoney<0.01 && $reward_points<1) $ctl->e->addItem('提问 至少设置一项悬赏!');
		}
		if(!$ctl->e->isCheck()){
			if($reward_money>0 || $reward_emoney>0 || $reward_points>0){
				$usere->minusValue('money',$reward_money);
				$usere->minusValue('emoney',$reward_emoney);
				$usere->minusValue('points',$reward_points);
				if(!$usere->isTest()) $ctl->e->addItem('您的当前分值不足，悬赏值过高!');
			}
		}
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'q_topic,q_subtopic,q_summary,q_remark');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$ctl->treeData->addItem('uuid',$this->ua->id);
			$ctl->treeData->addItem('sp_ip',DCS::ip());
			$ctl->treeData->addItem('sp_agent',DCS::agent());
			$ctl->treeData->addItem('q_state',0);
			$ctl->treeData->addItem('q_status',$cfg->cfgi('put.status'));
			$ctl->treeData->addItem('q_tim',DCS::timer());
			
			$sql=DB::sqlInsert($this->TableName,$this->TableFieldsAdd,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			//用户 更新分值
			if($cfg->cfgi('put.premium')>0){
				$usere->plusValue('emoney',$cfg->cfgi('put.premium.emoney'));
				$usere->plusValue('points',$cfg->cfgi('put.premium.points'));
			}
			$usere->doUpdate();
			
			$this->doMessage('this','succeed:提交成功','您已成功提交了'.$this->Names.'，请耐心等待网友的解答！',$url);
		}
	}
	
	protected function doQuestionEdit()
	{
		if(!isFormPost()) return;
		
	}
	
}
?>