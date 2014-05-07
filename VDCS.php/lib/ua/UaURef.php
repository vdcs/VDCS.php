<?
define('INC__UaURef',true);

trait UaURefBase
{
	public $rc='',$id=0;
	public $_cfg=array(),$_data=array();
	public $_isinit=false,$_islogin=false;
	
	protected function __constructBase()
	{
		$this->_cfg['UPDATE_SPACE']			= 300;

		$this->_cfg['auth']				= -1;
		$this->_cfg['auth.mode']			= -1;
		$this->_cfg['auth.model']			= 'cookie';
		
		$this->_cfg['url.mode']				= 1;
		
		$this->_cfg['cookie']				= false;

		//data default
		$this->_data['_sid']='';
		$this->_data['id']=$this->id;
	}

	public function initBase()
	{
		$this->_data['_rc']=$this->rc;
		$this->_data['_id']=0;
		$this->_data['_o']=0;
	}
	public function initData($real=true)
	{
		$_o=1;
		if($real && !$this->_islogin){
			$_o=0;
			$this->id=0;
			$this->_data=array();
			$this->_data['id']=$this->id;
			$this->_data['name']=Ua::prename();
		}
		$this->_data['_rc']=$this->rc;
		$this->_data['_id']=$this->id;
		$this->_data['_o']=$_o;
		$this->_data['id']=$this->id;
		$this->_data[$this->FieldID]=$this->id;
		$_names=Ua::toNames($this->_data);
		$this->_data['names']=$_names;
		$this->_data['_names']=$_names;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getDataTree()
	{
		$reTree=new utilTree();
		$reTree->setArray($this->_data);
		return $reTree;
	}
	public function getData($k){return $this->_data[$k];}
	public function getDataInt($k){return toInt($this->_data[$k]);}
	public function getDataNum($k){return toNum($this->_data[$k]);}
	public function setData($k,$v){$this->_data[$k]=$v;}
	public function setDataTree($strTree){$this->_data=$strTree->getArray();}
	
	public function setID($id){$this->id=$id;$this->setData('id',$id);$this->setData('_id',$id);}
	public function getNames(){return Ua::toNames($this->_data);}


	/*
	########################################
	########################################
	*/
	public function isInit(){return $this->_isinit;}
	public function isLogin(){return $this->_islogin;}

	public function doReInit()
	{
		$this->_isinit=false;
		$this->doInit();
	}
	public function doInit()
	{
		if($this->_isinit) return;$this->_isinit=true;
		$this->doIniter();
	}

	public function setAuth($s){$this->_cfg['auth']=$s;}
	public function setAuthMode($s){$this->_cfg['auth.mode']=$s;}
	public function doAuth($t=-1)
	{
		//if($t>-1) $this->_cfg['url.mode']=$t;
		if($this->_cfg['auth']>0){
			if(!$this->_islogin) go($this->getURL('login',$t));
		}
		$this->_isauth=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getURL($t='',$urlMode=null){return UaUA::getURL($this,$t,$urlMode);}
	public function getURLReferer($clr=false){return UaUA::getURLReferer($this,$clr);}
	public function getMemory($k){return UaUA::getMemory($this,$k);}
}


trait UaURefAuth
{
	
	public function authCookie()
	{
		$this->setID(toInt($this->getClientData('id')));
		$this->_data['name']=$this->getClientData('name');
		$this->_data['email']=$this->getClientData('email');
		$this->_data['_password']=$this->getClientData('password');
		if($this->id>0) $this->_islogin=true;	// && ($this->_data['name'] || $this->_data['email'])
		//##########
		$aryInfo=$this->getClientDatas('infos');
		if(is_array($aryInfo)){
			$this->_data=utilArray::toAppend($this->_data,$aryInfo);
		}
		else{
			$this->_isUpdateInfo=true;
		}
		//##########
		if($this->_islogin){
			if($this->_cfg['auth.mode']>1){
				//$_password=$this->getClientData('password');
				//$this->setData('_password',$_password);
				$this->doLoginCheck();			//???
			}
		}
		else{
			if($this->_cfg['cookie']){
				$_id=toInt($this->getClientCookie('id'));
				$_password=$this->getClientCookie('password');
				//debugx($_id.','.$_password);
				if($_id>0 && $_password){
					$this->setID($_id);
					$this->setData('_password',$_password);
					//debuga($this->_data);
					$this->doLoginCheck();
				}
			}
		}
	}
	public function authToken()
	{
		$this->setID(toInt($this->getClientData('id')));
		$token=$this->getClientData('token');
		$this->setData('_token',$token);
		if($this->id>0 && $token) $this->_islogin=true;
	}
	
}


trait UaURefConfig
{
	protected $treeConfig=null;
	
	public function loadConfig()
	{
		if(!isTree($this->treeConfig)){
			$this->treeConfig=VDCSDTML::getConfigCacheTree('common.channel.'.$this->rc.'/'.$this->rc.'');		//'common.channel.'.$this->rc.'/'.$this->rc.''
		}
	}
	
	public function getConfig($k){$this->loadConfig();return $this->treeConfig->getItem($k);}
	public function getConfigVar($k){$this->loadConfig();return $this->treeConfig->getItem('var.'.$k);}
	public function getConfigNum($k){$this->loadConfig();return $this->treeConfig->getItem('num.'.$k);}
	public function getConfigURL($k){$this->loadConfig();return $this->treeConfig->getItem('url.'.$k);}
	
}


trait UaURefClient
{
	public $_sessions=null,$_cookies=null;

	public function clientSave()
	{
		if($this->_session_set && is_array($this->_sessions)){
			$values=utilArray::toString($this->_sessions,'&','=');
			DCS::sessionSet($this->rc.'.ua',$values);
			$this->_session_set=false;
		}
		if($this->_cookie_set && is_array($this->_cookies)){
			$values=utilArray::toString($this->_cookies,'&','=');
			DCS::cookieSet($this->rc,$values);
			$this->_cookie_set=false;
		}
	}
	
	public function clientData($key,$value=null)
	{
		if(!is_array($this->_sessions)) $this->_sessions=utilString::toArray(DCS::sessionGet($this->rc.'.ua'),'&','=');
		if(!is_null($value)){$this->_sessions[$key]=$value;$this->_session_set=true;}
		return $this->_sessions[$key];
	}
	public function setClientData($key,$value){return $this->clientData($key,$value);}
	public function getClientData($key){return $this->clientData($key);}
	public function delClientData($key){$this->clientData($key,'');unset($this->_sessions[$key]);}
	public function setClientDatas($key,$value){DCS::sessionSet($this->rc.'.'.$key,$value);}
	public function getClientDatas($key){return DCS::sessionGet($this->rc.'.'.$key);}
	public function delClientDatas($key){return DCS::sessionDel($this->rc.'.'.$key);}
	/*
	public function setClientData($key,$value){DCS::sessionSet($this->rc.'.'.$key,$value);}
	public function getClientData($key){return DCS::sessionGet($this->rc.'.'.$key);}
	public function delClientData($key){return DCS::sessionDel($this->rc.'.'.$key);}
	*/
	
	public function clientCookie($key,$value=null)
	{
		if(!is_array($this->_cookies)) $this->_cookies=utilString::toArray(DCS::cookieGet($this->rc),'&','=');
		if(!is_null($value)){$this->_cookies[$key]=$value;$this->_cookie_set=true;}
		return $this->_cookies[$key];
	}
	public function setClientCookie($key,$value){return $this->clientCookie($key,$value);}
	public function getClientCookie($key){return $this->clientCookie($key);}
	public function delClientCookie($key){$this->clientCookie($key,'');unset($this->_cookies[$key]);}
	/*
	public function setClientCookie($key,$value){DCS::cookieSet($this->rc.'.'.$key,$value);}
	public function getClientCookie($key){return DCS::cookieGet($this->rc.'.'.$key);}
	public function delClientCookie($key){return DCS::cookieDel($this->rc.'.'.$key);}
	*/
	public function setClientCookies($age=null,$domain=null)
	{
		if(!isn($age)) DCS::cookieAge($age);
		if($domain=='now') $domain=DCS::serverName();
		//if(!isn($domain)) DCS::cookieDomain($domain);
	}
	
}
