<?
//Unite Authentic User
class UaU
{
	public $id=0,$name='Guest',$email='',$groupid=0;
	public $rc='',$KEY='';
	public $_cfg=array(),$_data=array();
	public $_sessions=null,$_cookies=null;
	public $_isInit=false,$_isLogin=false;
	public $_Auth=-1,$_AuthMode=-1,$_urlMode=1;
	protected $_isUpdateInfo=false,$_isInfo=false;
	protected $treeConfig=null,$tableGroup=null;
	const BaseKEY			= 'base';
	const FIELD_ID			= 'id';
	
	public function __construct()
	{
		$this->KEY=&$this->rc;
		$this->rc					= 'user';
		$this->_cfg['UPDATE_SPACE']			= 300;
		
		$this->_cfg['cookie']				= false;
		$this->_cfg['verify']				= 'name';
		$this->_cfg['verify.pivotal']			= true;
		$this->_cfg['base:TableName']			= 'db_user';
		$this->_cfg['base:TablePX']			= '';
		$this->_cfg['base:FieldId']			= 'uid';
		$this->_cfg['base:FieldNo']			= 'no';
		$this->_cfg['base:FieldName']			= 'name';
		$this->_cfg['base:FieldNames']			= 'names';
		$this->_cfg['base:FieldEmail']			= 'email';
		$this->_cfg['base:FieldMobile']			= 'mobile';
		$this->_cfg['base:FieldPassword']		= 'password';
		$this->_cfg['base:FieldGroupid']		= 'groupid';
		$this->_cfg['info:is']				= false;
		$this->_cfg['info:TableName']			= 'db_user_info';
		
		$this->_cfg['online:is']			= true;
		
		$this->_cfg['pivotal:TableName']		= 'dbu_pivotal';
		$this->_cfg['pivotal:TablePX']			= '';
		$this->_cfg['pivotal:FieldID']			= 'id';
		
		$this->_data['id']=$this->id;
		//$this->_data['name']=$this->name;
		//$this->_data['email']=$this->email;
		$this->_data['groupid']=$this->groupid;
		$this->_data['_sid']='';
		
		$this->_cfg['auth.model']				= 'cookie';

		$this->_Auth=-1;
		$this->_AuthMode=-1;
		
		$this->idi=-1;
	}
	public function __destruct()
	{
		unset($this->_cfg,$this->_data);
		unset($this->treeConfig,$this->tableGroup);
	}
	
	public function struct($k,$m=self::BaseKEY){return $this->_cfg[($m?$m:self::BaseKEY).':'.$k];}
	public function save(){$this->clientSave();}
	
	
	/*
	########################################
	########################################
	*/
	public function isInit(){return $this->_isInit;}
	public function isLogin(){return $this->_isLogin;}
	public function isInfo($value=null){if(!is_null($value))$this->_isInfo=$value;return $this->_isInfo;}
	
	public function setAuth($s){$this->_Auth=$s;}
	public function setAuthMode($s){$this->_AuthMode=$s;}
	
	public function setLocation($s){$this->_Location=utilCode::toSQL(utilCode::toCut($s,250,''));}
	
	public function sid()
	{
		if(!$this->_data['_sid']){	//!$this->_isLogin && 
			$this->_data['_sid']=$this->getClientCookie('sid');
			if(!$this->_data['_sid']){
				$this->_data['_sid']=DCS::sessionid();
				$this->setClientCookie('sid',$this->_data['_sid']);
			}
		}
		return $this->_data['_sid'];
	}
	
	public static function prename($k='guest')
	{
		$re=appv('var.'.$k);
		if(!$re) $re='Guest';
		return $re;
	}

	public static function toNames($treeU,$FieldID=self::FIELD_ID)
	{
		$adata=isa($treeU)?$treeU:$treeU->getArray();
		//debugTrace();
		//debuga($adata);
		if(!($_names=$adata['_names'])
			&& !($_names=$adata['names'])
			&& !($_names=$adata['name'])
			&& !($_names=$adata['email'])
			&& !($_names=$adata['mobile'])) $_names='['.$adata[$FieldID].']';
		//debugx('names='.$_names);
		return $_names;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		//##########
		$this->TableName=$this->struct('TableName');
		$this->TablePX=$this->struct('TablePX');
		$this->FieldID=$this->struct('FieldId');
		$this->FieldNO=$this->struct('FieldNo');
		//##########
		//debugx($this->TableName.','.$this->TablePX.','.$this->FieldID.','.$this->FieldNO);
		
		$this->_data['_rc']=$this->rc;
		$this->_data['_o']=0;
		$this->_data['_id']=0;
	}
	
	public function initData($real=true)
	{
		$_o=1;
		if($real && !$this->_isLogin){
			$_o=0;
			$this->id=0;
			$this->_data=array();
			$this->_data['id']=$this->id;
			$this->_data['name']=self::prename();
			$this->groupid=0;
		}
		$this->_data['_o']=$_o;
		$this->_data['_id']=$this->id;
		$this->_data['id']=$this->id;
		$this->_data[$this->FieldID]=$this->id;
		$_names=self::toNames($this->_data);
		$this->_data['names']=$_names;
		$this->_data['_names']=$_names;
	}
	

