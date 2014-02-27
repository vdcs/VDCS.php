<?
class OauthAgent
{
	const TABLE_NAME	= 'dbu_oauth';
	
	public $oauth;
	public $_var=array();
	//public $authrc,$authcode,$authtoken,$authtoken2,$authkey,$authv1,$authv2,$authv3,$openid,$uuid=0;
	public $querys,$results,$uinfo;
	public $treeConfig,$treeBind;
	protected $_isload=false,$_isdata=false,$_isbind=false,$_isredirect=false;
	
	public function __construct()
	{
		$this->_var['bindid']=-1;
		$this->_var['uurc']='';
		$this->_var['uuid']=0;
	}
	public function __destruct()
	{
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setVar($k,$v){$this->_var[$k]=$v;}
	public function getVar($k){return $this->_var[$k];}
	public function getVarInt($k){return intval($this->_var[$k]);}
	
	public function setRC($s){$this->_var['authrc']=$s;}
	public function getConfig($k){return $this->treeConfig->getItem($this->_var['authrc'].'.'.$k);}
	
	public function getStatus(){return $this->_var['status'];}
	public function isPause()
	{
		$re=false;
		if(inp('close,pause',$this->_var['status'])>0) $re=true;
		return $re;
	}
	
	public function getRedirect(){return $this->_var['redirect'];}
	public function isRedirect(){return $this->_isredirect;}
	
	public function isLoad(){return $this->_isload;}
	public function isData(){return $this->_isdata;}
	public function isBind(){return $this->_isbind;}
	
	public function openid(){return $this->getVar('openid');}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->treeConfig=OauthCommon::getConfigTree();
	}
	
	public function doLoad()
	{
		switch($this->_var['authrc']){
			case 'weibo':
				$this->oauth=new OauthWeiboAgent;
				break;
			case 'qq':
				$this->oauth=new OauthQqAgent;
				break;
			default:
				
				break;
		}
		if(!$this->oauth) return;
		$this->_isload=true;
		$this->oauth->setApp($this->getConfig('id'),$this->getConfig('key'));
		$callback=$this->getConfig('callback');
		if(!$callback) $callback=$this->toURL('action=callback',true);
		//debugx($callback);
		$this->oauth->setCallback($callback);
		
		$this->_var['status']=$this->getConfig('status');
		$this->_var['redirect']=$this->getConfig('redirect');
		if($this->_var['redirect']=='yes') $this->_isredirect=true;
	}
	
