<?
class ChannelPassportOauthX extends ChannelPassportBaseX
{
	use WebPortalRefAuthX;
	use PassportOauthRef;
	
	/*
	########################################
	########################################
	*/
	public function doAuth()
	{
		$this->isauth=true;
		if(inp('bind,unbind',query('action'))>0){
			$this->doAuthx();
		}
	}
	
	public function doParse()
	{
		$this->setStatus('init');
		if(!$this->oauthInit()) return;
		if($this->oa->isPause()){
			$this->setStatus('pause');
			return;
		}
		switch($this->action){
			case 'info':
				$this->doParseInfo();
				break;
			case 'bind':
				$this->doParseBind();
				break;
			case 'unbind':
				$this->doParseUnbind();
				break;
		}
	}
	
	public function doParseInfo()
	{
		$authrc=queryx('authrc');
		$uid=queryi('uid');
		$openid=queryx('openid');
		if(!$openid && !$uid) $uid=$this->ua->id;
		if($uid) $this->oa->setVar('uid',$uid);
		if($openid) $this->oa->setVar('openid',$openid);
		if(!$uid && !$openid){
			$this->setStatus('noquery');
			return;
		}
		switch($authrc){
			case 'weibo':
				$treeUinfo=OauthWeiboAction::getUserInfo($this->ua,$this->oa->getVar('uid'),$this->oa->getVar('openid'));
				break;
			case 'qq':
				$treeUinfo=OauthQqAction::getUserInfo($this->ua,$this->oa->getVar('uid'),$this->oa->getVar('openid'));
				break;
			default:
				$this->setStatus('authrc');
				break;
		}
		//debugTree($treeUinfo);
		if($treeUinfo->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		$this->addVarTree($treeUinfo,'info.');
		$this->setStatus('succeed');
	}
	
	public function doParseBind()
	{
		$this->oa->setVar('openid',queryx('openid'));
		if(!$this->oa->getVar('openid')){
			$this->setStatus('openid');
			return;
		}
		
		$this->oa->bindInit(false);
		
		//debugx($this->oa->getVar('openid').', '.$this->oa->getVar('uuid')&', '.$this->ua->id);
		if($this->oa->isBind()){
			$this->setStatus('binded');
		}
		else{
			$this->oa->setVar('uuid',$this->ua->id);
			if($this->oa->isBindU()){
				$this->setStatus('binded');
			}
			else{
				$this->oa->bindUID();
				$this->setStatus('succeed');
			}
		}
	}
	
	public function doParseUnbind()
	{
		$this->oa->bindInit(false);
		$this->oa->setVar('uuid',$this->ua->id);
		if(!$this->oa->isBindU()){
			$this->setStatus('nobind');
			return;
		}
		$this->oa->unbindUID();
		$this->setStatus('succeed');
	}
	
}
