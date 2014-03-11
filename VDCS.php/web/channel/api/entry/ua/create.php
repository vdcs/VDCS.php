<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parseCreate();
	}
	public function parseCreate()
	{
		$this->uam=&Ua::instance(APP_UA,'manage');
		$_id=0;
		$_name=request('name');
		$_email=request('email');
		$_password=request('password');

		if(len($_name)>0 && !utilCheck::isName($_name) && !utilCheck::isEmail($_name)) $this->addError($this->getVar('ua.names').'信息 不符合规则','name','check');
		if(len($_email)<1 && ins($_name,'@')>0) $_email=$_name;
		if(len($_email)>0 && !utilCheck::isEmail($_email)) $this->addError('电子邮件 不符合规则','email','check');
		if(len($_name)<1 && len($_email)<1) $this->addError('登录'.$this->getVar('ua.names').' 不能为空','account','no');

		$_query=$_email?$this->ua->sqlQuery('email',$_email):UaUA::toQueryI($this->ua,$_name);
		$_id=$this->ua->queryField('id',$_query);
		if($_id>0){
			$this->addError(''.$_email.' 已存在','account','check');
		}

		if($this->isRaiseError()) return;

		$tData=newTree();
		$tData->addItem('name', $_name);
		$tData->addItem('email', $_email);
		$tData->addItem('password', $_password);
		//$tData->addItem('mobile', $_mobile);

		$newid=-1;
		$this->uam->doCreate($tData,$newid);

		$this->addVarTree($this->ua->getQueryTree($_query),'ua.');

		$this->setSucceed();
		/*
		//##########
		$checknext=!$this->isErrorCheck();
		if($checknext){
			//debugx($_email);
			$_query=$_email?$this->ua->sqlQuery('email',$_email):UaUA::toQueryI($this->ua,$_name);
			//debugx($_query);
			$_id=$this->ua->queryField('id',$_query);
			$this->ua->setID($_id);
			if($_id<1){
				$this->addError($this->getVar('ua.names').'信息 不存在或不符合规则','account','check');		//$this->getVar('ua.names').'信息
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
		*/
	}
	
}
