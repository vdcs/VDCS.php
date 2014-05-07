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
		$_account=request('account');
		$_password=request('password');
		$_names=requestx('names');
		
		if(utilCheck::isEmail($_account)) $_field='email';
		elseif(utilCheck::isMobile($_account)) $_field='mobile';
		elseif(utilCheck::isName($_account)) $_field='name';
		//else $_field='uid';
		
		if(!$_field) $this->addError('账号不合法','account','check');
		else $_query=UaUA::toQueryI($this->ua,$_account);//$_email?$this->ua->sqlQuery('email',$_email):
		
		$_id=$this->ua->queryField('id',$_query);
		if($_id>0){
			$this->addError('账号 已存在','account','check');//$_email
		}
		
		//check names
		if($_names){
			$_query_names='names='.DB::q($_names,1);
			$_id=$this->ua->queryField('id',$_query_names);
			if($_id>0){
				$this->addError('名称 已存在','account','check');//$_email
			}
		}
		

		if($this->isRaiseError()) return;

		$tData=newTree();
		$tData->addItem($_field, $_account);
		$tData->addItem('password', $_password);
		$tData->addItem('names', $_names);
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
