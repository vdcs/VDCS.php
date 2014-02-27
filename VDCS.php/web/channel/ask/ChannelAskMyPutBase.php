<?
class ChannelAskMyPutBase extends ChannelAskMyBaseRoot
{
	
	public function doLoad()
	{
		if($this->_p_=='put'){
			$this->theme->setPage('my.'.$this->_p_);
		}
		
		$this->TableFieldsAdd='classid,uuid,q_topic,q_subtopic,q_icon,q_summary,q_remark,q_reward_money,q_reward_emoney,q_reward_points,q_timed_day,q_timed_expire,sp_ip,sp_agent,sp_code,q_state,q_status,q_tim';
		$this->TableFieldsEdit='classid,q_topic,q_subtopic,q_icon,q_summary,q_remark,q_reward_money,q_reward_emoney,q_reward_points,q_timed_day,q_timed_expire,sp_ip,sp_agent,sp_code';
		
		$this->loadPages();
		
		$this->loadVcode($this->_chn_.'.'.$this->_p_);
		$this->loadShield();
		
	}
	
	protected function doLoadAnswer()
	{
		global $cfg;
		$this->AnswerNames=$cfg->vp('answer:names');
		$this->AnswerTableName=$cfg->vp('answer:table.name');
		$this->AnswerTablePX=$cfg->vp('answer:table.px');
		$this->AnswerTableFieldsAdd='rootid,uuid,a_topic,a_icon,a_reference,a_summary,a_remark,sp_ip,sp_agent,sp_code,a_state,a_status,a_tim';
		$this->AnswerTableFieldsEdit='a_topic,a_icon,a_reference,a_summary,a_remark,sp_ip,sp_agent,sp_code,a_state,a_status,a_tim';
	}
	
	
	public function doThemeCache()
	{
		if($this->view) $this->theme->output=$this->view->toDTMLCache($this->theme->output,'cpo.view');
	}
	
}
?>