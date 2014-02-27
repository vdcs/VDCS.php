<?
class PassportPaOauthQq extends ChannelPaBase
{
	use PassportOauthRef,PassportOauthRefPa;
	protected $AUTHRC='qq';
	
	public function doParse(&$that)
	{
		if(!$this->oauthLoad($that)) return;
		if($this->oa->isPause()){
			$this->theme->setAction('pause');
			return;
		}
		switch($this->action){
			case 'bind':
				$this->doParseBind($that);
				break;
			case 'callback':
				$this->doParseCallback($that);
				break;
			default:
				$this->doParseAuthorize($that);
				break;
		}
	}
	
	public function doParseAuthorize(&$that)
	{
		$url=$this->oa->oauth->getAuthorizeURL();
		//debugx($url);
		if($this->oa->oauth->isError()){
			$this->theme->setAction('error');
			$this->addVar('errorcode',$this->oa->oauth->getErrorCode());
		}
		else{
			$this->addVar('url.login.oauth',$url);
			$this->theme->setAction('connect');
			if($this->oa->isRedirect()) go($url);
		}
	}
	
	public function doParseBind(&$that)
	{
		$this->oa->setVar('openid',queryx('openid'));
		if(!$this->oa->getVar('openid')){
			go(appURL('root'));
			return;
		}
		if(!$this->ua->isLogin()){
			$urlbind=$this->oa->toURL('action=bind&openid='.$this->oa->getVar('openid'),false);
			$url=$this->oa->getURL('login','url='.DCS::urlEncode($urlbind));
			$this->addVar('url.login.bind',$url);
			//debugx($url);
			go($url);
			return;
		}
		$this->oa->bindInit(false);
		
		$this->theme->setAction('bind');
		
		//debugx($this->oa->getVar('openid').', '.$this->oa->getVar('uuid')&', '.$this->ua->id);
		if($this->oa->isBind()){
			$this->theme->setAction('binded');
		}
		else{
			if($this->oa->isBindU($this->ua->id)){
				$this->theme->setAction('binded');
			}
			else{
				$this->oa->setVar('uuid',$this->ua->id);
				$this->oa->bindSaveUID();
			}
		}
		
		//$url=$this->getUserRedirectURL(true);
		$url=$this->ua->getURL('referer');
		if(!$url) $url=dcsURL('root');
		$this->addVar('url.login.referer',$url);
		//if($this->oa->isRedirect()) go($url);
	}
	
	public function doParseCallback(&$that)
	{
		$this->oa->querys=$this->oa->oauth->getQueryTree();
		//debugTree($this->oa->querys);
		if($this->oa->querys->getCount()<1){
			$this->theme->setAction('error');
			$this->addVar('error.code','-1');
			$this->addVar('error.type','params');
			$this->addVar('error.message','错误的返回参数');
			return;
		}
		
		//$this->oa->setVar('authcode',queryx('code'));
		$this->oa->setVar('authcode',$this->oa->querys->getItem('code'));
		//debugx($this->oa->getVar('authcode'));
		$this->oa->bindCheck();
		if(!$this->oa->isBind()){
			if(!$this->oa->isData()){
				//debugTree($this->oa->querys);
				$this->oa->results=$this->oa->oauth->getAccessToken('code',$this->oa->querys);
				//debugTree($this->oa->results);
				//debugx($this->oa->results->getItem('access_token'));
				if($this->oa->oauth->isError()){
					$this->theme->setAction('error');
					$this->addVar('error.code',$this->oa->oauth->getErrorCode());
					$this->addVar('error.type',$this->oa->oauth->getErrorType());
					$this->addVar('error.message',$this->oa->oauth->getErrorMessage());
					return;
				}
				$this->oa->setVar('authcode',$this->oa->results->getItem('code'));
				$this->oa->setVar('authtoken',$this->oa->results->getItem('access_token'));
				$this->oa->setVar('authkey',$this->oa->results->getItem('token_secret'));
				$this->oa->setVar('authv1',$this->oa->results->getItem('expires_in'));
				$this->oa->setVar('authv2',$this->oa->results->getItem('remind_in'));
				
				$this->oa->results_token=$this->oa->oauth->getUserOpenID($this->oa->results->getItem('access_token'));
				//debugTree($this->oa->results_token);
				if($this->oa->oauth->isError()){
					$this->theme->setAction('error');
					$this->addVar('error.code',$this->oa->oauth->getErrorCode());
					$this->addVar('error.type',$this->oa->oauth->getErrorType());
					$this->addVar('error.message',$this->oa->oauth->getErrorMessage());
					return;
				}
				$this->oa->results->doAppendTree($this->oa->results_token);
				//debugTree($this->oa->results);
				$this->oa->setVar('openid',$this->oa->results_token->getItem('openid'));
				$this->oa->bindInit(true);
			}
		}
		$this->addVar('openid',$this->oa->openid());
		
		//debugx($this->oa->getVar('openid').', '.$this->oa->getVar('uuid').', '.$this->ua->id);
		if($this->oa->isBind()){
			//$this->doUserAutoLogin($this->oa->getVar('uuid'),'',true);
			$url='';
			$status=UaE::autoLogin($this->ua,$this->oa->getVar('uuid'),false,$url);
			
			if($status=='succeed'){
				$this->theme->setAction('connected');
				$this->addVar('url.login.referer',$url);
				if($this->oa->isRedirect()) go($url);
			}
			else{
				$this->theme->setAction('error');
			}
			return;
		}
		
		$this->theme->setAction('nobind');
		//debugTree($this->treeVar);
		$urlbind=$this->oa->toURL('action=bind&openid='.$this->oa->getVar('openid'),false);
		$url=$this->oa->getURL('login','url='.DCS::urlEncode($urlbind));
		//debugx($url);
		$this->addVar('url.login',$url);
		$url=$this->oa->getURL('register','url='.DCS::urlEncode($urlbind));
		//debugx($url);
		$this->addVar('url.register',$url);
	}
	
}
