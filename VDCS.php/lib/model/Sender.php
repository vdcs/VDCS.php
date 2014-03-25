<?
class Sender
{
	
	public static function parseMail($email,$subject='tpl',$content='tpl',&$opt=null)
	{
		$re=null;$ret=null;
		if(!$ret) $ret=array();
		if(!$ret['status'])$ret['status']='ready';
		if($email) $re=self::mail($email,$subject,$content,$opt);

		$ret['send_status']=$re;
		if($re==1 || $re=='succeed'){
			$ret['status']='succeed';
		}
		else{
			$ret['status']='failed';
			$ret['error_type']=$send_opt['error.type'];
			$ret['error_status']=$send_opt['error.desc'];
		}
		return $ret;
	}

	public static function sendMail($email,$subject='tpl',$content='tpl',&$opt=null){return self::mail($email,$subject,$content,$opt);}	//hold
	public static function mail($email,$subject='tpl',$content='tpl',&$opt=null)
	{
		if(!$opt) $opt=array();
		if(!$opt['vars']) $opt['vars']=array();
		if(!$opt['vars']['time.now']) $opt['vars']['time.now']=DCS::now();
		if(!$opt['vars']['time.today']) $opt['vars']['time.today']=DCS::today();

		if(SendMail::cfg('service')=='xmq' && $opt['service']!=='direct'){
			//{"test":"yes","tpl":"common.test","email":"m@vipx.cn","vars":{"name":"Ranom","time":"2014-03-04"}}
			$vars=array();
			$vars['tpl']=$opt['tpl'];
			$vars['email']=$email;
			$vars['vars']=$opt['vars'];
			//$vars['test']='yes';
			//$vars['tpl']='common.test';
			
			//$module,$action,$actionid,$vars,$content=null,$timespace=0,$priority=0,$round=0,$host=null
			$ret=XMQService::push('send','mail',0,$vars,$content=='tpl'?'':$content);
			$re=-1;
			if($ret['var.status']=='accept') $re=1;
			return $re;
		}

		$treeTpl=VDCSDTML::getConfigTree('vdcs.config/data/sendmail.tpl');
		$treeTpl->doAppendTree(VDCSDTML::getConfigTree('common.config/data/sendmail.tpl'));
		$tpl=$opt['tpl'];
		if(!$treeTpl->isItem($tpl.'.content')){
			$tpl=$opt['tpl.base'];
			if(!$treeTpl->isItem($tpl.'.content')) $tpl='';
		}
		if(!$tpl){
			return -100;
		}
		if(!isset($opt['format'])) $opt['format']=$treeTpl->getItem($opt['tpl'].'.format');
		if($subject=='tpl'){
			//debugx($opt['tpl'].'.subject');
			$subject=$treeTpl->getItem($opt['tpl'].'.subject');
			$subject=utilRegex::toReplaceVar($subject,utilArray::toTree($opt['vars']));
		}
		if($content=='tpl'){
			$content=$treeTpl->getItem($opt['tpl'].'.content');
			$content=utilRegex::toReplaceVar($content,utilArray::toTree($opt['vars']));
		}
		//debugx($subject);
		//debugx($content);
		//$content='';
		return SendMail::post($email,$subject,$content,$opt);
	}
	
}
