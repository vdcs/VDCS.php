<?
class XMQService
{
	const PRIORITY_BASE			= 20;
	
	public static function push($module,$action,$vars,$timespace=0,$content=null,$priority=0)
	{
		$params=[];
		
		$post=[];
		$post['module']=$module;
		$post['action']=$action;
		$post['vars']=$vars;
		$post['timespace']=$timespace;
		$post['content']=$content;
		$post['priority']=$priority;
		
		$url='http://localhost:8310/xmq/dispatch.realtime';
		//$url=DCS::urlLink($url,$querys);
		
		$ret=VDCSHTTP::request($url,$params,$post);
		return $ret;
	}
	
}
