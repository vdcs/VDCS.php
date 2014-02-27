<?
class SendSMS
{
	public static $treeConfig=null;
	public static $treeTpl=null;

	public static function getConfigTree()
	{
		$treeConfig=VDCSDTML::getConfigTree('common.config/data/sms');
		$treeConfig=$treeConfig->getFilter('sms.');
		return $treeConfig;
	}
	
	
	public static function classname($module='')
	{
		$classname=__CLASS__.ucfirst($module);
		return $classname;
	}
	public static function instance($module='')
	{
		return self::classname($module);
	}
	
	public static function send($mobile,$message,$params=null,&$rets=null)
	{
		if(!$params) $params=array();
		if(!$rets) $rets=array();
		$re=false;
		$treeConfig=self::getConfigTree();
		//debugTree($treeConfig);
		$module=$treeConfig->getItem('module');
		if(!$module) return $re;
		$classname=self::classname($module);
		$re=$classname::send($treeConfig,$mobile,$message,$params,$rets);
		return $re;
	}
	

	public static function getTpl($key)
	{
		if(!self::$treeTpl) self::$treeTpl=VDCSDTML::getConfigTree('common.config/data/sms.tpl');
		return self::$treeTpl->getItem('sms.'.$key);
	}
	
	public static function getMessage($module,$code,$title='')
	{
		if(!$title) $title=self::getTpl($module.'.title');
		if(!$title) $title='['.$module.']';
		$re=self::getTpl($module.'.message');
		if(!$re) $re=self::getTpl('message');
		if(!$re) $re='尊敬的用户：您本次“{$title}”操作动态码是"{$code}"。请在页面输入该动态码进行下一步操作。';
		if($code) $re=rd($re,'code',$code);
		if($title) $re=rd($re,'title',$title);
		return $re;
	}

}
