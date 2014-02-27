<?
class ChannelAskMyChoose extends ChannelAskMyBaseRoot
{
	
	public function doLoad()
	{
		global $cfg,$ctl;
		
		$this->doLoadRoot();
		if($this->isRoot){
			if($this->view->getDataInt('uuid')!=$this->ua->id){
				$this->doMessage('this','error:系统提示','错误的数据提交！(user)',$url);
				return;
			}
			$this->id=queryi('id');
			$this->treeAnswer=$this->view->getAnswerTree(-1,$this->id);
			if($this->treeAnswer->getCount()<1){
				$this->doMessage('this','error:系统提示','错误的数据提交！(answer)',$url);
				return;
			}
			$this->isAnswer=true;
		}
		
		$cfg->setTitle('sub',$cfg->v('title.'.$this->_p_.'.'.$this->_m_));
		
		$this->loadPages();
		
		$this->loadVcode($this->_chn_.'.'.$this->_p_);
	}
	
	public function doParse()
	{
		if(!$this->isRoot || !$this->isAnswer) return;
		global $cfg,$ctl;
		
		if(!isFormPost()) return;
		
		$ctl->treeData->addItem('a_comment',post('a_comment',250));
		
		$reward_money=$this->view->getDataNum('reward_money');
		$reward_emoney=$this->view->getDataNum('reward_emoney');
		$reward_points=$this->view->getDataNum('reward_points');
		
		//问题 更新状态
		$sql=DB::sqlUpdate($this->TableName,'state','q_state=2','q_id={$id}');
		$sql=rd($sql,'id',$this->rootid);
		//debugx($sql);
		DB::exec($sql);
		
		//回答 更新状态
		$ctl->treeData->addItem('a_award_money',$reward_money);
		$ctl->treeData->addItem('a_award_emoney',$reward_emoney);
		$ctl->treeData->addItem('a_award_points',$reward_points);
		$ctl->treeData->addItem('a_comment_tim',DCS::timer());
		$ctl->treeData->addItem('a_state',1);
		$ChooseFields='a_award_money,a_award_emoney,a_award_points,a_comment,a_comment_tim,a_state';
		$sql=DB::sqlUpdate($this->AnswerTableName,$ChooseFields,$ctl->treeData,'a_id={$id}');
		$sql=rd($sql,'id',$this->id);
		//debugx($sql);
		DB::exec($sql);
		
		//用户 更新分值
		$uuid=$this->treeAnswer->getItemInt('uuid');
		if($uuid>0){
			initUsere();
			$usere->setID($uuid);
			$usere->plusValue('money',$reward_money);
			$usere->plusValue('emoney',$reward_emoney);
			$usere->plusValue('points',$reward_points);
			$usere->doUpdate();
		}
		
		$this->doMessage('this','succeed:操作成功','您已成功选择答案！同时将您的悬赏分值转帐给解答人，并结束提问.',$url);
	}
	
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		if($this->view) $this->theme->output=$this->view->toDTMLCache($this->theme->output,'cpo.view');
		$this->theme->doCacheFilterTree('answer','cpo.treeAnswer');
	}
	
}
?>