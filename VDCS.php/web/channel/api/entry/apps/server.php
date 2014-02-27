<?
class apiEntry extends apiBase
{
	
	public function auth()
	{
		$this->authReset();
		if($this->action=='login'){
			$this->authedModel('app');
		}
	}
	
	public function parse()
	{
		$this->parseAuth();
	}
	public function parseAuth()
	{
		//dcsLogSave('api','apps.soo',DCS::browseURL(true));
		
		// auth_code
		$auth_code=queryx('auth_code');
		$auth_ip=query('auth_ip');
		$this->addVar('auth_code',$auth_code);
		$this->addVar('auth_ip',$auth_ip);
		$treeCode=OauthsCode::getTree($auth_code);
		if($treeCode->getCount()<1){
			$this->setStatus('noauth');
			return;
		}
		$this->appid=$treeCode->getItem('appid');
		$this->ua=&Ua::instance($treeCode->getItem('uurc'));
		$this->ua->setID($treeCode->getItem('uuid'));
		//$this->testo($treeCode,'treeCode');
		
		// app ua
		$appUa=AppsUa::getTree($this->ua,$this->appid);
		if($appUa->getCount()<1){
			$this->setStatus('nobind');
			return;
		}
		//$this->testo($appUa,'appUa');
		// ua
		$treeUa=$this->ua->queryTree();
		
		$this->addVar('uurc',$this->ua->rc);
		$this->addVar('uuid',$this->ua->id);
		$this->addVar('ua.uid',$treeUa->getItem('uid'));
		$this->addVar('ua.name',$treeUa->getItem('name'));
		$this->addVar('ua.email',$treeUa->getItem('email'));
		
		$this->testo($treeApp,'appid');

		if(!DCS::$local || 1==1) OauthsCode::used($auth_code);
		$this->addVar('sso_url',$url);
		
		$this->setSucceed();
	}
	
	public function parseLogin()
	{
		// app ua
		$appUa=AppsUa::getTree($this->ua,$this->appid);
		if($appUa->getCount()<1){
			$this->setStatus('noua');
			return;
		}
		//$this->testo($appUa,'appUa');
		
		// auther
		$auther=OauthsAction::authorizer($this->ua,['appid'=>$this->appid,'appkey'=>$this->appkey]);
		if(!$auther){
			$this->setStatus('auther');
			return;
		}
		//$this->testo($auther,'auther');
		debugxx('auth_code='.$auther['code'].', auth_token='.$auther['token']);
		
		// url
		$opt=[];
		$opt['serve']='sso';
		$opt['api']='user.login';
		$querys=[];
		$querys['auth_code']=$auther['code'];
		/*
		$querys['by']='name';
		$querys['value']=$treeUa->getItem('name');
		$keystr=$querys['by'].$querys['value'].$this->appkey;
		$querys['authkey']=OauthsAction::toEncryptKey($keystr);
		*/
		$url=AppsCommon::urlBuild($this->treeApp,$opt,$querys);
		
		debugxx('sso_url='.$url);
		$this->addVar('redirect_url',$url);
		$this->addVar('sso_url',$url);
		
		$this->setSucceed();
	}
	
}
