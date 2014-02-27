<?
class UcPivotalManage extends UcPivotal
{
	const DataPX			= 'pivotal_';
	const TableFieldsAdd		= 'uurc,uuid,uaid,password,password_login,password_payment,password_safe,safe_question,safe_answer,safe_question1,safe_answer1,safe_question2,safe_answer2,safe_question3,safe_answer3,safe_mobile,safe_email,safe_certid,safe_certno,timed,timed_status,timed_expire,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,configs,configure,status,tim,tim_up,tim1,tim2,tim3,explain';
	const TableFieldsEdit		= 'uaid,password,password_login,password_payment,password_safe,safe_question,safe_answer,safe_question1,safe_answer1,safe_question2,safe_answer2,safe_question3,safe_answer3,safe_mobile,safe_email,safe_certid,safe_certno,timed,timed_status,timed_expire,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,configs,configure,status,tim,tim_up,tim1,tim2,tim3,explain';
	
	
	/*
	########################################
	########################################
	*/
	public function getFormDataTree()
	{
		$reTree=newTree();
		$this->loadData();
		if($this->_isData){
			$this->treeData->doBegin();
			for($t=0;$t<$this->treeData->getCount();$t++){
				$reTree->addItem(self::DataPX.$this->treeData->getItemKey(),$this->treeData->getItemValue());
				$this->treeData->doMove();
			}
		}
		return $reTree;
	}
	public function doFormCheck()
	{
		if(!$this->is()) return;
		global $ctl;
		if(!$ctl->e->isCheck()){
			$_password=$ctl->treeData->getItem(self::DataPX.'password');
			if(len($_password)>0){
				if(!utilCheck::isPassword($_password)) $ctl->e->addItem($this->getFormMessage('norule.password'));
			}
			$_question=$ctl->treeData->getItem(self::DataPX.'question');
			if(len($_question)>0){
				if(!utilCheck::isSecure($_question)) $ctl->e->addItem($this->getFormMessage('norule.question'));
			}
			$_answer=$ctl->treeData->getItem(self::DataPX.'answer');
			if(len($_answer)>0 ){
				if(!utilCheck::isPassword($_answer)) $ctl->e->addItem($this->getFormMessage('norule.answer'));
			}
		}
	}
	public function getFormMessage($key)
	{
		global $mp;
		$re='';
		if(iso($mp)) $re=$mp->getLang('error.'.$key);
		if(len($re)<1){
			switch($key){
				case 'norule.password':		$re='登录密码 不符合规则！';break;
				case 'norule.question':		$re='提示问题 不符合规则！';break;
				case 'norule.answer':		$re='问题答案 不符合规则！';break;
			}
		}
		return $re;
	}
	public function doFormSave(&$tData)
	{
		$this->doSave($tData,self::DataPX);
	}
	
	public function doSave(&$tData,$DataPX='')
	{
		if(!$this->is()) return;
		//debugTree($treeData);
		if(!$this->_isData){
			$this->treeData=newTree();
			$this->treeData->addItem('uurc',$this->urc);
			$this->treeData->addItem('uuid',$this->uid);
			$this->treeData->addItem('uaid',0);
			
			$this->treeData->addItem('password','');
			$this->treeData->addItem('password_login','');
			$this->treeData->addItem('password_payment','');
			$this->treeData->addItem('password_safe','');
			$this->treeData->addItem('safe_question','');
			$this->treeData->addItem('safe_answer','');
			
			$this->treeData->addItem('safe_certid',0);

			$this->treeData->addItem('timed',0);
			$this->treeData->addItem('timed_status',0);
			$this->treeData->addItem('timed_expire',0);
			
			$this->treeData->addItem('prop1','');
			$this->treeData->addItem('prop2','');
			$this->treeData->addItem('prop3','');
			$this->treeData->addItem('prop4','');
			$this->treeData->addItem('prop5','');
			$this->treeData->addItem('int1',0);
			$this->treeData->addItem('int2',0);
			$this->treeData->addItem('int3',0);
			$this->treeData->addItem('num1',0);
			$this->treeData->addItem('num2',0);
			$this->treeData->addItem('num3',0);
			$this->treeData->addItem('num4',0);
			$this->treeData->addItem('num5',0);
			
			$this->treeData->addItem('configs','');
			$this->treeData->addItem('configure','');
			
			$this->treeData->addItem('status',1);
			$this->treeData->addItem('tim',DCS::timer());
			$this->treeData->addItem('tim_up',0);
			$this->treeData->addItem('tim_last',0);
			$this->treeData->addItem('tim1',0);
			$this->treeData->addItem('tim2',0);
			$this->treeData->addItem('tim3',0);
			
			$this->treeData->addItem('explain','');
		}
		
		$_fields='password,password_login,password_payment,password_safe,safe_answer';
		$arFields=toSplit($_fields,',');
		for($a=0;$a<count($arFields);$a++){
			$_field=$arFields[$a];
			$_value=$tData->getItem($DataPX.$_field);
			if(len($_value)>0 && $_value!=$this->treeData->getItem($_field)) $tData->setItem($DataPX.$_field,utilCoder::toMD5i($_value));
		}
		
		if(len($DataPX)>0){
			$lenPX=len(self::DataPX);
			$tData->doBegin();
			for($t=0;$t<$tData->getCount();$t++){
				$key=$tData->getItemKey();
				if(left($key,$lenPX)==self::DataPX){
					$this->treeData->addItem(toSubstr($key,$lenPX+1),$tData->getItemValue());
				}
				$tData->doMove();
			}
		}
		else{
			$tData->doBegin();
			for($t=0;$t<$tData->getCount();$t++){
				$this->treeData->addItem($tData->getItemKey(),$tData->getItemValue());
				$tData->doMove();
			}
		}
		
		$this->doDataSave($this->treeData);
	}
	
	public function doDataSave($tData)
	{
		if($this->_isData){
			$sql=DB::sqlUpdate(self::TableName,self::TableFieldsEdit,$tData,$this->sqlKey);
		}
		else{
			$sql=DB::sqlInsert(self::TableName,self::TableFieldsAdd,$tData);
		}
		//debugx($sql);
		DB::exec($sql);
	}
	public function doDataRemove($ids)
	{
		$sqlQuery='uurc=\''.$this->urc.'\' and uuid in ('.$ids.')';
		$sql=DB::toSQLDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doUpdateSet($sqlSet,$queryi=null)
	{
		if(!$sqlSet) return false;
		if($queryi==null){
			$queryi=$this->sqlQuery(-1);
		}
		$sql='update '.self::TableName.' set '.$sqlSet.' where '.$queryi;
		//debugx($sql);
		return DB::exec($sql);
	}
	public function doUpdateTree($tData,$queryi=null)
	{
		if($queryi==null){
			$queryi=$this->sqlQuery(-1);
		}
		$sql=DB::sqlUpdate(self::TableName,$tData->getFields(),$tData,$queryi);
		//debugx($sql);
		return DB::exec($sql);
	}
	
}
?>