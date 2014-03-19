<?
class XMQService
{
	const PRIORITY_BASE			= 20;
	
	public static function interRequest($opt,$params,$paramhttp=array())
	{
		if(!isa($opt)) $opt=array('api'=>$opt);
		if(!$opt['server']) $opt['server']='api_inter';
		return AppsApp::parser($opt,$params,$paramhttp);
	}
	
	public static function push($module,$action,$actionid,$vars,$content=null,$timespace=0,$priority=0,$round=0,$host=null)
	{
		$params=newTree();
		
		//debuga($vars);
		if(!iss($vars)) $vars=VDCSData::enCode($vars);
		//debugx($vars);
		$host=$host?$host:DCS::urlHost();

		$post=[];
		$post['module']=$module;
		$post['action']=$action;
		$post['actionid']=$actionid;
		$post['host']=$host;
		$post['vars']=$vars;
		$post['content']=$content;
		$post['timespace']=$timespace;
		$post['round']=$round;
		$post['priority']=$priority;
		
		$ret=array();
		$opt=array();
		$opt['api']='push';
		$opt['server']='xmq';
		$ret=self::interRequest($opt,$params,$post);
		return $ret;
	}
	
}
