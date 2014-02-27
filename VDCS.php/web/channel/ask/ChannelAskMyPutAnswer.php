<?
class ChannelAskMyPutAnswer extends ChannelAskMyPutBase
{
	
	public function doParse()
	{
		global $cfg;
		$this->doLoadRoot();
		if($this->isRoot){
			$this->doLoadAnswer();
			switch($this->action){
				case 'edit':
					$cfg->setTitle('sub','编辑'.$this->AnswerNames);
					$this->doAnswerEdit();
					break;
				default:
					$cfg->setTitle('sub','提交'.$this->AnswerNames);
					$this->theme->setModule('answer');
					$this->doAnswerAdd();
					break;
			}
		}
	}
	
	protected function doAnswerAdd()
	{
		global $cfg,$ctl;
		
		//##########
		if($this->view->getData('state')==1){
			$this->doMessage('this','info:系统提示','问题已经解决，感谢您的支持和参与！',$url);
			return;
		}
		//##########
		
		$ctl->treeData->addItem('sp_code',1);
		
		if(!isFormPost()) return;
		
		initUsere();
		
		$ctl->treeData->addItem('a_topic',post('a_topic',200));
		$ctl->treeData->addItem('a_icon',postInt('a_icon'));
		$ctl->treeData->addItem('a_reference',post('a_reference',250));
		$ctl->treeData->addItem('a_summary',post('a_summary',250));
		$ctl->treeData->addItem('a_remark',post('a_remark',10000));
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'q_topic,q_subtopic,q_summary,q_remark');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$ctl->treeData->addItem('rootid',$this->rootid);
			$ctl->treeData->addItem('uuid',$this->ua->id);
			$ctl->treeData->addItem('sp_ip',DCS::ip());
			$ctl->treeData->addItem('sp_agent',DCS::agent());
			$ctl->treeData->addItem('a_state',0);
			$ctl->treeData->addItem('a_status',$cfg->cfgi('puta.status'));
			$ctl->treeData->addItem('a_tim',DCS::timer());
			
			$sql=DB::sqlInsert($this->AnswerTableName,$this->AnswerTableFieldsAdd,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			//问题 更新回复数
			$sql=$cfg->chn->getSQLStruct('answer.update');
			if(len($sql)<1) $sql=DB::sqlUpdate($this->TableName,'total','q_total_answer=q_total_answer+1','q_id={$id}');
			$sql=rd($sql,'id',$this->rootid);
			//debugx($sql);
			DB::exec($sql);
			//问题 更新状态
			$sql=DB::sqlUpdate($this->TableName,'total','q_state=2','q_id={$id}');
			$sql=rd($sql,'id',$this->rootid);
			//debugx($sql);
			DB::exec($sql);
			
			//用户 更新分值
			if($cfg->cfgi('puta.premium')>0){
				$usere->plusValue('emoney',$cfg->cfgi('puta.premium.emoney'));
				$usere->plusValue('points',$cfg->cfgi('puta.premium.points'));
			}
			$usere->doUpdate();
			
			$this->doMessage('this','succeed:提交成功','您已成功提交了'.$this->AnswerNames.'，感谢您的支持和参与！',$url);
		}
	}
	protected function doAnswerEdit()
	{
		if(!isFormPost()) return;
		
	}
	
}
?>