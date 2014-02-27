<?
class ChannelAskMyBase extends ChannelAskBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	/*use WebPortalRefControl{
		WebPortalRefControl::doMessage as public doMessageBase;
	}
	*/
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doAuth()
	{
		$this->doAuthed();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadPre()
	{
		$this->theme->setSubdir('my');
		
		$this->cfg->setTitle('sub',$this->cfg->v('title.'.$this->_p_.'.'.$this->_m_));
		
		$this->Names=$this->cfg->vp('question:names');
		$this->TableName=$this->cfg->vp('question:table.name');
		$this->TablePX=$this->cfg->vp('question:table.px');
		
		$this->AnswerNames=$this->cfg->vp('answer:names');
		$this->AnswerTableName=$this->cfg->vp('answer:table.name');
		$this->AnswerTablePX=$this->cfg->vp('answer:table.px');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doMessage($type,$state,$msg='',$url='')
	{
		$this->doMessageBase($type,$state,$msg,$url);
		$this->theme->setPage('my');
		$this->theme->setModule('handle');
	}
	
}
?>