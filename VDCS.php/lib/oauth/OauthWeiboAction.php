<?
class OauthWeiboAction extends OauthWeibo
{
	const URL_T_POST			= 'https://api.weibo.com/2/statuses/update.json';
	const URL_U_INFO			= 'https://api.weibo.com/2/users/show.json';
	
	
	public static function post($ua,$uid,$message)
	{
		$re=false;
		$treeUauth=OauthUc::getAuthTree(self::AUTHRC,$ua,$uid);
		if(!$treeUauth || $treeUauth->getCount()<1) return 'nobind';
		
		$posts=newTree();
		$posts->addItem('access_token',$treeUauth->getItem('authtoken'));
		$posts->addItem('status',$message);
		$reTree=OauthCommon::getRequestTree(self::URL_T_POST,'POST',$params,$posts,$requests,$err);
		/*
		debugTree($reTree);
		debugx($requests);
		debuga($err);
		*/
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
		$posts->addItem('uid',$treeUauth->getItem('openid'));
		//$posts->addItem('uid','1757091527');
		$reTree=OauthCommon::getRequestTree(self::URL_U_INFO,'GET',$params,$posts,$requests,$err);
		/*
		debugTree($reTree);
		debugx($requests);
		debuga($err);
		*/
		$status=$reTree->getItem('status');
		$reTree->addItem('status',VDCSData::s($status));
		$reTree->addItem('_name',trim($reTree->getItem('name')));
		$reTree->addItem('_avatar',trim($reTree->getItem('avatar_large')));
		$reTree->addItem('_sign',trim($reTree->getItem('description')));
		$reTree->addItem('_gender',$reTree->getItem('gender')=='m'?1:($reTree->getItem('gender')=='f'?2:0));
		return $reTree;
	}
	
}
