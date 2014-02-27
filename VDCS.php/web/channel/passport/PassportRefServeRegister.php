<?
trait PassportRefServeRegister
{
	use PassportRefServeBase,PassportRefAuth;
	
	
	/*
	########################################
	########################################
	*/
	public function doParseServe()
	{
		if($this->cfg->num('isregister')!=1){
			$this->setStatus('pause');
			return false;
		}
		
		$this->dcode_is=false;
		$this->dcode_module='register';
		$this->loadVcode('channel');
		$this->doParseRegister();
	}
	
	public function doParseRegister()
	{
		$this->uam=&Ua::instance($this->UURC,'manage');
		
		$this->doFormDataInit();
		
		if(!$this->ready(true)) return;
	
		$this->doFormDataGet();
		$this->doFormDataCheck();
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$this->doFormDataFilter();
		$this->doParseDisposeCreate();
		$this->doParseDisposeSucceed();
		
		$this->setStatus('succeed');
	}
	
	protected function doParseDisposeCreate()
	{
		$newid=-1;
		//$newid=$this->uam->newID();
		$this->uam->doCreate($this->treeData,$newid);
		//$this->uam->id=10002;
		$this->id=$this->uam->id;
		//##########
		//$this->uam->loadData();
		//$this->uam->initData(false);
		//##########
		$this->ua->setID($this->id);
		$this->ua->setData('_password',utilCoder::toMD5i($this->treeData->getItem('password')));
		$this->ua->doLoginCheck(true);
		//debugx(utilCoder::toMD5i('test'));
		if($this->ua->isLogin()){
			$this->doVarAppend();
		}
	}
	protected function doParseDisposeSucceed(){}
	
