<?
class UcMemoManage extends UcMemo
{
	const DataPX			= 'memo_';
	const TableFieldsAdd		= 'channel,action,rootid,dataid,uurc,uuid,types,no,mode,sort,type,level,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,topic,icon,summary,remark,sp_code,sp_ip,sp_agent,isread,islock,status,tim,tim_up,tim1,tim2,tim3,explain';
	const TableFieldsEdit		= 'types,no,sort,type,level,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5,topic,icon,summary,remark,sp_code,sp_ip,sp_agent,isread,islock,status,tim,tim_up,tim1,tim2,tim3,explain';
	
	
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
		global $mp;
		$re=$mp->getLang('error.'.$key);
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
			$this->treeData->addItem('types',0);
			
			$this->treeData->addItem('no','');
			$this->treeData->addItem('mode','');
			$this->treeData->addItem('sort','');
			$this->treeData->addItem('type','');
			$this->treeData->addItem('level','');
			
			$this->treeData->addItem('prop1','');
			$this->treeData->addItem('prop2','');
			$this->treeData->addItem('prop3','');
			$this->treeData->addItem('prop4','');
			$this->treeData->addItem('prop5','');
			$this->treeData->addItem('int1',0);
			$this->treeData->addItem('int2',0);
			$this->treeData->addItem('num1',0);
			$this->treeData->addItem('num2',0);
			$this->treeData->addItem('num3',0);
			$this->treeData->addItem('num4',0);
			$this->treeData->addItem('num5',0);
			
			$this->treeData->addItem('topic','');
			$this->treeData->addItem('icon',0);
			$this->treeData->addItem('summary','');
			$this->treeData->addItem('remark','');
			
			$this->treeData->addItem('sp_code',0);
			$this->treeData->addItem('sp_ip',DCS::ip());
			$this->treeData->addItem('sp_agent',DCS::agent());
			
			$this->treeData->addItem('isread',0);
			$this->treeData->addItem('islock',0);
			$this->treeData->addItem('status',1);
			$this->treeData->addItem('tim',DCS::timer());
			$this->treeData->addItem('tim_up',0);
			$this->treeData->addItem('tim1',0);
			$this->treeData->addItem('tim2',0);
			$this->treeData->addItem('tim3',0);
			
			$this->treeData->addItem('explain','');
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
		
		//debugTree($this->treeData);
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
	public function doDataRemove($rootids,$actions='',$dataids='')
	{
		$sqlQuery='channel=\''.$this->channel.'\' and rootid in ('.rootids.')';
		if($actions) $sqlQuery=DB::sqla($sqlQuery,'action in(\''.r($actions,',','\',\'').'\')');
		if($dataids) $sqlQuery=DB::sqla($sqlQuery,'dataid in ('.$dataids.')');
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
}
?>