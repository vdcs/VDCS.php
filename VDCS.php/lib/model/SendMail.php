<?
class SendMail
{
	
	public static function post($email,$subject,$message,&$opt=null)
	{
		if(!$opt) $opt=array();
		$treeConfig=VDCSDTML::getConfigTree('common.config/data/sendmail');
		$treeConfig=$treeConfig->getFilterTree('config.');
		//debugTree($treeConfig);
		if(!$opt['from.email']) $opt['from.email']=$treeConfig->getItem('from.email');
		if(!$opt['from.email']) $opt['from.email']=$treeConfig->getItem('smtp.user');
		if(!$opt['from.name']) $opt['from.name']=$treeConfig->getItem('from.name');
		//if(!$opt['from.name']) $opt['from.name']=appv('web.master');
		if(!$opt['from.name']) $opt['from.name']=appv('web.shortname');
		if(!$opt['from.name']) $opt['from.name']=appv('web.name');
		if(!$opt['to.email']) $opt['to.email']=$email;
		//return self::postByMail($email,$subject,$message,$opt,$treeConfig);
		if(!$email) return -102;
		if(!$subject) return -102;
		if(!$message) return -103;
		return self::postByMailer($email,$subject,$message,$opt,$treeConfig);
	}
	public static function postByMail($email,$subject,$message,&$opt=null,$treeConfig=null)
	{
		$re=false;
		$module=$treeConfig->getItem('module');
		if(!$module) return -101;
		$sm=new utilMail();
		$sm->init($treeConfig->getFilterTree('smtp.')->getArray());
		$sm->setMode($treeConfig->getItem('module'));
		$sm->setFrom($opt['from.email'],$opt['from.name']);
		$sm->addTo($opt['to.email'],$opt['to.name']);
		$sm->setSubject($subject);
		$sm->setMessage($message);
		$re=$sm->doSend();
		if(!$re){
			$opt['error.type']=$sm->getErrorType();
			$opt['error.desc']=$sm->getErrorDescription();
			dcsLog('SendMail::postByMail','error.type='.$opt['error.type']);
			dcsLog('SendMail::postByMail','error.desc='.$opt['error.desc']);
		}
		unset($sm);
		return $re;
	}
	public static function postByMailer($email,$subject,$message,&$opt=null,$treeConfig=null)
	{
		$re=false;
		$module=$treeConfig->getItem('module');
		if(!$module) return -101;
		$sm=new utilMailer();
		$sm->IsSMTP();					// 使用SMTP方式发送
		if($module=='smtp') $sm->SMTPAuth = true;
		if($opt['debug']) $sm->SMTPDebug = $opt['debug'];
		$sm->SMTPSecure = $treeConfig->getItem($module.'.secure');
		$sm->Host = $treeConfig->getItem($module.'.host');
		if(!$sm->Host) $sm->Host = $treeConfig->getItem($module.'.server');
		$sm->Port=$treeConfig->getItem($module.'.port');
		$sm->Username = $treeConfig->getItem($module.'.user');
		if(!$sm->Username) $sm->Username = $treeConfig->getItem($module.'.username');
		$sm->Password = $treeConfig->getItem($module.'.pass');
		if(!$sm->Password) $sm->Password = $treeConfig->getItem($module.'.password');
		$sm->CharSet = $treeConfig->getItem('charset');
		if(!$sm->CharSet) $sm->CharSet=CHARSET_HTML;
		$sm->SetFrom($opt['from.email'],$opt['from.name']);
		$sm->AddAddress($opt['to.email'],$opt['to.name']);		//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
		//$sm->AddReplyTo("", "");
		
		//$sm->AddAttachment("/var/tmp/file.tar.gz");	// 添加附件
		if($opt['format']=='html') $sm->IsHTML(true);				// set email format to HTML //是否使用HTML格式
		$sm->Subject = $subject;
		$sm->Body = $message;
		if($opt['text']) $mail->AltBody = $opt['text']; //附加信息，可以省略 "This is the body in plain text for non-HTML mail clients"
		$re=$sm->Send();
		if(!$re){
			$opt['error.type']='error';
			$opt['error.desc']=$sm->ErrorInfo;
			dcsLog('SendMail::postByMailer','error.type='.$opt['error.type']);
			dcsLog('SendMail::postByMailer','error.desc='.$opt['error.desc']);
		}
		unset($sm);
		return $re;
	}
	
}
