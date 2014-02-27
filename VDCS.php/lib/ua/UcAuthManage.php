<?
class UcAuthManage extends UcAuth
{
	const DataPX			= 'auth_';
	const TableFieldsAdd		= 'channel,uurc,uuid,sorts,types,res_type,res_id,res_value,value,value1,value2,value3,value4,value5,summary,tim,tim_up,status,explain,trans,trans_tim,trans_status,trans_value1,trans_value2,trans_value3,trans_value4,trans_value5,trans_summary,trans_explain';
	const TableFieldsEdit		= 'res_type,res_id,res_value,value,value1,value2,value3,value4,value5,summary,tim,tim_up,status,explain,trans,trans_tim,trans_status,trans_value1,trans_value2,trans_value3,trans_value4,trans_value5,trans_summary,trans_explain';
	const FieldResType		= 'res_type';
	const FieldResID		= 'res_id';
	const FieldResValue		= 'res_value';
	const ValueResType		= 'upload';
	const ValueStatus		= 0;
	
	
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
		
	}
	public function getFormMessage($key)
	{
		//$re=$mp->getLang('error.'.$key);
		if(len($re)<1){
			switch($key){
				case 'norule.test':		$re='测试项 不符合规则！';break;
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
			$this->treeData->addItem('channel',$this->channel);
			$this->treeData->addItem('action',$this->action);
			$this->treeData->addItem('rootid',$this->rootid);
			$this->treeData->addItem('dataid',$this->dataid);
			$this->treeData->addItem('uurc',$this->urc);
			$this->treeData->addItem('uuid',$this->uid);
			$this->treeData->addItem('sorts',$this->sorts);
			$this->treeData->addItem('types',$this->types);
			
			$this->treeData->addItem('res_type',self::ValueResType);
			$this->treeData->addItem('res_id',0);
			$this->treeData->addItem('res_value','');
			$this->treeData->addItem('value','');
			$this->treeData->addItem('value1','');
			$this->treeData->addItem('value2','');
			$this->treeData->addItem('value3','');
			$this->treeData->addItem('value4','');
			$this->treeData->addItem('value5','');
			$this->treeData->addItem('summary','');
			
			$this->treeData->addItem('sp_ip',VDCS_Request::getBrowseIP());
			$this->treeData->addItem('sp_agent',VDCS_Request::getBrowseAgent());
			
			$this->treeData->addItem('tim',DCS::timer());
			$this->treeData->addItem('tim_up',0);
			$this->treeData->addItem('status',self::ValueStatus);
			$this->treeData->addItem('explain','');
			
			$this->treeData->addItem('trans',0);
			$this->treeData->addItem('trans_tim',0);
			$this->treeData->addItem('trans_status',0);
		}
		
		if($DataPX){
			$lenPX=len(self::DataPX);
			$tData->doBegin();
			for($t=1;$t<=$tData->getCount();$t++){
				$key=$tData->getItemKey();
				if(left($key,$lenPX)==self::DataPX){
					$this->treeData->addItem(toSubstr($key,$lenPX+1),$tData->getItemValue());
				}
				$tData->doMove();
			}
		}
		else{
			$tData->doBegin();
			for($t=1;$t<=$tData->getCount();$t++){
				//debugx($tData->getItemKey().','.$tData->getItemValue());
				$this->treeData->addItem($tData->getItemKey(),$tData->getItemValue());
				$tData->doMove();
			}
		}
		
		//debugTree($this->treeData);
		$this->doDataSave($this->treeData);
	}
	
	public function doDataSave($tData)
	{
		if($this->_isData){
			$sql=DB::sqlUpdate(self::TableName,self::TableFieldsEdit,$tData,$this->sqlKey,$this->treeData);
		}
		else{
			$sql=DB::sqlInsert(self::TableName,self::TableFieldsAdd,$tData);
		}
		//debugx($sql);
		DB::exec($sql);
	}
	public function doDataRemove($rootids,$sorts='')
	{
		$sqlQuery=$this->sqlKey;	//'channel='.DB::q($this->channel,1).' and rootid in ('.rootids.')';
		if($sorts) $sqlQuery=DB::sqlAppend($sqlQuery,'sorts in(\''.r($sorts,',','\',\'').'\')');
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
}
?>