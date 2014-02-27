<?
trait ChannelRefSettingsModify
{
	
	protected function uv($k){return $this->cfg->getChannelValue($this->UCHANNEL,'','var.'.$this->UAC.$k);}
	protected function uvp($k){return $this->cfg->getChannelValue($this->UCHANNEL,'','pre.'.$this->UAC.$k);}
	
	protected function uvc($k,$yn=false)
	{
		$re=$this->cfg->getChannelValue($this->UCHANNEL,'','config.'.$this->UAC.$k);
		if($yn){
			if(len($re)<1 || $re=='0') $re='no';
			if($re=='1') $re='yes';
		}
		return $re;
	}
	
	
	protected function initModify()
	{
		//##########
		//$this->UCHANNEL='';
		//##########
	}
	protected function initModifySet()
	{
		//##########
		$this->_var['isedit.fields']='gender,email,mobile,idtype,idcard,names,nickname,realname';
		//##########
	}
	
	protected function initModifyed()
	{
		if($this->isModifyed)return;$this->isModifyed=true;
		$this->initModify();
		$this->initModifySet();
		//if(!$this->UCHANNEL) $this->UCHANNEL=$this->_chn_;
		//debugx($this->UCHANNEL.', '.$this->UAC);
		//##########
		$this->TableName=$this->ua->struct('TableName');
		$this->TablePX=$this->ua->struct('TablePX');
		$this->FieldID=$this->ua->struct('FieldId');
		//debugx($this->TableName.','.$this->TablePX.','.$this->FieldID);
		//##########
		$this->moduleC='ua:';
		$this->chnPre=CommonChannelExtend::getPreTree($this->UCHANNEL,true);
		$this->chnPre->addItem('xchannel',$this->_chn_);
		$this->chnPre->addItem('xportals',$this->_chn_);
		$this->chnPre->addItem('xportal',$this->_p_);
		$this->chnPre->addItem('xmodule',$this->_m_);
		$this->chnPre->addItem('tbl.name',$this->TableName);
		$this->chnPre->addItem('tpx',$this->TablePX);
		$this->chnPre->addItem('tbl.field.id',$this->FieldID);
		$this->chnPre->addItem('xname',$this->chnPre->getItem($this->moduleC.'name'));
		$this->chnPre->addItem('xnames',$this->chnPre->getItem($this->moduleC.'names'));
		$this->chnPre->addItem('xact',$this->chnPre->getItem($this->moduleC.'act'));
		$this->chnPre->addItem('xact.get',$this->chnPre->getItem($this->moduleC.'act.get'));
		$this->chnPre->addItem('xact.view',$this->chnPre->getItem($this->moduleC.'act.view'));
		$this->chnPre->addItem('xunit',$this->chnPre->getItem($this->moduleC.'unit'));
		$this->chnPre->addItem('@sp.code','1');
		$this->chnPre->addItem('@editor','editor_ubb');
		$this->chnPre->addItem('@remark.min','10');
		$this->chnPre->addItem('@remark.max','50000');
		//debugTree($this->chnPre);
		
		$this->uam=&Ua::instance($this->UARC,'manage');
		$this->uam->doInit();
		$this->uam->loadData();
	}
	
	
	protected function doModifyInfo()
	{
		global $ctl;
		$this->initModifyed();
		$this->loadPages();
		$ctl->pages->setFormAction('edit');
		$ctl->pages->setFormFile('modify','info');
		$ctl->pages->setFormTree($this->uam->getDatarTree());
		//debugTree($this->uam->getDatarTree());
		$this->doModifyEditIs();
		$this->loadPagesForm();
		if($ctl->pages->isFormPost()){
			$this->doPagesParse();
			//debugTree($ctl->treeData);
			/*
			if(!$ctl->e->isCheck()){
				$_birthday=$ctl->treeData->getItem($this->_var['table.px'].'birthday');
				if(!utilCheck::isDate($_birthday)) $ctl->e->addItem('出生日期 为空或不符合规则！');
			}
			*/
			if($ctl->e->isCheck()){$ctl->doRaiseError();}
			else{
				$this->doModifyEditData();
				//debugTree($ctl->treeData);
				
				$this->uam->doSave($ctl->treeData);
				/*
				DB::executeUpdate($this->_var['table.name'],$this->_var['fields.modify'],$ctl->treeData,$this->_var['relate.query']);
				if(!$this->ua->isInfoData()) DB::executeInsert($this->_var['info:table.name'],$this->_var['field.id'],$ctl->treeDat);
				DB::executeUpdate($this->_var['info:table.name'],$this->_var['info:fields.modify'],$ctl->treeData,$this->_var['relate.query']);
				*/
				
				$this->doMessage('','succeed','#ok.info',$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
		//debugx($ctl->pages->getDebug());
	}
	
	
	protected function doModifyPassword()
	{
		global $ctl;
		$this->initModifyed();
		$this->treeRS=newTree();
		// pivotal
		$this->mpivotal=new UcPivotalManage();
		$this->mpivotal->setURC($this->ua->rc);
		$this->mpivotal->init();
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->uam->id);
			$this->mpivotal->loadData();
			$this->treeRS->doAppendTree($this->mpivotal->getFormDataTree());
		}
		//##########
		//debugTree($this->treeRS);
		$this->loadPages();
		$ctl->pages->setFormChannel('modify');
		$ctl->pages->setFormFile('modify','password');
		$ctl->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if($ctl->pages->isFormPost()){
			$this->doPagesParse();
			
			if(!$ctl->e->isCheck()){
				if(utilCoder::toMD5($ctl->treeData->getItem('oldpassword'))!=$this->treeRS->getItem('pivotal_password')) $ctl->e->addItem('您输入的 当前密码 有错误存在');
			}
			if(!$ctl->e->isCheck()){
				$_password=$ctl->treeData->getItem('password');
				if(len($_password)>0){
					if(!utilCheck::isPassword($_password)){
						$ctl->e->addItem('您要修改的 登录密码 不符合规则');
					}
					else{
						if($_password!=$ctl->treeData->getItem('password2')) $ctl->e->addItem('您要修改的 登录密码 和 确认密码 不一致');
					}
				}
				if(len($ctl->treeData->getItem('pivotal_safe_question'))>0){
					if(!utilCheck::isSecure($ctl->treeData->getItem('pivotal_safe_question'))) $ctl->e->addItem('您要修改的 安全问题 不符合规则');
				}
				if(len($ctl->treeData->getItem('pivotal_safe_answer'))>0){
					if(!utilCheck::isSecure($ctl->treeData->getItem('pivotal_safe_answer'))) $ctl->e->addItem('您要修改的 安全答案 不符合规则');
				}
			}
			if($ctl->e->isCheck()){$ctl->doRaiseError();}
			else{
				//debugTree($ctl->treeData);
				
				if(len($_password)>0) $ctl->treeData->addItem('pivotal_password',$_password);
				
				//########## pivotal
				if($this->mpivotal->is()){
					$this->mpivotal->doFormSave($ctl->treeData);
				}
				//##########
				$this->uam->doModifyPassword($_password);
				
				$this->doMessage('','succeed','#ok.password',$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
		//debugx($ctl->pages->getDebug());
	}
	
	
	
	public function loadPages()
	{
		parent::loadPages();
		$this->loadPagesModify();
	}
	protected function loadPagesModify()
	{
		//#########
		$this->chnPre->doBegin();
		for($t=1;$t<=$this->chnPre->getCount();$t++){
			$this->pages->addFormPre($this->chnPre->getItemKey(),$this->chnPre->getItemValue());
			$this->chnPre->doMove();
		}
		//##########
	}
	
	
	protected function doModifyEditIs()
	{
		$arFields=toSplit($this->_var['isedit.fields'],',');
		foreach($arFields as $field){
			$this->pages->addFormPre('isedit.'.$field,$this->uvc('isedit.'.$field));
		}
	}
	protected function doModifyEditData()
	{
		$arFields=toSplit($this->_var['isedit.fields'],',');
		foreach($arFields as $field){
			if($this->pages->getFormPre('isedit.'.$field)=='no') $ctl->treeData->addItem($tablepx.$field,$ctl->treeDat->getItem($tablepx.$field));
		}
	}
	
	
	protected function initUaManage()
	{
		//##########
		$this->uam=&Ua::instance($this->ua->rc,'manage');
		$this->uam->id=$this->ua->id;
		$this->uam->loadData();
		if(!$this->uam->isData()){
			$this->setStatus('uam');
			return;
		}
		// pivotal
		$this->uam->mpivotal->loadData();
		//##########
	}
	protected function checkPasswordLogin()
	{
		$_encrypt_timer=request('_encrypt_timer');
		$_password_login=request('password_login');
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if(len($_password_login)<4) $this->addError('登录密码 为空或不符合规则！','password_login','no');
			else if(!UuPivotal::isLogin($this->uam,$_password_login,$_encrypt_timer)) $this->addError('登录密码 错误！','password_login','error');
		}
	}
	
}
?>