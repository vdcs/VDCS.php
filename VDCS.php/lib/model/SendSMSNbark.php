<?
class SendSMSNbark
{
	const API_URL			= 'http://hy6.nbark.com:7602/';
	const ROUTE_CT			= 'http://sms1.nbark.com:8002/';
	const ROUTE_CNC			= 'http://sms2.nbark.com:8002/';
	
	public static function send($treeConfig,$mobile,$message,$params=null,&$rets=null)
	{
		if(!$params) $params=array();
		if(!$rets) $rets=array();
		if(isTree($params)) $params=$params->getArray();
		$isdebug=$params['debug'];
		if(!$isdebug && DEBUGV=='sms') $isdebug=true;
		$re=false;
		$url=$treeConfig->getItem('url');
		//$routename='ROUTE_'.toUpper($route?$route:'ct');
		if(!$url){
			switch($treeConfig->getItem('route')){
				case 'cnc':	$url=self::ROUTE_CNC;break;
				case 'ct':
				default:	$url=self::ROUTE_CT;break;
			}
			$url=self::API_URL;
		}
		$url.='sms.aspx';
		if($isdebug) debugx($url);
		
		$posts=newTree();
		$posts->addItem('userid',$treeConfig->getItem('uid'));
		$posts->addItem('account',$treeConfig->getItem('id'));
		$posts->addItem('password',$treeConfig->getItem('key'));
		$posts->addItem('action','send');
		$posts->addItem('mobile',$mobile);
		$posts->addItem('content',$message);
		$posts->addItem('sendTime','');
		$posts->addItem('extno','');
		if($isdebug) debugTree($posts);
		
		$results=VDCSHTTP::request($url,[],$posts);
		if($isdebug) debugx($results);
		$oxml=simplexml_load_string($results);
		if($isdebug) debuga($oxml);
		$rets['results']=$results;
		$rets['status']=toLower($oxml->returnstatus);
		$rets['returnstatus']=$oxml->returnstatus;
		$rets['message']=$oxml->message;
		$rets['remainpoint']=$oxml->remainpoint;
		$rets['taskid']=$oxml->taskID;
		$rets['successCounts']=$oxml->successCounts;
		if($isdebug) debuga($rets);
		
		if($rets['status']=='success') $re=true;	//succeed
		return $re;
	}
	
}
