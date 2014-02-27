<?
trait UaRefManage
{
	
	public function init()
	{
		parent::init();
		if($this->isinitm)return;$this->isinitm=true;
		
		// pivotal
		$this->mpivotal=new UcPivotalManage();
		$this->mpivotal->setURC($this->rc);
		$this->mpivotal->init();
		
		$this->_isPivotalSave=false;
		$this->_isInfo=$this->struct('is','info');
		
		// fields
		if(!isa($this->TableFields)){
			$this->TableFields=['base:add'=>$this->TableFieldsAdd,'base:edit'=>$this->TableFieldsEdit,'info:add'=>$this->TableInfoFieldsAdd,'info:edit'=>$this->TableInfoFieldsEdit];
		}
		foreach($this->TableFields as $k=>$v){
			$fields=$v;
			$fields=r($fields,'{@table.px}',$this->TablePX);
			$fields=r($fields,'{@tpx}',$this->TablePX);
			$this->TableFields[$k]=$fields;
		}
		//debuga($this->TableFields);
	}
	
	public function setPivotalSave($v){$this->_isPivotalSave=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function isData(){return $this->_isData;}
	public function loadData($by=null,$value=null)
	{
		if($this->_isData) return true;
		if(is_null($by)) $by=$this->id;
		if(!$by) return false;
		if($this->_loadData) return;$this->_loadData=true;
		$sqlQuery=$this->sqlQuery($by,$value);	//$this->FieldID.'='.$this->id.'';
		$_res=$this->_isInfo?1:0;
		$this->treeDatar=$this->queryTree($sqlQuery,$_res);		//,true
		//debugTree($this->treeDatar);
		$id=$this->treeDatar->getItemInt($this->FieldID);
		if(!$id) return false;
		$this->id=$id;
		$this->mpivotal->setUID($this->id);
		$this->dataAppend($this->treeDatar);
		if($this->isQueryInfo($this->id)){
			$this->_isDataInfo=true;
		}
		$this->_isData=true;
		$this->sqlKey=$sqlQuery;
		return true;
	}
	public function getDataTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->treeDatar->getArray());
		return $reTree;
	}
	public function getData($k){return $this->treeDatar->getItem($k);}
	public function getDataInfo($k){return $this->treeDatar->getItem($k);}
	
	public function getDatar($k){return $this->treeDatar->getItem($k);}
	public function getDatarTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->treeDatar->getArray());
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doCreate($tData,$id=0,$aid=0)
	{
		$this->doSave($tData,$id,$aid);
	}
	public function doSave($tData,$id=0,$aid=0)
	{
		$sData=newTree();
		if(!isTree($this->treeData)) $this->treeData=newTree();
		if(!$this->_isData){
			if($id<1) $id=$this->newID();
			$this->id=$id;
			$this->treeData->addItem($this->struct('FieldId'),$id);
			$this->treeData->addItem('uaid',$aid);
			$this->setDataDefault($this->treeData);
		}
		
		$tData->doBegin();
		for($t=0;$t<$tData->getCount();$t++){
			$key=$tData->getItemKey();
			$this->treeData->addItem($tData->getItemKey(),$tData->getItemValue());
			$this->treeData->addItem($this->TablePX.$tData->getItemKey(),$tData->getItemValue());
			$tData->doMove();
		}
		
		$_field=$this->TablePX.'birthday';
		if(ins($this->TableFields['base:add'],','.$_field.',')>0 || ins($this->TableFields['base:edit'],','.$_field.',')>0){
			if(len($this->treeData->getItem($_field))<1) $this->treeData->addItem($_field,'0000-00-00');
		}
		
		//debugTree($this->treeData);
		$this->doDataSave($this->treeData);
		
		$pivotalSave=$this->_isPivotalSave;
		if(!$this->_isData) $pivotalSave=true;
		if($pivotalSave){
			// pivotal
			//##########
			$pData=newTree();
			$pData->addItem('password',$tData->getItem('password'));
			$pData->addItem('safe_question',$tData->getItem('safe_question'));
			$pData->addItem('safe_answer',$tData->getItem('safe_answer'));
			//debugTree($pData);
			//##########
			if(!$this->_isData && $id>0) $this->mpivotal->setUID($id);
			$this->mpivotal->doSave($pData);
			//##########
		}
	}
	
	public function doDataSave($tData)
	{
		if($this->_isData){
			$sql=DB::sqlUpdate($this->TableName,$this->TableFields['base:edit'],$tData,$this->sqlKey);
		}
		else{
			$sql=DB::sqlInsert($this->TableName,$this->TableFields['base:add'],$tData);
		}
		//debugx($sql);
		DB::exec($sql);
		if($this->_isInfo){
			if($this->_isDataInfo){
				$sql=DB::sqlUpdate($this->struct('TableName','info'),$this->TableFields['info:edit'],$tData,$this->sqlKey);
				$this->clearCache();
			}
			else{
				$sql=DB::sqlInsert($this->struct('TableName','info'),$this->TableFields['info:add'],$tData);
			}
			//debugx($sql);
			DB::exec($sql);
		}
	}
	
	
	public function doModifyPassword($value)
	{
		if(len($value)<1) return;
		if(len($value)>0 && len($value)!=16 && $value!=$this->_data['_password']) $value=utilCoder::toMD5i($value);
		$this->_data['_password']=$value;
		$this->setClientData('password',$value);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function newID()
	{
		$sql='select max('.$this->FieldID.') from '.$this->TableName;
		$id=DB::queryInt($sql)+1;
		if($id<$this->MinID) $id=$this->MinID+1;
		return $id;
	}
	
	public function isExists($field,$value,$id=0)
	{
		$field=r($field,'{tpx}',$this->TablePX);
		$sqlQuery=$field.'='.DB::q($value,1);
		if($id>0) $sqlQuery=DB::sqla($sqlQuery,$this->FieldID.'<>'.$id);
		//$sql=DB::sqlSelect($this->TableName,'count','*',$sqlQuery,'',1);
		$sql=DB::sqlSelect($this->TableName,'',$this->FieldID,$sqlQuery,'',1);
		return (DB::queryInt($sql)>0);
	}
	public function isExistID($value,$id=0){return $this->isExists($this->FieldID,$value,$id);}
	public function isExistName($value,$id=0){return $this->isExists($this->TablePX.'name',$value,$id);}
	public function isExistNames($value,$id=0){return $this->isExists($this->TablePX.'names',$value,$id);}
	public function isExistEmail($value,$id=0){return $this->isExists($this->TablePX.'email',$value,$id);}
	public function isExistMobile($value,$id=0){return $this->isExists($this->TablePX.'mobile',$value,$id);}
	public function isExistIDCard($value,$id=0){return $this->isExists($this->TablePX.'idcard',$value,$id);}
	
	
	/*
	########################################
	########################################
	*/
	public function setDataDefault(&$tData)
	{
		//u_no,u_name,u_email,u_mobile,u_idtype,u_idcard
		$tData->addItem($this->TablePX.'groupid',0);
		$tData->addItem($this->TablePX.'teamid',0);
		$tData->addItem($this->TablePX.'grade',0);
		$tData->addItem($this->TablePX.'rank',0);
		
		$tData->addItem($this->TablePX.'gender',0);
		$tData->addItem($this->TablePX.'birthday','0000-00-00');
		//u_names,u_location,u_marks,u_prop1,u_prop2,u_prop3,u_prop4,u_prop5
		$tData->addItem($this->TablePX.'credit',0);
		$tData->addItem($this->TablePX.'money',0);
		$tData->addItem($this->TablePX.'emoney',0);
		$tData->addItem($this->TablePX.'points',0);
		$tData->addItem($this->TablePX.'exp',0);
		
		$tData->addItem($this->TablePX.'int1',0);
		$tData->addItem($this->TablePX.'int2',0);
		$tData->addItem($this->TablePX.'int3',0);
		$tData->addItem($this->TablePX.'num1',0);
		$tData->addItem($this->TablePX.'num2',0);
		$tData->addItem($this->TablePX.'num3',0);
		$tData->addItem($this->TablePX.'num4',0);
		$tData->addItem($this->TablePX.'num5',0);
		
		$tData->addItem($this->TablePX.'online',0);
		$tData->addItem($this->TablePX.'onlines',0);
		//u_config,u_certs
		$tData->addItem($this->TablePX.'auths','0000000000');
		$tData->addItem($this->TablePX.'auth_email',0);
		$tData->addItem($this->TablePX.'auth_mobile',0);
		$tData->addItem($this->TablePX.'auth_idcard',0);
		$tData->addItem($this->TablePX.'auth_cert',0);
		$tData->addItem($this->TablePX.'auth_real',0);
		
		$tData->addItem($this->TablePX.'timed',0);
		$tData->addItem($this->TablePX.'isauth',0);
		$tData->addItem($this->TablePX.'islock',0);
		$tData->addItem($this->TablePX.'status',1);
		//$tData->addItem($this->TablePX.'timezone',DCS::timezone());
		$tData->addItem($this->TablePX.'tim',DCS::timer());
		$tData->addItem($this->TablePX.'tim_up',0);
	}
	
}
?>