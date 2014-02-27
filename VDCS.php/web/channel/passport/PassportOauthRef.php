<?
trait PassportOauthRef
{
	
	protected function oauthInit()
	{
		$this->oa=new OauthAgent();
		$this->oa->doInit();
		
		if(!$this->AUTHRC) $this->AUTHRC=queryx('authrc');
		if(!$this->AUTHRC) $this->AUTHRC=$this->_m_;
		//$this->oa->setVar('authrc',$this->AUTHRC);
		$this->oa->setRC($this->AUTHRC);		//oauth2.0
		$this->oa->doLoad();
		if(!$this->oa->isLoad()) return false;
		
		$this->oa->setVar('uurc',$this->ua->rc);
		
		$this->addVar('title','开放平台');
		$this->addVar('refresh.second',DCS::isLocal()?3000:3);
		$this->addVar('authrc',$this->AUTHRC);
		$this->addVar('auth.name',$this->oa->getConfig('name'));
		return true;
	}
	
}
?>