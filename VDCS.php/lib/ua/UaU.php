<?
if(!defined('INC__UaURef')) require('UaURef'.EXT_SCRIPT);
//Unite Authentic User
class UaU
{
	use UaURefBase,UaURefAuth,UaURefConfig,UaURefClient;

	const BaseKEY			= 'base';
	const FIELD_ID			= 'id';

	public $KEY='';
	protected $_isUpdateInfo=false,$_isinfo=false;
	
	public function __construct()
	{
		$this->__constructBase();
		$this->rc					= 'user';
		
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
		
		$this->idi=-1;
		$this->KEY=&$this->rc;
	}
	public function __destruct()
	{
		unset($this->_cfg,$this->_data);
	}
	
	public function struct($k,$m=self::BaseKEY){return $this->_cfg[($m?$m:self::BaseKEY).':'.$k];}
	public function save(){$this->clientSave();}
	
	
	/*
	########################################
	########################################
	*/
	public function isInfo($value=null){if(!is_null($value))$this->_isinfo=$value;return $this->_isinfo;}
	
	public function sid()
	{
		if(!$this->_data['_sid']){	//!$this->_islogin && 
			$this->_data['_sid']=$this->getClientCookie('sid');
			if(!$this->_data['_sid']){
				$this->_data['_sid']=DCS::sessionid();
				$this->setClientCookie('sid',$this->_data['_sid']);
			}
		}
		return $this->_data['_sid'];
	}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		$this->initBase();

		//##########
		$this->TableName=$this->struct('TableName');
		$this->TablePX=$this->struct('TablePX');
		$this->FieldID=$this->struct('FieldId');
		$this->FieldNO=$this->struct('FieldNo');
		//##########
		//debugx($this->TableName.','.$this->TablePX.','.$this->FieldID.','.$this->FieldNO);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doIniter()
	{
		//$this->init();
		//dcsLog(__METHOD__,DCS::sessionGet($this->rc));
		//dcsLog(__METHOD__,DCS::cookieGet($this->rc));
		$funcauth='auth'.ucfirst($this->_cfg['auth.model']);
		$this->$funcauth();
		//##########
		$this->doAuth();
		if(!$this->_islogin){
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
		if($this->_cfg['auth.mode']>1 || $this->spaceUpdate) $this->_isUpdateInfo=true;	//############
		//debugx($this->rc.'tim.update='.$this->_data['_tim.update'].','.$this->spaceSecond.','.($this->_isUpdateInfo?'true':'false'));
		//##########
		$this->dataParser();
		//debuga($this->_data);
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
		$this->_isinfo=true;
		if(!$this->_isUpdateInfo) return false;
		//##########
		if($this->spaceUpdate && $this->_cfg['online:is']){
			$onlinetim=$this->spaceSecond;
			if($onlinetim) $this->update('{tpx}online={tpx}online+'.$onlinetim.',{tpx}onlines={tpx}onlines+'.$onlinetim);
		}
		//##########
		$info=$this->_cfg['auth.mode']>1?1:0;
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
		
		$_names=Ua::toNames($reTree);
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
	
}