	/*
	########################################
	########################################
	*/
	public function authCookie()
	{
		$this->setID(toInt($this->getClientData('id')));
		$this->_data['name']=$this->getClientData('name');
		$this->_data['email']=$this->getClientData('email');
		$this->_data['_password']=$this->getClientData('password');
		if($this->id>0) $this->_isLogin=true;	// && ($this->_data['name'] || $this->_data['email'])
		//##########
		$aryInfo=$this->getClientDatas('infos');
		if(is_array($aryInfo)){
			$this->_data=utilArray::toAppend($this->_data,$aryInfo);
		}
		else{
			$this->_isUpdateInfo=true;
		}
		//##########
		if($this->_isLogin){
			if($this->_AuthMode>1){
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
		if($this->id>0 && $token) $this->_isLogin=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doReInit()
	{
		$this->_isInit=false;
		$this->doInit();
	}
	public function doInit()
	{
		if($this->_isInit) return;$this->_isInit=true;
		//$this->init();
		//dcsLog(__METHOD__,DCS::sessionGet($this->rc));
		//dcsLog(__METHOD__,DCS::cookieGet($this->rc));
		$funcauth='auth'.ucfirst($this->_cfg['auth.model']);
		$this->$funcauth();
		//##########
		$this->doAuth();
		if(!$this->_isLogin){
			$this->initData();
			return;
		}
		//##########
		$this->_data['_tim']=toInt($this->getClientData('tim'));
		$this->_data['_tim.update']=toInt($this->getClientData('tim.update'));
		if($this->_data['_tim.update']<1){
			$this->_data['_tim.update']=DCS::timer();
			$this->setClientData('tim.update',$this->_data['_tim.update']);
			$this->_isUpdateInfo=true;
		}
		//##########
		$this->spaceSecond=DCS::timer()-$this->_data['_tim.update'];
		$this->spaceUpdate=$this->spaceSecond>$this->_cfg['UPDATE_SPACE'];
		if($this->_AuthMode>1 || $this->spaceUpdate) $this->_isUpdateInfo=true;	//############
		//debugx($this->rc.'tim.update='.$this->_data['_tim.update'].','.$this->spaceSecond.','.($this->_isUpdateInfo?'true':'false'));
		//##########
		$this->dataParser();
		//debuga($this->_data);
	}
	
	public function doAuth($t=-1)
	{
		if($t>-1) $this->_urlMode=$t;
		if($this->_Auth>0){
			if(!$this->_isLogin) go($this->getURL('login'));
		}
		$this->_isAuth=true;
	}
	

	/*
	########################################
	########################################
	*/
	public function loader($id,$info=0)
	{
		if(!$id) return;
		$this->setID($id);
		$this->dataLoader($info);
	}
	
	public function loadDataInfo($info=0){return $this->dataLoader($info);}			//hold
	public function dataLoader($info=0)
	{
		$re=false;
		$treeDat=$this->queryTree($this->sqlQuery(),$info);
		//debugTree($treeDat);
		$this->dataAppend($treeDat);
		return $re;
	}
	public function dataAppend($treeDat)
	{
		if($treeDat->getCount()>0){
			$this->_data=array_merge($this->_data,$treeDat->getArray());
			$re=true;
		}
	}
	public function dataParser()
	{
		$this->_isInfo=true;
		if(!$this->_isUpdateInfo) return false;
		//##########
		if($this->spaceUpdate && $this->_cfg['online:is']){
			$onlinetim=$this->spaceSecond;
			if($onlinetim) $this->update('{tpx}online={tpx}online+'.$onlinetim.',{tpx}onlines={tpx}onlines+'.$onlinetim);
		}
		//##########
		$info=$this->_AuthMode>1?1:0;
		$this->dataLoader($info);
		$this->setClientDatas('infos',$this->_data);
		$this->_data['_tim.update']=DCS::timer();
		//debugx('---'.$this->_data['_tim.update']);
		$this->setClientData('tim.update',$this->_data['_tim.update']);
		unset($treeDat);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoginCheck($cookie=false){return UaUA::doLoginCheck($this,$cookie);}
	public function doLoginUpdate($treeDat,$cookie=false){return UaUA::doLoginUpdate($this,$treeDat,$cookie);}
	public function doLogout(){return UaUA::doLogout($this);}
	
	
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
	
	public function getNames(){return self::toNames($this->_data);}

	
	/*
	########################################
	########################################
	*/
	public function queryField($field,$queryi=null,$info=0)
	{
		if(!$fieldname=$this->struct('Field'.ucfirst($field))) $fieldname=$field;
		return $this->queryTree($queryi,$info)->getItem($fieldname);
	}

	public function getInfoTree($id,$info=0){return $this->queryTree($this->sqlQuery($id),$info);}		//hold
	public function queryTree($queryi=null,$info=0)
	{
		$reTree=newTree();
		$mapKey=$this->rc.'.'.strval($queryi).'-'.$info;
		//debugx($mapKey);
		if(DCS::isDatc($mapKey)){
			$reTree->setArray(DCS::getDatc($mapKey));
			return $reTree;
		}
		$sqlQuery=$this->sqlQuery($queryi);
		//debugx('--'.$sqlQuery);
		//##########
		$reTree=DB::queryTree('select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1');
		if($reTree->getCount()<1){
			DCS::setDatc($mapKey,$reTree->getArray());
			return $reTree;
		}
		//##########
		$reTree->addItem('_o',1);
		$id=$reTree->getItemInt($this->FieldID);
		$reTree->addItem('_id',$id);
		$reTree->addItem(self::FIELD_ID,$id);

		if(!$_no=$reTree->getItem($this->struct('FieldNO'))) $_no=$id;
		$reTree->addItem('_no',$_no);
		$reTree->addItem('no',$_no);

		$_names=self::toNames($reTree);
		$reTree->addItem('_names',$_names);
		$reTree->addItem('names',$_names);
		//##########
		if($info && $this->struct('is','info')){
			$sql='select * from '.$this->struct('TableName','info').' where '.$this->FieldID.'='.$id.' limit 0,1';
			//debugx($sql);
			$treeInfo=DB::queryTree($sql);
			if($treeInfo->getCount()>0){
				//$this->_datas['info'.$id]=$treeInfo->getCount();
				DCS::setDatc($this->rc.'.info'.$id,$treeInfo->getCount());
				$reTree->doAppendTree($treeInfo);
			}
			unset($treeInfo);
		}
		//debugTree($reTree);
		DCS::setDatc($mapKey,$reTree->getArray());
		return $reTree;
	}

	public function isQueryInfo($id=-1)
	{
		if($id==-1)$id=$this->id;
		return DCS::isDatc($this->rc.'.info'.$id)?true:false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toTable($table=self::BaseKEY){return $this->struct('TableName',$table);}
	
	public function sqlQuery($by=null,$value=null)
	{
		if(is_null($by)) return $this->FieldID.'='.$this->id;
		if(is_null($value)){
			if(is_string($by)) return r($by,'{tpx}',$this->TablePX);
			$value=$by;
			$by='id';
			if($value<0) $value=$this->id;
		}
		$fieldname=$this->struct('Field'.ucfirst($by));
		if(!$fieldname) $fieldname=$this->TablePX.$by;
		return $fieldname.'='.DB::q($value,1);
	}

	public function sqlSelect($fields='',$queryi=null,$order='',$limit=0,$table=self::BaseKEY)
	{
		$fields=r($fields,'{tpx}',$this->TablePX);
		$sql=DB::sqlQuery($this->toTable($table),$fields,$this->sqlQuery($queryi),$order,$limit);
		return $sql;
	}

	public function getQueryTree($queryi=null,$fields='')
	{
		$sql=$this->sqlSelect($fields,$queryi,'',1);
		$treeRS=DB::queryTree($sql);
		$reTree=newTree();
		$reTree->doAppendTree($treeRS);
		//$treeRS->doFilter($this->TablePX);
		//$reTree->doAppendTree($treeRS);
		return $reTree;
	}
	
	
	public function sets($sets,$queryi=null,$table=self::BaseKEY){return $this->update($sets,$queryi,$table);}			//hold
	public function updateTree($sets,$queryi=null,$table=self::BaseKEY){return $this->update($sets,$queryi,$table);}		//hold
	public function update($sets,$queryi=null,$table=self::BaseKEY)
	{
		if(is_null($queryi)) $this->clearCache();
		$queryi=$this->sqlQuery($queryi);
		if(is_string($sets)){
			$sets=r($sets,'{tpx}',$this->TablePX);
			$sql='update '.$this->toTable($table).' set '.$sets.' where '.$queryi;
		}
		else{
			//isTree($sets)
			$sql=DB::sqlUpdate($this->toTable($table),$sets->getFields(),$sets,$queryi);
		}
		//dcsLog('sql',$sql);
		//debugx($sql);
		return DB::exec($sql);
	}
	
	public function clearCache()
	{
		$this->delClientDatas('infos');
	}
	
	
	/*
	########################################
	########################################
	*/
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
	
	
	/*
	########################################
	########################################
	*/
	public function getURL($t='',$urlMode=null){return UaUA::getURL($this,$t,$urlMode);}
	public function getURLReferer($clr=false){return UaUA::getURLReferer($this,$clr);}
	public function getMemory($k){return UaUA::getMemory($this,$k);}
	
	
	/*
	########################################
	########################################
	*/
	public function clientSave()
	{
		if($this->_session_set && is_array($this->_sessions)){
			$values=utilArray::toString($this->_sessions,'&','=');
			DCS::sessionSet($this->rc,$values);
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
		if(!is_array($this->_sessions)) $this->_sessions=utilString::toArray(DCS::sessionGet($this->rc),'&','=');
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
?>