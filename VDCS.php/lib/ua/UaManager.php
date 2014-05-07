<?
define('MANAGER_UPDATE_SPACE',			300);

class UaManager
{
	public $rc='manager';
	public $id=0,$name='';
	public $TableName='dbs_manager',$TablePX='';
	protected $_data=array();
	
	protected $_isInit=false,$_isLogin=false,$_Auth=-1;
	protected $_update_time='',$_space_second=-1,$_isUpdateInfo=false,$_isInfo=false;
	
	
	public function __construct()
	{
		
	}
	
	public function __destruct(){}
	
	
	/*
	########################################
	########################################
	*/
	public function isInit(){return $this->_isInit;}
	public function isLogin(){return $this->_isLogin;}
	
	public function setAuth($v){$this->_Auth=$v;}
	public function getAuth(){return $this->_Auth;}
	
	public function isLocked(){return $this->getDataInt('islock')==1?true:false;}
	
	public function getGrade(){return $this->getDataInt('grade');}
	
	public function setClientData($key,$value){return DCS::sessionSet($this->rc.'.'.$key,$value);}
	public function getClientData($key){return DCS::sessionGet($this->rc.'.'.$key);}
	public function delClientData($key){return DCS::sessionDel($this->rc.'.'.$key);}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->_isInit) return;
		$this->id=toInt($this->getClientData('id'));
		$this->name=$this->getClientData('name');
		if($this->id && $this->name) $this->_isLogin=true;
		if(!$this->_isLogin){
			if($this->_Auth>0){
				//debugx($this->getURL('login'));
				//dcsEnd();
				go($this->getURL('login'));
			}
			$this->id=0;
			$this->name='Guest';
		}
		$this->_data['id']=$this->id;
		$this->_data['name']=$this->name;
		if(!$this->_isLogin) return;
		$this->_data['password']=$this->getClientData('password');
		$this->_data['time']=$this->getClientData('time');
		
		$this->_update_time=$this->getClientData('_tim.update');
		if($this->_update_time){
			$this->_space_second=$this->_update_time-DCS::timer();
			//echo $this->_space_second;
			if($this->_space_second>MANAGER_UPDATE_SPACE) $this->_isUpdateInfo=true;
		}
		else{
			//$this->setClientData('_tim.update',DCS::timer());
			$this->_isUpdateInfo=true;
		}
		$this->_data['_tim.update']=$this->_update_time;
		$this->_isUpdateInfo=true;
		
		$this->doUpdateInfo();
		$this->doAuth();
		$this->_isInit=true;
	}
	
	public function doAuth()
	{
		if($this->_Auth>0){
			if(!$this->_isLogin) go($this->getURL('login'));
		}
	}
	
	public function doUpdateInfo()
	{
		$this->_isInfo=true;
		if(!$this->_isUpdateInfo){
			$tmpAry=$this->getClientData('info');
			if($tmpAry){
				if($this->_Auth>1){$this->_isUpdateInfo=true;}
				else{$this->_data=utilArray::toAppend($this->_data,$tmpAry);}
			}
			else{
				$this->_isUpdateInfo=true;
			}
		}
		if(!$this->_isUpdateInfo) return;
		$sql='select * from '.$this->TableName.' where id='.$this->id.' and status=1 limit 0,1';
		$treeData=DB::queryTree($sql);
		if($treeData->getCount()<1){
			$this->doDataClear();
			return;
		}
		
		//$treeData->doFilter($this->TablePX);
		$this->_data=utilArray::toAppend($this->_data,$treeData->getArray());
		
		$this->setClientData('info',$this->_data);
		$this->setClientData('_tim.update',DCS::timer());
	}
	
	public function doLoginCheck()
	{
		$sql='select * from '.$this->TableName.' where name='.DB::q($this->_data['name'],1).' and status=1 limit 0,1';
		$treeData=DB::queryTree($sql);
		//debugx($sql);
		if($treeData->getCount()>0){
			$treeData->doFilter($this->TablePX);
			if($treeData->getItem('password')==$this->_data['password'] && $treeData->getItemInt('status')==1){
				$treeData->addItem('_tim',DCS::timer());
				$this->doLoginUpdate($treeData);
			}
		}
		if(!$this->_isLogin) $this->doDataClear();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoginUpdate($treeData)
	{
		$this->id=$treeData->getItemInt('id');
		$this->name=$treeData->getItem('name');
		if($this->id && $this->name){
			$this->_isLogin=true;
			$this->setClientData('id',$this->id);
			$this->setClientData('name',$this->name);
			$this->setClientData('password',$treeData->getItem('password'));
			$this->setClientData('_tim',$treeData->getItem('_tim'));
			$this->setClientData('_tim.update','');
		}
	}
	
	public function doModifyPassword($treeData,$isUpdate=0)
	{
		if(!isTree($treeData)){
			if(!$treeData) return;
			$password=$treeData;
			$treeData=newTree();
			$treeData->addItem('password',$password);
		}
		$password=$treeData->getItem('password');
		$this->_data['password']=$password;
		$this->setClientData('password',$password);
		if($isUpdate==1){
			$sql='update '.$this->TableName.' set password='.DB::q($password,1).' where id='.$this->id;
			DB::exec($sql);
		}
	}
	
	public function doDataClear()
	{
		$this->delClientData('id');
		$this->delClientData('name');
		$this->delClientData('password');
		$this->delClientData('_tim');
		$this->delClientData('_tim.update');
		$this->delClientData('info');
		$this->id=0;
		$this->name='';
		$this->_data['id']=$this->id;
		$this->_data['name']=$this->name;
		$this->_isLogin=false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getDataTree(){$reTree=new utilTree(); $reTree->setArray($this->_data); return $reTree;}
	
	public function getData($k){return $this->_data[$k];}
	public function getDataInt($k){return toInt($this->_data[$k]);}
	public function getDataNum($k){return toNum($this->_data[$k]);}
	public function setData($k,$v){$this->_data[$k]=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function loadDataInfo()
	{
		$treeInfo=$this->getInfoTree($this->id);
		$this->_data=utilArray::toAppend($this->_data,$treeInfo->getArray());
		unset($treeInfo);
	}
	
	public function getInfoTree($id)
	{
		return DB::queryTree('select * from '.$this->TableName.' where id='.$id.' limit 0,1');
	}
	
	public function getDataBase($fields,$query)
	{
		$treeData=$this->getDataBaseTree($fields,$query);
		return $treeData->getItem($fields);
	}
	
	public function getDataBaseTree($fields,$query)
	{
		return DB::queryTree('select '.$fields.' from '.$this->TableName.' where '.$query.' limit 0,1');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getURL($type='')
	{
		if($type=='referer'){
			$re=$this->getClientData('login.url.referer');
		}
		else{
			$tmpKey=($type) ? 'manage.'.$type : 'manage';
			$re=appURL($tmpKey);
			if($type=='login'){
				$nowURL=DCS::browsePath(true);
				if($nowURL!=appURL('manage'.'.logout')){
					if(ins($re,'{$url}')>0){$re=rd($re,'url',$nowURL);}
					else{$this->setClientData('login.url.referer',$nowURL);}
				}
			}
		}
		if(!$re) $re=appURL('manage');
		return $re;
	}
	

	/*
	########################################
	########################################
	*/
	public function loadConfig()
	{
		if(!isTree($this->treeConfig)){
			$this->treeConfig=VDCSDTML::getConfigTree('vdcs.mconfig/manager');
			$this->treeConfig->doAppendTree(VDCSDTML::getConfigTree('manage.config/manager'));
		}
	}
	
	public function getConfig($k){$this->loadConfig(); return $this->treeConfig->getItem($k);}
	public function getConfigVar($k){$this->loadConfig(); return $this->treeConfig->getItem('var.'.$k);}
	

	/*
	########################################
	########################################
	*/
	public function uConfig($k){return $this->getConfig('ua.'.$k);}
	
	public function getUA()
	{
		$rc=$this->urc();
		if(!$rc) return null;
		$ua=Ua::instance($rc);
		$ua->setID($this->uid());
		return $ua;
	}
	
	public function urc()
	{
		$re=$this->_data['uurc'];
		if(!$re) $re=$this->uConfig('rc');
		return $re;
	}
	public function uid(){return toInt($this->_data['uuid']);}
	public function unames(){return $this->uValue('_names');}
	
	protected function uInit()
	{
		if(!$this->treeUa){
			$this->treeUa=newTree();
			if($this->uConfig('rc') && $this->uid()>0){
				$sql=DB::sqlSelect($this->uConfig('table.name'),'','*',$this->uConfig('field.id').'='.$this->uid(),'',1);
				$this->treeUa=DB::queryTree($sql);
				$names=$this->treeUa->getItem('names');
				if($names) $names=$this->treeUa->getItem('name');
				if($names) $names=$this->treeUa->getItem('email');
				if($names) $names=$this->treeUa->getItem('mobile');
				if($names) $names=$this->treeUa->getItem($this->uConfig('field.id'));
				$this->treeUa->addItem('_names',$names);

				$treeRelation=utilString::toTree($this->uConfig('info.data'),';','=');
				$treeRelation->doBegin();
				for($t=1;$t<=$treeRelation->getCount();$t++){
					$tablename=$treeRelation->getItemKey();
					$fieldid=$treeRelation->getItemValue();
					if($tablename && $fieldid){
						$prefix='';
						if(ins($fieldid,',')){
							$fieldids=$fieldid;
							$fieldid=strstr($fieldids,',',true);
							$prefix=ltrim(strstr($fieldids,','),',');
						}
						$sql=DB::sqlSelect($tablename,'','*',$fieldid.'='.$this->uid(),'',1);
						//$this->treeUa->doAppendTree(DB::queryTree($sql));
						$this->treeUa->doAppendTree(DB::queryTree($sql),$prefix);
					}
					$treeRelation->doMove();
				}
			}
			if(DEBUGV=='mau') debugTree($this->treeUa);
		}
	}
	public function uTree()
	{
		$this->uInit();
		return $this->treeUa;
	}
	public function uValue($field)
	{
		$this->uInit();
		return $this->treeUa->getItem($field);
	}

}
?>