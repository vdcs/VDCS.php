<?
class ChannelPassportResetPasswordX extends ChannelPassportBaseX
{
	
	public function parse()
	{
		$this->loadVcode('channel');
		$this->parseReset();
	}
	public function parseReset()
	{
		if(!$this->ready()) return;	//true
		
		$checknext=!$this->isErrorCheck();
		
		$field_hashcode='hashcode';
		$hashcode=query($field_hashcode);
		if(!$hashcode) $hashcode=post($field_hashcode);
		if($hashcode) $hashval=utilCoder::toBase64Decode($hashcode);
		if($hashval) $treeVal=utilString::toTree($hashval,';','=');
		//debugTree($treeVal);
		$this->addVar('hashcode',$hashcode);
		if($checknext){		//check hashcode
			if(!$hashval || !$treeVal) $this->addError('无效的请求数据(hashcode)！',$field_hashcode,'no');
		}
		
		if($treeVal){
			$uid=$treeVal->getItemInt('uid');
			$email=$treeVal->getItem('email');
			$tim=$treeVal->getItemInt('tim');
			$sign=$treeVal->getItem('sign');
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){		//check hashcode expire
			//debugx((DCS::timer()-$tim));
			if((DCS::timer()-$tim)>3600) $this->addError('数字签名已过期！',$field_hashcode,'expire');
		}
		
		$field_password='password';
		$password=post($field_password);
		if(!$password) $password=query($field_password);
		//$password='4621d373cade4e83';
		$field_password2='password2';
		$password2=post($field_password2);
		if(!$password2) $password2=query($field_password2);
		//$password2='4621d373cade4e83';
		$checknext=!$this->isErrorCheck();
		if($checknext){		//check password
			if(!utilCheck::isPassword($password)) $this->addError('重置密码 为空或不符合规则！',$field_password,'no');
			else if($password!=$password2) $this->addError('重置密码 与 确认密码 不一致！',$field_password2,'no');
		}
		
		$this->vcodeFormCheck();
		
		$checknext=!$this->isErrorCheck();
		if($checknext){		//check uid
			if(!$uid) $this->addError('无效的请求数据(uid)！','uid','no');
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){	//check uid
			$this->uam=&Ua::instance($this->UARC,'manage');
			$this->uam->loadData($uid);
			if(!$this->uam->isData()){
				$this->addError('UID 不存在！','uid','noexist');
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$upivotal=new UcPivotal();
			$upivotal->setURC($this->uam->rc);
			$upivotal->setUID($this->uam->id);
			$upivotal->init();
			$upivotal->loadData();
			$secret=$upivotal->getData('temp1');
			
			$signval=$secret.','.$this->uam->id.','.$email;
			$signcode=utilCoder::toMD5($signval);
			//debugx($signcode.' = '.$signval);
			if($signcode!=$sign){
				$this->addError('无效的数字签名！','signcode','invalid');
			}
		}
		
		if($this->isRaiseError()) return;
		
		$passwordi=utilCoder::toMD5i($password);
		//debugx($password.'='.$passwordi);
		$upivotal->sets('temp1='.DB::q('',1).',password='.DB::q($passwordi,1));
		unset($upivotal);
		
		$this->setStatus('succeed');
	}
	
}
