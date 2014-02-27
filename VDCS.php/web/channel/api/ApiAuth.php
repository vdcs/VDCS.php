<?
class ApiAuth
{
	use utilRefError;

	public $that;
	public $isapp=false,$isauth=true;
	
	
	public function auther()
	{
		$this->isauth=$this->authiCheck();
		if($this->isauth) $this->isauth=$this->authCheck();
		//$this->isauth=$this->authua();
	}
	

	/*
	########################################
	########################################
	*/
	public function getAppsKey($auth)
	{
		if($this->isapp) $re=AppsServer::getSiteKey($this->that->appid,$auth);
		elseif($this->isauth_app) $re=AppsApp::getKey($auth);
		else $re=AppsServer::getKey($auth);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	protected $auth_format='xml',$auth_mode='',$auth_status='',$isauth_app=true;
	public function authFormat($value=null){if(!is_null($value))$this->auth_format=$value;return $this->auth_format;}
	public function authMode($value=null){if(!is_null($value))$this->auth_mode=$value;return $this->auth_mode;}
	public function authStatus($value=null){if(!is_null($value))$this->auth_status=$value;return $this->auth_status;}
	
	public function authCheck()			// check
	{
		$re=true;
		$this->auth_port=queryx('auth_port');
		if($this->auth_port=='app') $this->isauth_app=true;
		if($this->isapp && !$this->auth_mode) $this->auth_mode='app';
		debugxx('auth_mode='.$this->auth_mode);
		switch($this->auth_mode){
			case 'interface'	: $re=$this->isAuthCheckInterface();break;
			case 'oauths'		: $re=$this->isAuthCheckOauths();break;
			case 'oauth'		: $re=$this->isAuthCheckOauth();break;
			//case 'ua'		: $re=$this->authua();break;
		}
		$this->isauth=$re;
		return $re;
	}

	public function isAuthCheckInterface()
	{
		$auth_token=queryx('auth_token');
		$auth_tim=queryx('auth_tim');
		$re=false;
		$secret_key=$this->getAppsKey('interface');
		debugxx('secret_key='.$secret_key.', auth_tim='.DCS::timer().', auth_token='.AppsCommon::encryptKey($secret_key,DCS::timer()));
		$ENCRYPT_KEY=AppsCommon::encryptKey($secret_key,$auth_tim);
		//debugx($ENCRYPT_KEY);
		//$auth_token=$ENCRYPT_KEY;
		if($auth_token==$ENCRYPT_KEY){
			$re=true;
		}
		return $re;
	}

	public function isAuthCheckOauth()		// check Oauth
	{
		$auth_code=queryx('auth_code');
		$re=false;
		$params=newTree();
		$params->addItem('auth_code',$auth_code);
		$params->addItem('auth_ip',DCS::ip());
		$params->addItem('action','auth');
		$url=AppsApp::urlBuild('apps.server',$params);
		debugxx('oauth.url='.$url);
		$requests=VDCSHTTP::request($url);
		$this->filterRequest($requests,$this->reqVar,$this->reqMaps);
		//debugTree($this->reqVar);
		//debuga($this->_errors);
		if(!$this->isError()){
			$re=true;
		}
		return $re;
	}

	public function authua()			// auth ua
	{
		if(!$this->isauth) return false;
		$re=true;
		if(!$this->that->ua->isLogin()){
			$this->that->setStatus('nologin');
			$re=false;
		}
		$this->isauth=$re;
		return $re;
	}

	/*
	########################################
	########################################
	*/
	protected $authi_model='',$authi_status='';
	public function authiModel($value=null){if(!is_null($value))$this->authi_model=$value;return $this->authi_model;}
	public function authiStatus($value=null){if(!is_null($value))$this->authi_status=$value;return $this->authi_status;}
	public function authiCheck($model=null)		//authiCheck
	{
		if(!$this->isauth) return false;
		$re=true;
		if(is_null($model)) $model=$this->authi_model;
		debugxx('authiCheck.model='.$model);
		if(inp($model,'app')>0) $re=$this->isAuthiCheckApp();
		if(inp($model,'local')>0) $re=$this->isAuthiCheckLocal();
		$this->isauth=$re;
		return $re;
	}
	
	public function isAuthiCheckApp()
	{
		$this->isapp=true;
		$this->appid=queryx('appid');
		$this->that->appid=&$this->appid;
		if(DCS::$local) $this->that->addVar('appid',$this->that->appid);
		$this->that->treeApp=AppsServer::getSiteTree($this->that->appid);
		//debugTree($this->that->treeApp);
		if(!$this->that->appid || $this->that->treeApp->getCount()<1){
			$this->authi_status='noapp';
			return false;
		}
		$this->that->appkey=AppsServer::getSiteKey($this->that->appid);
		//$this->that->testo($this->that->treeApp,'appid');
		return true;
	}
	public function isAuthiCheckLocal()
	{
		if(!DCS::isLocali()){
			$this->authi_status='nolocal';
			return false;
		}
		return true;
	}



	/*
	########################################
	########################################
	*/
	public function filterRequest($requests,&$reqVar,&$reqMaps)
	{
		$this->setError(false);
		//debugxx($requests);
		$reqVar=VDCSDTML::toConfigTree($requests);
		$reqVar=$reqVar->getFilterTree('var.');
		//debugTree($reqVar);
		$this->auth_status=$reqVar->getItem('status');
		//debugx($this->auth_status);
		if($this->auth_status!='succeed'){
			$this->setErrorCode($reqVar->getItem('status_code'));
			$this->setErrorMessage($reqVar->getItem('status_message'));
			$this->setError(true);
		}
	}
	
}
