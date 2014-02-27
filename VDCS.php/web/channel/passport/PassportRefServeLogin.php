<?
trait PassportRefServeLogin
{
	use PassportRefServeBase,PassportRefAuth;
	
	
	/*
	########################################
	########################################
	*/
	public function doParseServe()
	{
		global $cfg;
		if($cfg->num('islogin')!=1){
			$this->setStatus('pause');
			return false;
		}
		if($this->ua->isLogin()){
			UaUA::doLoginAppend($this,$this->ua);
			$this->setStatus('already');
			return true;
		}
		
		$this->doParseLogin();
	}
	
	protected function doParseLogin()
	{
		$secureCode='';
		$this->treeData->addItem('issecurecode','');
		$this->treeData->addItem('error_message','');
		$this->treeData->addItem('name','');
		$this->treeData->addItem('email','');
		$this->treeData->addItem('password','');
		$this->treeData->addItem('secure_code','');
		//('url.back',$this->ua->getURL('back',1));
		
		if(!$this->ready()) return;	//true
		
		$this->uid=0;
		$_name=request('name');
		if(!$_name){
			$_name=request('username');
			$this->treeData->addItem('username',$_name);
		}
		$_email=request('email');
		//debugx('name='.$_name.', email='.$_email);
		$this->treeData->addItem('name',$_name);
		$this->treeData->addItem('email',$_email);
		$this->treeData->addItem('password',request('password'));
		$this->treeData->addItem('remember_info',request('remember_info'));
		$this->treeData->addItem('_encrypt_timer',request('_encrypt_timer'));
		$this->treeData->addItem('secure_code',request('secure_code'));
		
		//debugx('DCS::timer()='.DCS::timer());
		//debugx('password='.$this->treeData->getItem('password').', encrypttimer='.$this->treeData->getItem('_encrypt_timer'));
		
		$this->extendData();

		$_name=$this->treeData->getItem('name');
		$_email=$this->treeData->getItem('email');
		$secureCode=$this->treeData->getItem('secure_code');
		if($secureCode) $this->treeData->addItem('issecurecode','yes');

		if(len($_name)>0 && !utilCheck::isName($_name) && !utilCheck::isEmail($_name)) $this->addError($this->getVar('ua.names').'信息 不符合规则','name','check');
		if(len($_email)<1 && ins($_name,'@')>0) $_email=$_name;
		if(len($_email)>0 && !utilCheck::isEmail($_email)) $this->addError('电子邮件 不符合规则','email','check');
		if(len($_name)<1 && len($_email)<1) $this->addError('登录'.$this->getVar('ua.names').' 不能为空','account','no');
		if(!utilCheck::isPassword($this->treeData->getItem('password'))) $this->addError('登录密码 为空或不符合规则','password','check');
		//##########
		if($secureCode){
			if(!utilCheck::isPassword($this->treeData->getItem('secure_code'))){
				$this->addError('安全密码 为空或不符合规则','secure_code','check');
			}
			else{
				if(utilCoder::toMD5i($this->treeData->getItem('secure_code'))!=$secureCode) $this->addError('安全密码 有错误存在','secure_code','check');
			}
		}
		
		//$this->vcodeFormCheck();
		
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			//debugx($_email);
			$_query=$_email?$this->ua->sqlQuery('email',$_email):UaUA::toQueryI($this->ua,$_name);
			//debugx($_query);
			$this->uid=$this->ua->queryField('id',$_query);
			$this->ua->setID($this->uid);
			if($this->uid<1){
				$this->addError($this->getVar('ua.names').'信息 不存在或不符合规则','account','check');		//$this->getVar('ua.names').'信息
			}
		}
		
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$this->extendCheck();
		}
		
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			//debugx($this->treeData->getItem('password'));
			//debugx(utilCoder::toMD5i($this->treeData->getItem('password')));
			$this->ua->setData('_encrypt_timer',$this->treeData->getItem('_encrypt_timer'));
			$this->ua->setData('_password',utilCoder::toMD5i($this->treeData->getItem('password')));
			$this->ua->setData('_remember',$this->treeData->getItem('remember_info'));
			$this->ua->doLoginCheck(true);
			//debugx(utilCoder::toMD5i('test'));
			if(!$this->ua->isLogin()){
				$this->ua->setID(0);
				$this->addError('登录密码 不正确','password','error');
				$this->extendBad();
			}
		}
		
		if($this->isRaiseError()) return;
		
		$this->ua->dataLoader();
		UaUA::doLoginAppend($this,$this->ua);
		$this->extendSucceed();
		
		$this->setSucceed();
	}
	
	protected function extendData(){}
	protected function extendCheck(){}
	protected function extendBad(){}
	protected function extendSucceed(){}

}
?>