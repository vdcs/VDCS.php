<?
class TSync
{
	const MAX_SUMMARY		= 250;
	
	
	/*
	########################################
	########################################
	*/
	public static function post($ua,$tData)
	{
		$topic=$tData->getItem('message');
		$sync_url=TCode::urlT($tData->getItem('id'));
		$sync_message=utilCode::toCutt(utilCode::toTxt($topic),50,'..');
		//dcsLog('TSync::post',$sync_message.' '.$sync_url);
		$status=OauthQqAction::doPost($ua,0,$sync_message.' '.$sync_url);
		//dcsLog('TSync::post',$status);
	}
	
	public static function postWeibo($ua,$tData)
	{
		$topic=$tData->getItem('message');
		$sync_url=TCode::urlT($tData->getItem('id'));
		$sync_message=utilCode::toCutt(utilCode::toTxt($topic),50,'..');
		//dcsLog('TSync::post',$sync_message.' '.$sync_url);
		$status=OauthWeiboAction::doPost($ua,0,$sync_message.' '.$sync_url);
		//dcsLog('TSync::post',$status);
	}
	
}
?>