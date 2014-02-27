<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parseLogin();
	}
	public function parseLogin()
	{
		$_id=0;
		$_account=request('account');
		if($_account){
			if(utilCheck::isEmail($_account)){
				$_email=$_account;
			}
			else{
				$_name=$_account;
			}
		}
		else{
			$_name=request('name');
			$_email=request('email');
		}
		$_password=request('password');
		debugxx('account='.$_account.', name='.$_name.', email='.$_email.', password='.$_password.'');

		$ua_names='帐号';	//$this->getVar('ua.names')
		if(len($_name)>0 && !utilCheck::isName($_name) && !utilCheck::isEmail($_name) && !utilCheck::isMobile($_name)) $this->addError($ua_names.' 不符合规则','name','check');
		if(len($_email)<1 && ins($_name,'@')>0) $_email=$_name;
		if(len($_email)>0 && !utilCheck::isEmail($_email)) $this->addError('电子邮件 不符合规则','email','check');
		if(len($_name)<1 && len($_email)<1) $this->addError('登录'.$ua_names.' 不能为空','account','no');
		
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			//debugx($_email);
			$_query=$_email?$this->ua->sqlQuery('email',$_email):UaUA::toQueryI($this->ua,$_name);
			//debugx($_query);
			$_id=$this->ua->queryField('id',$_query);
			$this->ua->setID($_id);
			if($_id<1){
				$this->addError(''.$ua_names.' 不存在或不符合规则','account','check');
			}
		}
		
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$this->ua->setData('_password',utilCoder::toMD5i($_password));
			$islogin=UuPivotal::isLogin($this->ua,$_password);
			if(!$islogin){
				$this->ua->setID(0);
				$this->addError('登录密码 有错误存在','password','error');
			}
		}
		
		if($this->isRaiseError()) return;
		
		$this->ua->dataLoader();
		UaUA::doLoginAppend($this,$this->ua);

		$this->setSucceed();
	}
	
}
