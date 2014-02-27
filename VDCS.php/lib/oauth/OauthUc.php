<?
class OauthUc
{
	
	/*
	########################################
	########################################
	*/
	public static function getAuthTree($authrc,$ua,$uid,$openid='')
	{
		$sqlQuery='';
		if($uid) $sqlQuery='uuid='.$uid;
		if($openid) $sqlQuery='openid='.DB::q($openid,1);
		if(!$sqlQuery && !$uid) $sqlQuery='uuid='.$ua->id;
		//debugx($sqlQuery);
		if($sqlQuery){
			$sql='select * from dbu_oauth where authrc='.DB::q($authrc,1).' and '.$sqlQuery;
			//debugx($sql);
			//dcsLog('OauthUc::getAuthTree',$sql);
			$treeUauth=DB::queryTree($sql);
		}
		else{
			$treeUauth=newTree();
		}
		return $treeUauth;
	}
	
	
	
	
	
}
