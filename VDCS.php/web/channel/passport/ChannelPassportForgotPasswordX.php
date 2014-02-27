<?
class ChannelPassportForgotPasswordX extends ChannelPassportBaseX
{
	
	public function parse()
	{
		$this->loadVcode('channel');
		$this->parseEmail();
	}
	
	public function parseEmail()
	{
		if(!$this->ready()) return;	//true
		$checknext=!$this->isErrorCheck();
		$field_email='email';
		$email=post($field_email);
		if(!$email) $email=query($field_email);
		$this->addVar('email',$email);
		if($checknext){		//check email
			if(!utilCheck::isEmail($email)) $this->addError('电子邮件 为空或不符合规则！',$field_email,'no');
		}
		/*
		$field_mobile='mobile';
		$mobile=post($field_mobile);
		$this->addVar('mobile',$mobile);
		if($checknext){		//check mobile
			if(!utilCheck::isMobile($mobile)) $this->addError('手机号码 为空或不符合规则！',$field_mobile,'no');
		}
		*/
		
		$this->vcodeFormCheck();
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$this->uam=&Ua::instance($this->ua->rc,'manage');
			$this->uam->loadData('email',$email);
			if(!$this->uam->isData()){
				$this->addError('电子邮件 不存在！',$field_email,'noexist');
			}
		}
		if($this->isRaiseError()) return;
		
		$upivotal=new UcPivotal();
		$upivotal->setURC($this->uam->rc);
		$upivotal->setUID($this->uam->id);
		$upivotal->init();
		
		$secret=utilCode::getRand(10,2);
		//debugx($secret);
		$upivotal->sets('temp1='.DB::q($secret,1));
		$signval=$secret.','.$this->uam->id.','.$email;
		$signcode=utilCoder::toMD5($signval);
		//debugx($signcode.' = '.$signval);
		$hashval='uid='.$this->uam->id.';email='.$email.';tim='.DCS::timer().';sign='.$signcode;
		$hashcode=utilCoder::toBase64Encode($hashval);
		$url_serve=$this->cfg->toLinkURL('pam','p=reset&m=password&hashcode='.$hashcode);
		$url_serve=DCS::url($url_serve);
		//debugx($url_serve);
		
		$vars=array();
		$vars['uid']=$this->uam->id;
		$vars['names']=$this->uam->getNames();
		$vars['email']=$email;
		$vars['mobile']=$this->uam->getData('mobile');
		$vars['realname']=$this->uam->getNames();
		$vars['url.serve']=$url_serve;
		
		$opt['tpl']='passport.forgot.password';
		$opt['vars']=$vars;
		$send_status=Sender::sendMail($email,'tpl','tpl',$opt);
		//debugx('send_status='.$send_status);
		$this->addVar('send','mail');
		if($send_status<1){
			$this->setStatus('nosend');
			$this->addVar('sendmail',$send_status);
			return;
		}
		
		$this->setSucceed();
	}
	
}
