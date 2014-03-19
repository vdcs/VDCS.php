<?
trait CommonRefTestX
{
	
	protected function parseSendmail()
	{
		$email=query('email');
		$message=query('message');
		$service=queryx('service');
		$tpl=queryx('tpl');
		$subject=query('subject');
		$content=query('content');
		if(!utilCheck::isEmail($email)) $email='m@vipx.cn';
		
		$tpl=$tpl?$tpl:'common.test';
		$subject=$subject?$subject:'tpl';
		$content=$content?$content:'tpl';
		
		$this->addVar('send.email',$email);
		$this->addVar('send.message',$message);
		$this->addVar('send.service',$service);
		$this->addVar('mail.tpl',$tpl);
		$this->addVar('mail.subject',$subject);
		$this->addVar('mail.content',$content);

		$send_opt=array();
		$send_opt['service']=$service;
		$send_opt['tpl']=$tpl;
		$send_opt['vars']=array();
		$send_opt['vars']['email']=$email;
		$send_opt['vars']['message']=$message;
		$re=Sender::sendMail($email,$subject,$content,$send_opt);
		if($re==1){
			$this->setSucceed();
		}
		else{
			$this->setStatus('status'.$re);
			$this->addVar('error_type',$send_opt['error_type']);
			$this->addVar('error_desc',$send_opt['error_desc']);
		}
	}

}
