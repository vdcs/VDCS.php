<?
class PageLoginxPortal extends ManagePortalBaseX
{
	
	public function doAuth()
	{
		$this->doAuthe();
	}
	
	public function doInit()
	{
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setAuth(false);
		$this->setDebug(false);
	}
	
	public function doParse()
	{
		$this->doParseLogin();
	}
	
	public function doParseLogin()
	{
		$this->ma->doInit();
		if($this->ma->isLogin()){
			$this->doVarAppend();
			$this->setStatus('already');
			return;
		}
		
		$secureCode=c('manage','secure_code');
		$this->treeData->addItem('issecurecode','');
		$this->treeData->addItem('error_message','');
		$this->treeData->addItem('name','');
		$this->treeData->addItem('password','');
		$this->treeData->addItem('secure_code','');
		if($secureCode) $this->treeData->addItem('issecurecode','yes');
		
		if(!$this->ready()) return;	//true
		
		$this->treeData->addItem('name',request('name'));
		$this->treeData->addItem('password',request('password'));
		$this->treeData->addItem('secure_code',request('secure_code'));
		
		if(!utilCheck::isName($this->treeData->getItem('name'))) $this->addError('登录帐号 为空或不符合规则');
		if(!utilCheck::isPassword($this->treeData->getItem('password'))) $this->addError('登录密码 为空或不符合规则');
		
		if($secureCode){
			if(!utilCheck::isPassword($this->treeData->getItem('secure_code'))){
				$this->addError('安全密码 为空或不符合规则');
			}
			else{
				if(utilCoder::toMD5($this->treeData->getItem('secure_code'))!=$secureCode) $this->addError('安全密码 为空或有错误存在');
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$this->ma->setData('name',$this->treeData->getItem('name'));
			$this->ma->setData('password',utilCoder::toMD5i($this->treeData->getItem('password')));
			$this->ma->doLoginCheck();
			if(!$this->ma->isLogin()){
				$this->addError('登录帐号 或 密码 有错误存在');
			}
		}
		
		if($this->isRaiseError()) return;
		
		//debugx($_id.','.$_name.','.$_email);
		$this->doVarAppend();
		$this->setStatus('succeed');
	}
	
	public function doVarAppend()
	{
		//$backurl=$this->ma->getURL('referer');
		if(!$backurl) $backurl=appURL('manage.welcome');
		$this->addVar('backurl',$backurl);
	}
	
}
?>