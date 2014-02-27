<?
class OauthsAction extends Oauths
{
	
	public static function authorizer($ua,$opt)
	{
		if(!isa($opt)) return false;
		$opt['uurc']=$ua->rc;$opt['uuid']=$ua->id;
		if(!$opt['uuid'] || !$opt['appid'] || !$opt['appkey']) return false;
		$opt['encrypt']='md5';
		$opt['ip']=DCS::ip();
		$opt['session']=DCS::sessionid();
		$opt['timer']=timer();
		$opt['tim']=DCS::tim();
		
		//##########
		$tokenstr=$uid.','.$opt['appid'].','.$opt['appkey'].','.$opt['timer'];
		//debugx($tokenstr);
		$opt['token']=self::toEncrypt($tokenstr,$opt['encrypt']);
		$opt['token2']='';
		//########## token
		$tData=newTree();
		$tData->addItem('sid',0);
		$tData->addItem('uurc',$opt['uurc']);
		$tData->addItem('uuid',$opt['uuid']);
  		
		$tData->addItem('appid',$opt['appid']);
		$tData->addItem('key',$opt['appkey']);
  		
		$tData->addItem('encrypt',$opt['encrypt']);
		$tData->addItem('token',$opt['token']);
		$tData->addItem('token2',$opt['token2']);
		$tData->addItem('scope',$opt['scope']);
  		
		$tData->addItem('value1',$opt['value1']);
		$tData->addItem('value2',$opt['value1']);
		$tData->addItem('value3',$opt['value1']);
		$tData->addItem('value4',$opt['value1']);
		$tData->addItem('value5',$opt['value1']);
  		
		$tData->addItem('ip',$opt['ip']);
		$tData->addItem('session',$opt['session']);
		$tData->addItem('timer',$opt['timer']);
  		
		$tData->addItem('status',1);
		$tData->addItem('tim',$opt['tim']);
		
		$opt['tokenid']=OauthsToken::add($tData,$opt);
		//########## code
		$opt['code']=self::toEncryptCode($tokenstr.$opt['tokenid']);
		$tData->addItem('code',$opt['code']);
		$tData->addItem('state',$opt['state']);
		$opt['codeid']=OauthsCode::add($tData,$opt);
		//##########
		
		$re=array();
		$re['token']=$token;
		$re['code']=$code;
		$re=$opt;
		return $re;
	}
	
}
