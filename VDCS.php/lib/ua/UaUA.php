<?
class UaUA
{

	public static function doLoginCheck(&$that,$cookie=false)
	{
		if($that->_cfg['auth.model']=='token'){
			if($that->_islogin) return;
			$that->_islogin=false;
			$token=$that->getData('_token');
			if($that->id && $token){
				$that->_islogin=true;
				$that->setClientData('id',$that->id);
				$that->setClientData('token',$token);
			}
			return;
		}
		$that->_islogin=false;
		$_id=$that->id;		//getDataInt('id');
		//if($_id<1) $_id=$that->getDataInt($that->FieldID);
		if(!$_id){
			$_name=$that->getData('name');		if(!utilCheck::isName($_name)) $_name='';
			$_email=$that->getData('email');	if(!utilCheck::isEmail($_email)) $_email='';
		}
		$_password=$that->getData('_password');
		$_encrypt_timer=$that->getDataInt('_encrypt_timer');
		//debugx('id='.$_id.', name='.$_name.', email='.$_email.', password='.$_password.', encrypt_timer='.$_encrypt_timer);
		if($_password && ($_id>0 || $_name || $_email)){
			/*
			$fieldPass='';
			if(!$that->_cfg['verify.pivotal']) $fieldPass=','.$that->struct('FieldPassword');
			$fields=''.$that->FieldID.','.$that->struct('FieldName').','.$that->struct('FieldEmail').$fieldPass.','.$that->struct('FieldGroupid').',{tpx}isauth,{tpx}islock,{tpx}status';
			*/
			$sqlQuery='';
			if($_id>0) $sqlQuery=$that->FieldID.'='.$_id;
			else{
				if($that->_cfg['verify']=='email'){
					$sqlQuery=$that->struct('FieldEmail').'='.DB::q($_email,1);
				}
				else{
					$sqlQuery=$that->struct('FieldName').'='.DB::q($_name,1);
				}
			}
			//debugx($sqlQuery);
			$sql=DB::sqlSelect($that->TableName,'','*',$sqlQuery,'',1);
			//$sql=r($sql,'{tpx}',$that->TablePX);
			//debugx($sql);
			$treeDat=DB::queryTree($sql);
			//debugTree($treeDat);
			if($treeDat->getCount()>0){
				//$treeDat->doFilter($that->TablePX);
				//$that->_cfg['verify.pivotal']
				//debugx(($_password.'--'.$treeDat->getItem($that->struct('FieldPassword')).'--'.$treeDat->getItemInt($that->TablePX.'status'));
				if($treeDat->getItemInt('status')>0){
					$that->id=$treeDat->getItem($that->FieldID);
					$isVerify=false;
					$_passwordo=$_password;
					if($that->_cfg['verify.pivotal']){
						$isVerify=UuPivotal::isLogin($that,$_password,$_encrypt_timer,$_passwordo);
					}
					else{
						$isVerify=($_password==$treeDat->getItem($that->struct('FieldPassword')));
					}
					if($isVerify){
						$that->id=$treeDat->getItem($that->FieldID);
						$that->setID($that->id);
						$that->setData('_encrypt_timer','');
						//debugx('passwordo='.$_passwordo);
						$treeDat->addItem('id',$that->id);
						$treeDat->addItem('name',$treeDat->getItem($that->struct('FieldName')));
						$treeDat->addItem('email',$treeDat->getItem($that->struct('FieldEmail')));
						$treeDat->addItem('_password',$_passwordo);
						$treeDat->addItem('_tim',DCS::timer());
						$remember=$that->getData('_remember');
						if(!$remember) $remember=$that->getClientCookie('remember');
						$treeDat->addItem('_remember',$remember);
						//debuga($that->_data);
						$that->doLoginUpdate($treeDat,$cookie);
						//debuga($that->_data);
						if($that->_islogin){
							//$treeDat->doFilter($that->TablePX);
							//$that->parserData($treeDat);
							//$that->idi=$that->id;
							//$that->dataParser();
						}
					}
				}
			}
			unset($treeDat);
		}
		if(!$that->_islogin) $that->doLogout();
		//exit;
	}
	public static function doLoginUpdate(&$that,$treeDat,$cookie=false)
	{
		if(!isTree($treeDat) || $treeDat->getCount()<1) return;
		//debugTree($treeDat);
		$that->id=$treeDat->getItemInt('id');
		//debugx(($that->id.'--'.$treeDat->getItem('name').'--'.$treeDat->getItem('email'));
		if($that->id>0){	// && ($treeDat->getItem('name') || $treeDat->getItem('email'))
			$that->_islogin=true;
			//debugx($treeDat->getItem('_password'));
			if($cookie){
				$remember=$treeDat->getItem('_remember');
				$cookiesAge=$remember;
				$that->setClientCookies($cookiesAge,'now');
				$that->setClientCookie('remember',$remember);
				$that->setClientCookie('id',$that->id);
				//$that->setClientCookie('name',$treeDat->getItem('name'));
				//$that->setClientCookie('email',$treeDat->getItem('email'));	//if($treeDat->getItem('email')) 
				$that->setClientCookie('password',$treeDat->getItem('_password'));
			}
			$that->setClientData('id',$that->id);
			$that->setClientData('name',$treeDat->getItem('name'));
			$that->setClientData('email',$treeDat->getItem('email'));
			$that->setClientData('password',$treeDat->getItem('_password'));
			$that->setClientData('tim',$treeDat->getItemInt('_tim'));
			//$that->setClientData(tim.update',0);
		}
	}
	
