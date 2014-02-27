<?
class apiBaseSSO extends apiBaseServe
{
	public $serveType='html';
	
	
	public function initBase()
	{
		$this->authMode('sso');
	}
	
	public function header()
	{
		switch($this->serveType){
			case 'xml':
			case 'results':
				pageHeader('xml');
				put(VDCSXCML::XMLHeader().NEWLINE);
				break;
			case 'html':
			default:
				pageHeader('html');
				break;
		}
	}
	
	public function output()
	{
		switch($this->serveType){
			case 'xml':
			case 'results':
				$this->outputResults();
				break;	
			case 'html':
			default:
				$this->outputRedirect();
				break;	
		}
	}
	
	public function outputRedirect()
	{
		debugxx($this->serve->getStatus());
		if($this->serve->getStatus()=='succeed'){
			$url=$this->serve->getVar('redirect_url');
			$redirect=$this->treeApp->getItemInt('redirect');
			if($redirect>0){
				put('<a href="'.$url.'">SSO请求成功，转接中..</a>');
				put('<meta http-equiv="refresh" content="'.$redirect.'; url='.$url.'" />');
			}
			else{
				go($url);
			}
		}
		else{
			put('SSO请求失败！你来自非洲？！');
		}
	}
	
	public function outputResults()
	{
		if($this->serve->getStatus()!='succeed'){
			put('<status>'.$this->serve->getStatus().'</status>');
			return;
		}
		$result=$this->serve->getVar('results');
		$result=r($result,VDCSXCML::XMLHeader(),'<!--results-->');
		put($result);
	}
	
}
