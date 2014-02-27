<?
trait ManageRefMessage
{
	
	protected function messageFilter(&$tit,&$msg,&$url,&$status)
	{
		if(left($tit,1)=='!'){
			$tit=toSubstr($tit,2);
			if(len($tit)>0) $tit=$this->config('message.title.'.$tit,'language','','');
			if(len($tit)<1) $tit=$this->config('message.title','language');
		}
		if(left($msg,1)=='!'){
			$msg=toSubstr($msg,2);
			$msg=$this->config('message.'.$msg,'language');
		}
		if(!$status) $status='info';
		if($status=='!') $status='succeed';
		if($status=='x') $status='error';
	}
	
	protected function messageSet($tit='',$msg='',$url='',$status='')
	{
		$this->theme->setSubdir('');
		$this->theme->setPage('pagex');
		$this->theme->setModule('');
		$this->theme->setModulei('');
		$this->theme->setAction('message');
		$this->ctl->addDTML('tip.status',$status);
		$this->ctl->addDTML('tip.title',$tit);
		$this->ctl->addDTML('tip.message',$msg);
		$this->ctl->addDTML('tip.backurl',$url);
		/*
		if($tit){
			$re=$this->ctl->ui->getMessage($tit,$msg,$url);
			$this->ctl->addDTML('_pages.message',$re);
		}
		else $re=$this->ctl->doRaiseMessage($msg,$url);
		return $re;
		*/
	}
	
	public function setMessages($tit='',$msg='',$url='',$status='')
	{
		$this->messageFilter($tit,$msg,$url,$status);
		parent::setMessages($tit,$msg,$url);
		if(!$this->serveType){
			$this->messageSet($tit,$msg,$url,$status);
		}
	}
	public function doMessages($tit='',$msg='',$url='',$status=''){return $this->setMessages($tit,$msg,$url,$status);}

	public function doMessageHint($msg,$type='',$sort='')
	{
		$sortx=$sort?'.'.$sort:'';
		if($type=='append'){
			$msg=$this->ctl->getDTML('tip.hint'.$sortx).$msg;
		}
		$this->ctl->addDTML('tip.hint'.$sortx,$msg);
	}
	
	/*
	########################################
	########################################
	*/
	/*
	protected function doMessage($type,$state,$msg='',$url='')
	{
		$this->doMessageBase($type,$state,$msg,$url);
	}
	protected function doMessageBase($type,$state,$msg='',$url='')
	{
		switch($type){
			case 'common':
				$this->theme->setPage('handle');
				$this->theme->setModule('message');
				$this->theme->setAction('');
				$this->theme->setStatus('');
				break;
			case 'none':
				break;
			default:
				$this->theme->setModule('handle');
				break;
		}
		$this->doBoxMessage($state,$msg,$url);
	}
	protected function doBoxMessage($state,$msg='',$url='')
	{
		global $ctl;
		utilString::lists($state,$status,$tit,':');
		//list($status,$tit)=explode(':',$state);
		if(len($tit)<1) $tit=$this->_box['title.'.$status];
		$state=$status.':'.$tit;
		WebHandleMessage::msg($state,1);
		if($msg) WebHandleMessage::att('explain',$msg);
		if($url) WebHandleMessage::btn('back',WebHandleMessage::lang('back'),$url);
	}
	protected function boxSet($k,$v){$this->_box[$k]=$v;}
	*/

}
?>