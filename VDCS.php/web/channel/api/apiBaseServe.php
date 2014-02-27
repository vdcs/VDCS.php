<?
class apiBaseServe
{
	use WebServeRefBase,WebServeRefAid;
	public $serveType			= 'xml';
	
	public $ua=null;
	public $isauth=true;
	
	
	/*
	########################################
	########################################
	*/
	public function authiModel($value=null){return $this->oauth->authiModel($value);}
	public function authMode($value=null){return $this->oauth->authMode($value);}
	
	public function authReset()
	{
		$this->authiModel('');
		$this->authMode('');
	}

	
	/*
	########################################
	########################################
	*/
	public function initer($entry,$p,$m,$mi,$action)
	{
		$this->_entry_=$entry;
		$this->_p_=$p;
		$this->_m_=$m;
		$this->_mi_=$mi;
		$this->action=$action;
		$this->ip=DCS::ip();
		$this->timer=DCS::timer();
		debugxx('_p_='.$this->_p_.', _m_='.$this->_m_.', _mi_='.$this->_mi_.', action='.$this->action.', timer='.$this->timer.', ip='.$this->ip);
		debugxx($_SERVER['REQUEST_URI']);

		$this->oauth=new ApiAuth();
		$this->oauth->that=&$this;
		$this->isauth=&$this->oauth->isauth;
	}
	
	public function initBase(){}
	public function init(){}

	public function auther(){}
	public function auth(){}
	public function authed()
	{
		//$this->auther();
		/*
		switch($this->_entry_){
			case 'interface':		$this->authMode('interface');break;
		}
		*/
		$this->auth();
		$this->oauth->auther();
		if(!$this->isauth){
			$authiStatus=$this->oauth->authiStatus();
			$this->addVar('authi_model',$this->oauth->authiModel());
			$this->addVar('authi_status',$authiStatus);
			$this->addVar('auth_mode',$this->oauth->authStatus());
			$this->addVar('auth_status',$this->oauth->authStatus());
			$this->setStatus($authiStatus?$authiStatus:'noauth');
		}
	}

	public function load(){}

	public function parser()
	{
		$funcname='parse'.ucfirst($this->action);
		//method_exists,is_callable
		if(!method_exists($this,$funcname)){
			$this->setStatus('noaction');
			return;
		}
		$this->$funcname();
	}
	
	public function parse()
	{
		$this->addVar('parser','parse');
	}
	public function parseDemo()
	{
		$this->addVar('parser','parseDemo');
	}

}
