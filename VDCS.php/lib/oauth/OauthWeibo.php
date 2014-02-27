<?
class OauthWeibo
{
	
	const AUTHRC				= 'weibo';
	const URL_HOST				= 'https://api.weibo.com/2/';
	const URL_AUTHORIZE			= 'https://api.weibo.com/oauth2/authorize';   
	const URL_ACCESS_TOKEN			= 'https://api.weibo.com/oauth2/access_token';
	
	const URL_USER_INFO			= 'https://api.weibo.com/oauth2/users/show';
	
	
	const URL_SHORTURL_EN			= 'https://api.weibo.com/2/short_url/shorten.json';
	const URL_SHORTURL_EX			= 'https://api.weibo.com/2/short_url/expand.json';
	
	
	public static function getAppKey()
	{
		return OauthCommon::getConfigTree()->getItem(self::AUTHRC.'.id');
	}
	
	public static function toShortURL($url)
	{
		$re='';
		$posts=newTree();
		$posts->addItem('source',self::getAppKey());
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
		$posts->addItem('source',self::getAppKey());
		$posts->addItem('url_short',$url);
		$reTree=OauthCommon::getRequestTree(self::URL_SHORTURL_EX,'GET',$params,$posts,$requests,$err);
		$urls=$reTree->getItem('urls');
		//debuga($urls);
		if($urls && $urls[0]) $re=$urls[0]->url_long;
		return $re;
	}
	
	
}