	public static function doLogout(&$that)
	{
		//debugTrace();
		//exit;
		$that->delClientData('id');
		if($that->_cfg['auth.model']=='token'){
			$that->delClientData('token');
		}
		else{
			$that->delClientCookie('remember');
			$that->delClientCookie('id');
			$that->delClientCookie('name');
			$that->delClientCookie('email');
			$that->delClientCookie('password');
			$that->delClientData('name');
			$that->delClientData('email');
			$that->delClientData('password');
		}
		$that->delClientData('tim');
		$that->delClientData('tim.update');
		$that->delClientDatas('infos');
		//$that->delClientData('login.url.referer');
		$that->initData();
		$that->_islogin=false;
	}


	/*
	########################################
	########################################
	*/
	public static function getURL(&$that,$t='',$url_mode=null)
	{
		global $cfg;
		if($url_mode==null) $url_mode=$that->_cfg['url.mode'];
		if($t!='base') $re=appURL($that->rc.'.'.$t);
		switch($t){
			case 'login':
				if(!$re) $re=appURL(''.$that->rc.'.login.agent');
				if(!$re) $re=appURL(''.$that->rc.'.login');
				if(!$re) $re=$cfg->getChannelValue('passport','configure','url.'.$that->rc.'.login.agent');
				if(!$re) $re=$cfg->getChannelValue('passport','configure','url.'.$that->rc.'.login');
				if(!$re) $re=$cfg->getChannelValue('passport','configure','url.login.agent');
				if(!$re) $re=$cfg->getChannelValue('passport','configure','url.login');
				if($url_mode>0){
					if(ins($re,'{$url}')>0) $re=rd($re,'url',DCS::browsePath(true));
					else $that->setClientData('login.url.referer',DCS::browsePath(true));
				}
				break;
			case 'referer':
				$re=$that->getURLReferer(true);
				if($re==appURL('root')) $re='';
				if(!$re) $re=appURL($that->rc.'.index');
				if(!$re) $re=appURL($that->rc.'.m');
				if(!$re) $re=$that->getURLReferer(true);
				if(!$re) $re=appURL('account');
				break;
			case 'base':
			default:
				if($url_mode>0) $re=$that->getURLReferer(false);
				if(!$re) $re=appURL($that->rc.'.index');
				if(!$re) $re=appURL($that->rc.'.m');
				if(!$re && $url_mode>0) $re=$that->getURLReferer(false);
				if(!$re) $re=appURL('account');
				break;
		}
		if(!$re) $re=appURL('root');
		return $re;
	}
	public static function getURLReferer(&$that,$clr=false)
	{
		$re=$that->getClientData('login.url.referer');
		if($re && $clr) $that->delClientData('login.url.referer');
		return $re;
	}
	
	public static function getMemory(&$that,$k)
	{
		$re=$that->getClientCookie($k);
		switch($k){
			case 'name':		if(!utilCheck::isName($re)) $re='';break;
			case 'email':		if(!utilCheck::isEmail($re)) $re='';break;
		}
		return $re;
	}
	



	/*
	########################################
	########################################
	*/
	public static function doLoginAppend(&$cpo,&$ua)
	{
		$cpo->addVar('uurc',$ua->rc);
		$cpo->addVar('uuid',$ua->getData('id'));
		$cpo->addVar('uid',$ua->getData('id'));
		$cpo->addVar('id',$ua->getData('id'));
		$cpo->addVar('no',$ua->getData('no'));
		$cpo->addVar('name',$ua->getData('name'));
		$cpo->addVar('email',$ua->getData('email'));
		$cpo->addVar('names',$ua->getNames());
	}
	
	public static function toQueryI($ua,$key)
	{
		$re='{tpx}no='.DB::q($key,1).' or {tpx}name='.DB::q($key,1).'';
		if(utilCheck::isMobile($key)) $re='{tpx}mobile='.DB::q($key,1).'';
		if(utilCheck::isEmail($key)) $re='{tpx}email='.DB::q($key,1).'';
		$re=rv($re,'tpx',$ua->TablePX);
		return $re;
	}
	
}