	protected function doVarAppend()
	{
		$this->addVar('uurc',$this->ua->rc);
		$this->addVar('uuid',$this->ua->getData('id'));
		$this->addVar('uid',$this->ua->getData('id'));
		$this->addVar('id',$this->ua->getData('id'));
		$this->addVar('no',$this->ua->getData('no'));
		$this->addVar('name',$this->ua->getData('name'));
		$this->addVar('email',$this->ua->getData('email'));
		$this->addVar('names',$this->ua->getData('names'));
		$this->addVar('backurl',$this->ua->getURL('referer'));
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doFormDataInit()
	{
		$this->treeData->addItem('safe_question',0);
		$this->treeData->addItem('gender',0);
		$this->treeData->addItem('_agreement','yes');
	}
	
	protected function doFormDataGet()
	{
		$this->treeData->addItem('name',post('name',50));
		$this->treeData->addItem('email',toLower(post('email',100)));
		$this->treeData->addItem('email_dcode',post('email_dcode',10));
		$this->treeData->addItem('mobile',post('mobile',11));
		$this->treeData->addItem('mobile_dcode',post('mobile_dcode',10));
		$this->treeData->addItem('idcard',post('idcard',50));
		
		$this->treeData->addItem('gender',posti('gender'));
		$birthday=post('birthday',10);
		if(!utilCheck::isDate($birthday)) $birthday='';
		$this->treeData->addItem('birthday',$birthday);
		
		$this->treeData->addItem('names',post('names',50));
		$this->treeData->addItem('location',post('location',50));
		$this->treeData->addItem('marks',post('marks',50));
		$this->treeData->addItem('sign',post('sign',200));
		
		$this->treeData->addItem('password',post('password',20));
		$this->treeData->addItem('password2',post('password2',20));
		$this->treeData->addItem('safe_question',post('safe_question'));
		$this->treeData->addItem('safe_answer',post('safe_answer',20));
		
		$this->treeData->addItem('names',post('names',50));
		$this->treeData->addItem('location',post('location',50));
		$this->treeData->addItem('marks',post('marks',50));
		$this->treeData->addItem('prop1',post('prop1',250));
		$this->treeData->addItem('prop2',post('prop2',250));
		$this->treeData->addItem('prop3',post('prop3',250));
		$this->treeData->addItem('prop4',post('prop4',250));
		$this->treeData->addItem('prop5',post('prop5',250));
		
		$timezone=posti('timezone');	//post('timezone',20)
		if($timezone>0) $this->treeData->addItem('timezone',$timezone);
		
		// im begin
		$this->treeData->addItem('im',post('im',50));
		$this->treeData->addItem('qq',post('qq',20));
		$this->treeData->addItem('msn',post('msn',100));
		$this->treeData->addItem('im1',$this->treeData->getItem('qq'));
		$this->treeData->addItem('im2',$this->treeData->getItem('msn'));
		$this->treeData->addItem('im3',post('im3',100));
		$this->treeData->addItem('im4',post('im4',100));
		$this->treeData->addItem('im5',post('im5',100));
		// im end
		
		$this->treeData->addItem('company',post('company',200));
		$this->treeData->addItem('url',post('url',200));
		$this->treeData->addItem('address',post('address',200));
		$this->treeData->addItem('postcode',post('postcode',20));
		$this->treeData->addItem('phone',post('phone',50));
		$this->treeData->addItem('fax',post('fax',50));
		
		$this->treeData->addItem('summary',post('summary',250));
		
		$this->treeData->addItem('area_country',post('area_country',50));
		$this->treeData->addItem('area_province',post('area_province',50));
		$this->treeData->addItem('area_city',post('area_city',50));
		
		$this->treeData->addItem('_issafe',post('_issafe'));
		$this->treeData->addItem('_isinfo',post('_isinfo'));
		$_agreement=post('_agreement');
		if(len($_agreement)<1) $_agreement=posts('_agreement');
		$this->treeData->addItem('_agreement',$_agreement);
		
		$this->doFormDataGetExtend();
	}
	protected function doFormDataGetExtend(){}
	
	protected function doFormDataCheck()
	{
		global $cfg;
		$this->_var['ua.data.key']=false;
		$field_name='name';
		$name=$this->treeData->getItem('name');
		$checknext=!$this->isErrorCheck();
		if($checknext && len($name)>0){	//check name
			$this->_var['ua.data.key']=true;
			$name_min=$cfg->num('name.min');
			//if(!$name_min) $name_min=3;
			$name_max=$cfg->num('name.max');	//50;	//
			if(!$name_max) $name_max=50;
			$name_type=$cfg->num('name.type');	//2;	//
			if(!$name_type) $name_type=2;
			//debugx($name_type);
			if($name_min>0 && len($name)<$name_min) $this->addError(''.$this->getVar('ua.names').'名称 长度不能小于'.$name_min.'位！',$field_name,'min');
			if($name_max>0 && len($name)>$name_max) $this->addError(''.$this->getVar('ua.names').'名称 长度不能大于'.$name_max.'位！',$field_name,'max');
			if(!$this->isErrorCheck()){
				$isCheck=false;
				switch($name_type){
					case 2:
						if(utilCheck::isName($name)) $isCheck=true;
						break;
					case 5:
						if(utilCheck::isEmail($name)) $isCheck=true;
						break;
					case 9:
						if(utilCheck::isName($name) || utilCheck::isEmail($name)) $isCheck=true;
						break;
					default:
						if(utilCheck::isAccount($name)) $isCheck=true;
						break;
				}
				if(!$isCheck){ $this->addError(''.$this->getVar('ua.names').'名称 为空或不符合规则！',$field_name,'check'); }
				else{
					if($this->isHoldName($name)) $this->addError($this->getVar('ua.names').'名称 已保留！',$field_name,'hold');
				}
				if(!$this->isErrorCheck()){
					if($this->uam->isExistName($name)) $this->addError($this->getVar('ua.names').'名称 已存在！',$field_name,'exist');
				}
			}
		}
		
		//ResTest::logAction('register');
		
		$field_name='names';
		$names=$this->treeData->getItem('names');
		$checknext=!$this->isErrorCheck();
		if($checknext && len($names)>0){	//check names
			if(!utilCheck::isName($names)){ $this->addError(''.$this->getVar('ua.names').'昵称 为空或不符合规则！',$field_name,'check'); }
			else{
				if($this->uam->isExistNames($names)) $this->addError(''.$this->getVar('ua.names').'昵称 已存在！',$field_name,'exist');
			}
		}
		
		$field_name='email';
		$email=$this->treeData->getItem('email');
		$checknext=!$this->isErrorCheck();
		if($checknext && len($email)>0){	//check email
			$this->_var['ua.data.key']=true;
			if(!utilCheck::isEmail($email)){ $this->addError('电子邮箱 为空或不符合规则！',$field_name,'check'); }
			else{
				if($this->uam->isExistEmail($email)) $this->addError('电子邮箱 已存在！',$field_name,'exist');
			}
		}
		$field_name='mobile';
		$mobile=$this->treeData->getItem('mobile');
		$checknext=!$this->isErrorCheck();
		if($checknext && len($mobile)>0){	//check mobile
			$this->_var['ua.data.key']=true;
			if(!utilCheck::isMobile($mobile)){ $this->addError('手机号码 为空或不符合规则！',$field_name,'check'); }
			else{
				if($this->uam->isExistMobile($mobile)) $this->addError('手机号码 已存在！',$field_name,'exist');
			}
			$field_name='mobile_dcode';
			$dcode=$this->treeData->getItem('mobile_dcode');	//debugx($dcode);
			$checknext=!$this->isErrorCheck();			//$checknext=true;
			if($checknext && ($this->dcode_is || $dcode)){
				if(DcodeSMS::isValidUse($this->dcode_module,$dcode,$mobile)!=1) $this->addError('短信动态码 不正确！',$field_name,'error');
				else{
					DcodeSMS::used($this->dcode_module,$dcode,$mobile);
					$this->treeData->addItem('auth_mobile',1);
				}
			}
		}
		$field_name='idcard';
		$idcard=$this->treeData->getItem('idcard');
		$checknext=!$this->isErrorCheck();
		if($checknext && len($idcard)>0){	//check idcard
			$this->_var['ua.data.key']=true;
			if(!utilCheck::isIDCard($idcard)){ $this->addError('证件号码 为空或不符合规则！',$field_name,'check'); }
			else{
				if($this->uam->isExistIDCard($idcard)) $this->addError('证件号码 已存在！',$field_name,'exist');
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){					//check password,answer,realname
			if(!utilCheck::isPassword($this->treeData->getItem('password'))) { $this->addError('登录密码 为空或不符合规则！','password','check'); }
			else if($this->treeData->getItem('password')!=$this->treeData->getItem('password2')) { $this->addError('登录密码 与 确认密码 不一致！','password2','check'); }
			if(len($this->treeData->getItem('safe_answer'))>0 && !utilCheck::isPassword($this->treeData->getItem('safe_answer'))) $this->addError('问题答案 为空或不符合规则！','safe_answer','check');
		}
		
		$this->doFormDataCheckExtend();
		
		if(!$this->_var['ua.data.key']) $this->addError(''.$this->getVar('ua.names').'信息 为空或不符合规则！','account','no');
	}
	protected function doFormDataCheckExtend(){}
	
	protected function doFormDataFilter(){}
	
	
	/*
	########################################
	########################################
	*/
	protected function isHoldName($s)
	{
		global $cfg;
		$holdname=$cfg->cfg('name.hold');
		if($holdname){
			$holdname=str_replace(',','|',$holdname);
			if(@eregi('^('.$holdname.')$',$s)) return true;
		}
		return false;
	}
	
}
?>