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
	
	public static function push($module,$action,$actionid,$host,$vars,$timespace=0,$round=0,$content=null,$priority=0)
	{
		$params=newTree();
		
		$post=[];
		$post['module']=$module;
		$post['action']=$action;
		$post['actionid']=$actionid;
		$post['host']=$host;
		$post['vars']=$vars;
		$post['timespace']=$timespace;
		$post['round']=$round;
		$post['content']=$content;
		$post['priority']=$priority;
		
		$ret=array();
		$opt=array();
		$opt['api']='push';
		$opt['server']='xmq';
		$ret=self::interRequest($opt,$params,$post);
		return $ret;
	}
	
}
