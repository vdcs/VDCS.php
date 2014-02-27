<?
trait WebServeRefAid
{
	
	protected function setSucceed(){$this->setStatus('succeed');}
	protected function getStatus($k='status'){return $this->getVar($k);}
	protected function setStatus($v,$msg=''){$this->addVar('status',$v);if($msg)$this->setMessage($msg);}
	protected function getMessage($k='message'){return $this->getVar($k);}
	protected function setMessage($v,$k='message'){$this->addVar($k,$v);}
	protected function setMessages($tit,$msg,$url='')
	{
		if($tit) $this->addVar('title',$tit);
		$this->setMessage($msg);
		if($url) $this->addVar('backurl',$url);
	}
	
}
?>