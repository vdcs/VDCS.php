<?
trait WebPortalRefMessage
{
	protected static $StatusIcon		= 'info,succeed,error';
	
	public function demo()
	{
		WebHandleMessage::msg('status');
		//WebHandleMessage::att('icon','info');		//info,succeed,failed,error
		WebHandleMessage::att('topic','测试主题');
		WebHandleMessage::att('explain','这里是描述(Explain)内容');
		WebHandleMessage::att('message','这里是信息(Message)内容');
		WebHandleMessage::btn('goback');
		WebHandleMessage::btn('root','首页页名称','http://www.joekoe.com/article/');
		WebHandleMessage::btn('back','返回页名称','http://www.joekoe.com/article/list.asp');
	}
	
	public static function lang($type,$param1='',$param2='')
	{
		$re='';
		switch($type){
			case 'tit':
				switch($param1){
					case 'succeed':		$re='操作成功'; break;
					case 'failed':		$re='操作失败'; break;
					case 'error':		$re='操作错误'; break;
				}
				break;
			case 'back':		$re='返回'; break;
			case 'root':		$re='首页'; break;
		}
		return $re;
	}
	
	public static function msg($state,$st=1)
	{
		utilString::lists($state,$status,$tit,':');
		//list($status,$tit)=explode(':',$state);
		if($st==1 && len($tit)<1) $tit=self::lang('tit',$status);
		self::att('status',$status);
		if(inp(self::$StatusIcon,$status)>0) self::att('icon',$status);
		if(len($tit)>0) self::att('topic',$tit);
	}
	
	public static function btn($btn,$name='',$url='')
	{
		self::att('btn.'.$btn,'show');
		if($name) self::att('btn.'.$btn.'.name',$name);
		if($url) self::att('btn.'.$btn.'.url',$url);
	}
	
	public static function att($k,$v)
	{
		global $ctl;
		$ctl->treeDTML->addItem('_hmsg.'.$k,$v);
	}
}
?>