<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parseCheck();
	}
	public function parseCheck()
	{
		$auth_code=queryx('auth_code');
		$treeCode=Oauth2Action::getCodeTree($auth_code);
		if($treeCode->getCount()<1){
			$this->setStatus('nocode');
			return;
		}
		$this->addVar('uid',$treeCode->getItemInt('uuid'));
		
		
		$this->setStatus('succeed');
	}
	
}
