<?
class OauthQqAction extends OauthQq
{
	const URL_T_POST			= 'https://graph.qq.com/t/add_t';
	const URL_U_INFO			= 'https://graph.qq.com/user/get_user_info';
	
	
	public static function post($ua,$uid,$message)
	{
		$re=false;
		$treeUauth=OauthUc::getAuthTree(self::AUTHRC,$ua,$uid);
		if(!$treeUauth || $treeUauth->getCount()<1) return 'nobind';
		
		$posts=newTree();
		$posts->addItem('oauth_consumer_key',self::getAppKey());
		$posts->addItem('openid',$treeUauth->getItem('openid'));
		$posts->addItem('access_token',$treeUauth->getItem('authtoken'));
		$posts->addItem('content',$message);
		
		//dcsLog('oauth_consumer_key',self::getAppKey());
		//dcsLog('openid',$treeUauth->getItem('openid'));
		//dcsLog('access_token',$treeUauth->getItem('authtoken'));
		//dcsLog('content',$message);
		$reTree=OauthCommon::getRequestTree(self::URL_T_POST,'POST',$params,$posts,$requests,$err);
		//dcsLog('tree',$reTree);
		//dcsLog('req',$requests);
		
		$re='succeed';
		return $re;
	}
	
	
	public function getUserInfo($ua,$uid,$openid='')
	{
		$reTree=newTree();
		//debugx($uid.','.$openid);
		$treeUauth=OauthUc::getAuthTree(self::AUTHRC,$ua,$uid,$openid);
		//debugTree($treeUauth);
		if(!$treeUauth || $treeUauth->getCount()<1) return $reTree;
		
		$posts=newTree();
		$posts->addItem('access_token',$treeUauth->getItem('authtoken'));
		$posts->addItem('oauth_consumer_key',self::getAppKey());
		$posts->addItem('openid',$treeUauth->getItem('openid'));
		//$posts->addItem('openid','1757091527');
		$reTree=OauthCommon::getRequestTree(self::URL_U_INFO,'GET',$params,$posts,$requests,$err);
		/*
		debugTree($reTree);
		debugx($requests);
		debuga($err);
		*/
		$status=$reTree->getItem('status');
		//$reTree->addItem('status',VDCSData::s($status));
		$reTree->addItem('_name',trim($reTree->getItem('nickname')));
		$reTree->addItem('_avatar',trim($reTree->getItem('figureurl_2')));
		$reTree->addItem('_sign','');
		$reTree->addItem('_gender',$reTree->getItem('gender')=='男'?1:($reTree->getItem('gender')=='女'?2:0));
		return $reTree;
	}
	
}