	public function toURL($params,$isreal)
	{
		global $cfg;
		$re=$cfg->getLinkURL('passport','pm','p=oauth&m='.$this->_var['authrc']);
		//debugx($re);
		if($isreal) $re=DCS::url($re);
		$re=DCS::urlLink($re,$params);
		return $re;
	}
	public function getURL($page,$params='')
	{
		global $cfg;
		$re=$cfg->getLinkurl('passport',$page,'');
		if($params) $re=DCS::urlLink($re,$params);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function bindCheck($code='')
	{
		if(!$code) $code=$this->_var['authcode'];
		if(!$code) return;
		$this->_isdata=false;
		$sql='select * from '.self::TABLE_NAME.' where authrc='.DB::q($this->_var['authrc'],1).' and authcode='.DB::q($code,1);
		//debugx($sql);
		$this->treeBind=DB::queryTree($sql);
		if($this->treeBind->getCount()>0){
			$this->_var['bindid']=$this->treeBind->getItemInt('id');
			$this->_var['uuid']=$this->treeBind->getItemInt('uuid');
			if($this->_var['uuid']>0){
				$this->_isbind=true;
			}
			$this->_var['authcode']=$this->treeBind->getItem('authcode');
			$this->_var['openid']=$this->treeBind->getItem('openid');
			$this->_isdata=true;
		}
	}
	
	public function bindInit($issave=true)
	{
		if(!$this->_var['openid']) return;
		$sql='select * from '.self::TABLE_NAME.' where authrc='.DB::q($this->_var['authrc'],1).' and openid='.DB::q($this->_var['openid'],1);
		//debugx($sql);
		$this->treeBind=DB::queryTree($sql);
		//debugTree($this->treeBind);
		$this->_var['bindid']=$this->treeBind->getItemInt('id');
		if($this->treeBind->getCount()<1){
			if($issave){
				$this->treeBind->addItem('authrc',$this->_var['authrc']);
				$this->treeBind->addItem('authcode',$this->_var['authcode']);
				$this->treeBind->addItem('authtoken',$this->_var['authtoken']);
				$this->treeBind->addItem('authtoken2',$this->_var['authtoken2']);
				$this->treeBind->addItem('authkey',$this->_var['authkey']);
				$this->treeBind->addItem('authv1',$this->_var['authv1']);
				$this->treeBind->addItem('authv2',$this->_var['authv2']);
				$this->treeBind->addItem('authv3',$this->_var['authv3']);
				$this->treeBind->addItem('openid',$this->_var['openid']);
				$this->treeBind->addItem('uuid',APP_UA);
				$this->treeBind->addItem('uuid',$this->_var['uuid']);
				$this->treeBind->addItem('status',1);
				$this->treeBind->addItem('tim',DCS::timer());
				$this->treeBind->addItem('tim_up',DCS::timer());
				$FieldsAdd='authrc,authcode,authtoken,authtoken2,authkey,authv1,authv2,authv3,openid,uurc,uuid,status,tim,tim_up';
				$sqli=DB::sqlInsert(self::TABLE_NAME,$FieldsAdd,$this->treeBind);
				//debugx($sqli);
				DB::exec($sqli);
				$this->treeBind=DB::queryTree($sql);
				$this->_var['bindid']=$this->treeBind->getItemInt('id');
			}
		}
		else{
			if($issave){
				$this->treeBind->addItem('authcode',$this->_var['authcode']);
				$this->treeBind->addItem('authtoken',$this->_var['authtoken']);
				$this->treeBind->addItem('authtoken2',$this->_var['authtoken2']);
				$this->treeBind->addItem('authkey',$this->_var['authkey']);
				$this->treeBind->addItem('authv1',$this->_var['authv1']);
				$this->treeBind->addItem('authv2',$this->_var['authv2']);
				$this->treeBind->addItem('authv3',$this->_var['authv3']);
				$this->treeBind->addItem('tim_up',DCS::timer());
				$FieldsUpdate='authcode,authtoken,authtoken2,authkey,authv1,authv2,authv3,tim_up';
				$sqli=DB::sqlUpdate(self::TABLE_NAME,$FieldsUpdate,$this->treeBind,'id='.$this->_var['bindid']);
				//debugx($sqli);
				DB::exec($sqli);
			}
		}
		$this->_var['uuid']=$this->treeBind->getItemInt('uuid');
		if($this->_var['uuid']>0){
			$this->_isbind=true;
		}
		$this->_isdata=true;
	}
	
	public function bindSaveUID()
	{
		$this->bindUID();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isBindU($uid=0,$authrc='')
	{
		if(!$authrc) $authrc=$this->_var['authrc'];
		if(!$uid) $uid=$this->_var['uuid'];
		//debugx($uid);
		$re=0;
		$sql='select id from '.self::TABLE_NAME.' where authrc='.DB::q($authrc,1).' and uuid='.$uid;
		$re=DB::queryInt($sql);
		return $re;
	}
	
	public function bindUID($uid=0,$openid='',$authrc='')
	{
		if(!$authrc) $authrc=$this->_var['authrc'];
		if(!$uid) $uid=$this->_var['uuid'];
		$urc=$this->_var['uurc'];
		if(!$openid) $openid=$this->_var['openid'];
		if(!$authrc || !$openid || !$uid) return;
		$sql='update '.self::TABLE_NAME.' set uurc='.DB::q($urc,1).',uuid='.$uid.' where authrc='.DB::q($authrc,1).' and openid='.DB::q($openid,1);
		//debugx($sql);
		DB::exec($sql);
	}
	
	public function unbindUID($uid=0,$authrc='')
	{
		if(!$authrc) $authrc=$this->_var['authrc'];
		if(!$uid) $uid=$this->_var['uuid'];
		$sql='select id from '.self::TABLE_NAME.' where authrc='.DB::q($authrc,1).' and uuid='.$uid;
		$bindid=DB::queryInt($sql);
		if($bindid){
			$sql='update '.self::TABLE_NAME.' set uurc=\'\',uuid=0 where id='.$bindid;
			//debugx($sql);
			DB::exec($sql);
		}
	}
	
}
