<?
class ChannelAccountAppsSsoX extends ChannelAccountBaseX
{
	
	public function parserCan()
	{
		$this->appid=queryx('appid');
		$this->addVar('appid',$this->appid);
		$this->treeApp=AppsServer::getSiteTree($this->appid);
		if(!$this->appid || $this->treeApp->getCount()<1){
			$this->setStatus('noapp');
			$this->setMessage('无效的接入服务('.$this->appid.')！');
			return false;
		}
		$this->appkey=$this->treeApp->getItem('api_key_sso');
		if(!$this->appkey) $this->appkey=$this->treeApp->getItem('api_key');
		
		$this->appUa=AppsUa::getTree($this->ua,$this->appid);
		//$this->testo($this->appUa,'appUa');
		if($this->appUa->getCount()>0){
			$this->addVar('app.uid',$this->appUa->getItem('uid'));
			$this->addVar('app.name',$this->appUa->getItem('name'));
			$this->addVar('app.names',$this->appUa->getItem('names'));
		}
		
		return true;
	}

	public function setStatusDef($type)
	{
		switch($type){
			case 'binded':
				$this->setStatus('binded');
				$this->setMessage('帐号('.$this->treeApp->getItem('name').')已绑定！');
				break;
			case 'nobind':
				$this->setStatus('nobind');
				$this->setMessage('帐号('.$this->treeApp->getItem('name').')未绑定！');
				break;
		}
	}
	
        
	/*
	########################################
	########################################
	*/
	public function parseStatus()
	{
		if($this->appUa->getCount()<1){
			$this->setStatus('noua');
			return;
		}
		$this->setSucceed();
	}
	
	public function parseBind()
	{
		if($this->appUa->getCount()>0){
			$this->setStatusDef('binded');
			return;
		}
		//debugTree($this->treeApp);
		
		if(!($by=postx('by')) && !($by=queryx('by'))) $by='name';
		if(!($value=post('value'))) $value=query('value');
		if(!$value && !($value=post('account'))) $value=query('account');
		if(!($password=postx('password'))) $password=queryx('password');
		if(utilCheck::isEmail($value)) $by='email';
		elseif(utilCheck::isMobile($value)) $by='mobile';
		$params=newTree();
		$params->addItem('action','query');
		$params->addItem('by',$by);
		$params->addItem('value',$value);
		$apiret=AppsServer::parser($this->treeApp,'ua.query',$params);
		$this->addVar('app.status',$apiret['status']);
		$resultStatus=$apiret['var.status'];
		if($resultStatus!='succeed'){
			$this->setStatus($resultStatus);
			$message='服务异常！('.$resultStatus.')';
			switch($resultStatus){
				case 'data':		$message='缺少必要的数据';break;
				case 'noexist':		$message='错误的登录帐号';break;
			}
			$this->setMessage($message);
			return;
		}
		
		$resultVar=$apiret['api.var'];
		$this->testo($resultVar,'resultVar');
		$uid=$resultVar->getItemInt('ua._id');
		
		$params=newTree();
		$params->addItem('action','password');
		$params->addItem('uid',$uid);
		$params->addItem('password',$password);
		$apiret=AppsServer::parser($this->treeApp,'ua.verify',$params);
		if($apiret['var.status']!='succeed'){
			$this->setStatus('password');
			$this->setMessage('错误的登录密码');
			return;
		}
		
		$_status=AppsUa::bind($this->ua,$this->appid,$resultVar->getFilterTree(AppsApp::getConfig('ua').'.'));
		if($_status==1){
			$this->setSucceed();
		}
		else{
			$this->setStatusDef('binded');
		}
	}

	public function parseUnbind()
	{
		if($this->appUa->getCount()<1){
			$this->setStatusDef('nobind');
			return;
		}
		//debugTree($this->treeApp);
		
		$_status=AppsUa::unbind($this->ua,$this->appid);
		if($_status==1){
			$this->setSucceed();
		}
		else{
			$this->setStatusDef('nobind');
		}
	}

	public function parseLogin()
	{
		if($this->appUa->getCount()<1){
			$this->setStatusDef('nobind');
			return;
		}
		
		// author
		$author=OauthsAction::authorizer($this->ua,['appid'=>$this->appid,'appkey'=>$this->appkey]);
		if(!$author){
			$this->setStatus('author');
			return;
		}
		//$this->testo($author,'author');
		debugxx('auth_code='.$author['code'].', auth_token='.$author['token']);
		
		// url
		$opt=[];
		//$opt['auth']='sso';
		$opt['api']='apps.app';
		$params=[];
		$params['auth_code']=$author['code'];
		$params['action']='login';
		/*
		$params['by']='name';
		$params['value']=$this->appUa->getItem('name');
		$keystr=$params['by'].$params['value'].$this->appkey;
		$params['authkey']=OauthsAction::toEncryptKey($keystr);
		*/
		$url=AppsServer::urlBuild($this->treeApp,$opt,$params);
		//debugxx('url_redirect='.$url);
		$isredirect=queryx('redirect');
		if($isredirect) $url=DCS::urlLink($url,'redirect='.$isredirect);
		$this->addVar('url_redirect',$url);
		
		$this->setSucceed();
		
		if($isredirect) go($url);
	}
	
}
