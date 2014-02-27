<?
class OauthCommon
{
	
	public static function getConfigTree()
	{
		$treeConfig=VDCSDTML::getConfigTree('common.config/data/oauth');
		return $treeConfig;
	}
	
	
	//URL连接&编码
	public static function toURLQuery($url,$params){return DCS::urlLink($url,$params);}		//hold
	public static function toURLEncode($re){return DCS::urlEncode($re);}				//hold
	
	public function getRequest($url,$method='',$params=null,$posts=null,$headers=null)
	{
		if(!$params) $params=newTree();
		if($method) $params->addItem('method',$method);
		return VDCSHTTP::request($url,$params,$posts,$headers);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getRequestTree($url,$method,$params=null,$posts=null,&$requests=null,&$err=null)
	{
		if(!$params) $params=newTree();
		//if(isTree($params)) $params=$params->getArray();
		if($params && !$method) $method='POST';
		if($params){
			//$params->addItem('access.token','2.00GYhTjCl_APjD645a3b23a2U2SZTB');
		}
		if($posts){
			$params->addItem('access.token',$posts->getItem('access_token'));
		}
		/*
		debugx($url);
		debugTree($params);
		debugTree($posts);
		*/
		$requests='';
		switch($method){
			case 'POST':
				$requests=self::getRequest($url,$method,$params,$posts,$headers);
				break;
			case 'GET':
			default:
				$url=DCS::urlLink($url,$posts);
				//debugx($url);
				//dcsLog('urlurl',$url);
				$requests=self::getRequest($url,$method,$params,null,$headers);
				break;
		}
		//debugx('url='.$url);
		//dcsLog('url',$url);
		//debugx('request='.$requests);
		//针对qq
		if($url=='https://graph.qq.com/oauth2.0/me'){
			$s1=strpos($requests,'(');
			$s2=strpos($requests,')');
			$len=$s2-$s1;
			$requests=substr($requests,$s1+1,$len-1);
		}
		
		$treeReq=VDCSData::JsonToTree($requests);
		
		//针对qq
		if($url=='https://graph.qq.com/oauth2.0/token'){
			$reArr=explode('&',$requests);
			$access_token=ltrim(strstr($reArr[0],'='),'=');
			$treeReq->addItem('access_token',$access_token);	
		}
		//结束
		$err=array();
		if($treeReq->isItem('error_code')){
			$err['value']=$treeReq->getItem('error');
			$err['code']=$treeReq->getItem('error_code');
			$err['message']=$treeReq->getItem('error_description');
			$err['request']=$treeReq->getItem('request');
		}
		
		//debugx($requests);
		//debugTree($treeReq);
		return $treeReq;
	}
	
	
}
