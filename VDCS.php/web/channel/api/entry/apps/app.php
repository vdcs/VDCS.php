<?
class apiEntry extends apiBase
{
	
	public function auth()
	{
		$this->authReset();
		$this->authMode('oauth');
	}
	
	public function parse()
	{
		$this->parseLogin();
	}
	public function parseLogin()
	{
		$authkey=queryx('authkey');
		$by=queryx('by');
		$value=queryx('value');
		if($authkey){
			debugxx('by='.$by.', value='.$value);
			$this->addVar('query.by',$by);
			$this->addVar('query.value',$value);
			//##########
			if($authkey!=$this->oauth->toEncryptKey($by.$value.$API_SSO_KEY)){
				$this->setStatus('authkey');
				return;
			}
			//##########
		}
		else{
			$by='uid';
			$value=$this->oauth->reqVar->getItem('ua.uid');
		}
		
		//by=value
		switch($by){
			case 'email'		: $sqlQuery=$this->ua->sqlQuery('email',$value);break;
			case 'name'		: $sqlQuery=$this->ua->sqlQuery('name',$value);break;
			case 'uid':
			default			: $sqlQuery=$this->ua->setID(toi($value));break;
		}
		$uid=$this->ua->queryField('id',$sqlQuery);
		if(!$uid){
			$this->setStatus('noexist');
			return;
		}

		$this->ua->setID($uid);
		//##########
		$upivotal=UuPivotal::getInstance($this->ua);
		$_password=$upivotal->getData('password');
		$islogin=UuPivotal::isLogin($this->ua,$_password);
		//##########
		$this->ua->setData('_password',$_password);
		$this->ua->doLoginCheck(true);
		$this->ua->save();
		if(!$this->ua->isLogin()){
			$this->setStatus('failed');
			return;
		}
		
		//$this->ua->dataLoader();
		//UaUA::doLoginAppend($this,$this->ua);

		$url=$this->ua->getURL();
		$this->addVar('url_redirect',$url);

		$this->setSucceed();

		$isredirect=queryx('redirect');
		if($isredirect && ins($url,'log')<1) go($url);
	}

}
