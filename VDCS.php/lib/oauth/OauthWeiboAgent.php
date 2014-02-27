<?
class OauthWeiboAgent extends OauthWeibo
{
	use utilRefError,OauthRefAgent;
	
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getQueryTree()				//检测是否合法登录,返回一个utilTree对像,合法则有item
	{
		$reTree=newTree();
		$code=trim(query('code'));
		if(len($code)>0){
			$reTree->addItem('code',$code);
			$reTree->addItem('redirect_uri',$this->_CALLBACK);
		}
		return $reTree;
	}
	
	public function getAuthorizeURL()			//生成登录地址
	{
		$params=newTree();
		$params->addItem('client_id',$this->_KEY);
		$params->addItem('redirect_uri',$this->_CALLBACK);
		$params->addItem('response_type','code');
		$params->addItem('state','');
		$params->addItem('display','');
		$url=DCS::urlLink(self::URL_AUTHORIZE,$params);
		//debugx($url);
		return $url;
	}
	
	public function getAccessToken($type,$keys)		//获取存取token,返回一个utilTree对像
	{
		$reTree=newTree();
		$posts=newTree();
		$posts->addItem('client_id',$this->_KEY);
		$posts->addItem('client_secret',$this->_SECRET);
		switch($type){
			case 'token':
				$posts->addItem('grant_type','refresh_token');
				$posts->addItem('refresh_token',$keys->getItem('refresh_token'));
				break;
			case 'password':
				$posts->addItem('grant_type','password');
				$posts->addItem('username',$keys->getItem('username'));
				$posts->addItem('password',$keys->getItem('password'));
				break;
			//case 'code':
			default:
				$posts->addItem('grant_type','authorization_code');
				$posts->addItem('code',$keys->getItem('code'));
				$posts->addItem('redirect_uri',$keys->getItem('redirect_uri'));
				break;
		}
		//debugx('posts:');
		//debugTree($posts);
		$treeReq=OauthCommon::getRequestTree(self::URL_ACCESS_TOKEN,'POST',null,$posts,$requests,$err);
		$this->filterRequest($treeReq);
		if(!$this->_iserror){
			//debugx($requests);
			//debugTree($treeReq);
			$reTree->addItem('uid',$treeReq->getItem('uid'));
			$reTree->addItem('access_token',$treeReq->getItem('access_token'));
			$reTree->addItem('token_secret',$treeReq->getItem('token_secret'));
			$reTree->addItem('expires_in',$treeReq->getItem('expires_in'));
			$reTree->addItem('remind_in',$treeReq->getItem('remind_in'));
			$reTree->addItem('code',$keys->getItem('code'));
		}
		return $reTree;
	}
	
	public function filterRequest(&$treeReq)
	{
		$this->setError(false);
		//debugTree($treeReq);
		if($treeReq->isItem('error_code')){
			$this->setErrorValue('error',$treeReq->getItem('error'));
			$this->setErrorCode($treeReq->getItem('error_code'));
			$this->setErrorType($treeReq->getItem('error_code'));
			$this->setErrorMessage($treeReq->getItem('error_description'));
			$this->setErrorValue('uri',$treeReq->getItem('error_uri'));
			$this->setErrorValue('request',$treeReq->getItem('request'));
			$this->setError(true);
		}
		/*
		debuga($err);
		if($err && $err['code']){
			$this->_error=$err['value'];
			$this->_error_code=$err['code'];
			$this->_error_type=$err['code'];
			$this->_error_message=$err['message'];
			$this->_error_request=$err['request'];
			$this->_iserror=true;
		}
		*/
	}
	
}
