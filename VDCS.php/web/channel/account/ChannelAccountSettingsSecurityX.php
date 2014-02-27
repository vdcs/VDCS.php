<?
class ChannelAccountSettingsSecurityX extends ChannelAccountBaseX
{
	use ChannelRefSettingsModify;
	
	public function doLoad()
	{
		$this->loadVcode('channel');
	}
	
	protected function parsePassword()
	{
		if(!$this->ready()) return;	//true
		
		$this->initUaManage();
		
		$this->checkPasswordLogin();
		/*
		$this->addVar('abc','abc123');
		$tableAtt=VDCSDTML::getConfigTable('common.config/control/att');
		$this->setTable($tableAtt);
		*/
		$_password=request('password');
		$_password2=request('password2');
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if(len($_password)<4) $this->addError('密码 为空或长度小于4位！','password','no'); 
			else if(!utilCheck::isPassword($_password)){
				$this->addError('新密码 不符合规则','password','vcheck');
			}
			else{
				if($_password!=$_password2) $this->addError('新密码 和 重复密码 不一致','password2','error');
			}
		}
		
		$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$passwordi=utilCoder::toMD5i($_password);
		//debugx($_password.'='.$passwordi);
		$this->uam->mpivotal->sets('password='.DB::q($passwordi,1));
		$this->uam->doModifyPassword($_password);
		unset($this->uam);
		
		$this->setStatus('succeed');
	}
	
	protected function parseEmail()
	{
		if(!$this->ready()) return;	//true
		
		$this->initUaManage();
		
		$this->checkPasswordLogin();
		
		$field_email='email';
		$_email=request($field_email);
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if(len($_email)<5) $this->addError('电子邮箱 为空或不符合规则！',$field_email,'no');
			else if(!utilCheck::isEmail($_email) || !utilCheck::isEmailName(strstr($_email,'@',true)) || !utilCheck::isEmailName(substr(strstr($_email,'@'),1))){
				$this->addError('电子邮箱 不符合规则',$field_email,'no');
			}
			else{
				if($this->uam->isExistEmail($_email)) $this->addError('电子邮箱 已存在！',$field_email,'no');
			}
		}
		
		$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$this->uam->sets('{tpx}email='.DB::q($_email,1));
		unset($this->uam);
		
		$this->setStatus('succeed');
	}
	
	protected function parseQuestions()
	{
		if(!$this->ready()) return;	//true
		$this->initUaManage();
		
		//$this->checkPasswordLogin();
		
		$_question1=requestx('pivotal_safe_question1');
		$_answer1=requestx('pivotal_safe_answer1');
		$_question2=requestx('pivotal_safe_question2');
		$_answer2=requestx('pivotal_safe_answer2');
		$_question3=requestx('pivotal_safe_question3');
		$_answer3=requestx('pivotal_safe_answer3');
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if(len($_answer1)<3 || len($_answer2)<3 || len($_answer3)<3) $this->addError('请输入 安全问题 的 答案！');
			
			if(!$ctl->e->isCheck()){
				/*
				if(len($ctl->treeData->getItem('pivotal_safe_question'))>0){
					if(!utilCheck::isSecure($ctl->treeData->getItem('pivotal_safe_question'))) $this->addError('您要修改的 安全问题 不符合规则');
				}
				if(len($ctl->treeData->getItem('pivotal_safe_answer'))>0){
					if(!utilCheck::isSecure($ctl->treeData->getItem('pivotal_safe_answer'))) $this->addError('您要修改的 安全答案 不符合规则');
				}
				*/
			}
		}
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$ctl->treeData->addItem('pivotal_safe_question1',$_question1);
		$ctl->treeData->addItem('pivotal_safe_answer1',utilCoder::toMD5i($_answer1));
		$ctl->treeData->addItem('pivotal_safe_question2',$_question2);
		$ctl->treeData->addItem('pivotal_safe_answer2',utilCoder::toMD5i($_answer2));
		$ctl->treeData->addItem('pivotal_safe_question3',$_question3);
		$ctl->treeData->addItem('pivotal_safe_answer3',utilCoder::toMD5i($_answer3));
		debugTree($ctl->treeData);
		
		//########## pivotal
		if($this->uam->mpivotal->is()){
			$this->uam->mpivotal->doFormSave($ctl->treeData);
		}
		//##########
		
		$this->setStatus('succeed');
	}
	
}
?>