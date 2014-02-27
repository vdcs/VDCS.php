<?
class OauthQq
{
	
	const AUTHRC				= 'qq';
	/*	
	const URL_HOST				= 'https://open.t.qq.com/api';
	const URL_AUTHORIZE			= 'https://open.t.qq.com/cgi-bin/oauth2/authorize';   
	const URL_ACCESS_TOKEN			= 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
	
	const URL_USER_INFO			= 'https://open.t.qq.com/api/user/info';
	*/
	const URL_HOST				= 'https://open.t.qq.com';
	const URL_AUTHORIZE			= 'https://graph.qq.com/oauth2.0/authorize';   
	const URL_ACCESS_TOKEN			= 'https://graph.qq.com/oauth2.0/token';
	
	const URL_USER_OPENID			= 'https://graph.qq.com/oauth2.0/me';
	const URL_USER_INFO			= 'https://graph.qq.com/user/get_user_info';
		
	//const URL_SHORTURL_EN			= 'https://open.t.qq.com/api/short_url/shorten';
	//const URL_SHORTURL_EX			= 'https://open.t.qq.com/api/short_url/expand';
	
	public static function getAppKey()
	{
		return OauthCommon::getConfigTree()->getItem(self::AUTHRC.'.id');
	}
	
	/*
	public static function toShortURL($url)
	{
		$re='';
		$posts=newTree();
		$posts->addItem('oauth_consumer_key',self::getAppKey());
		$posts->addItem('url_long',$url);
		$reTree=OauthCommon::getRequestTree(self::URL_SHORTURL_EN,'GET',$params,$posts,$requests,$err);
		$urls=$reTree->getItem('urls');
		//debuga($urls);
		if($urls && $urls[0]) $re=$urls[0]->url_short;
		return $re;
	}
	public static function toShortURLx($url)
	{
		$re='';
		$posts=newTree();
		$posts->addItem('oauth_consumer_key',self::getAppKey());
		$posts->addItem('url_short',$url);
		$reTree=OauthCommon::getRequestTree(self::URL_SHORTURL_EX,'GET',$params,$posts,$requests,$err);
		$urls=$reTree->getItem('urls');
		//debuga($urls);
		if($urls && $urls[0]) $re=$urls[0]->url_long;
		return $re;
	}
	
	*/
}
