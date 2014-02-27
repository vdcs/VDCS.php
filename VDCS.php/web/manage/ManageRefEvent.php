<?
trait ManageRefEvent
{
	
	protected function isChecked($checks_='lock')
	{
		$re=true;
		if(ins($checks_,'lock')>0 && $this->ma->isLocked()){
			$this->setStatus('lock');
			//$this->doMessages('!','!manager.lock','');
			$re=false;
		}
		return $re;
	}
	
	
	public function doEventLog($strName,$strType,$strTopic,$strExplain)
	{
		if(!$strName) $strName=$this->ma->name;
		$sql=	"insert into dbs_manage_log(ures,uname,l_type,l_topic,l_explain,l_tim,sp_ip,sp_agent) " .
			"values('',".DB::q($strName,1).",".$strType.",".DB::q(utilCode::toCut($strTopic,200,''),1).",".DB::q(utilCode::toCut($strExplain,250,''),1).",".DCS::timer().",".DCS::ip().",".utilCode::toCut(DCS::agent(),250,'').")";
		//debugx($sql);
		//DB::exec($sql);
	}
	
	//添加日志
	public function doModifyLogs()
	{
		$fields=$this->getConfig('modify.log.fields');
		if(!$fields) return;
		$fieldsAry=utilString::toAry($fields);
		foreach($fieldsAry as $v){
			if($this->treeData->getItem($v)!=$this->treeRS->getItem($v)){
				$moduleid=$this->id;
				ManageRecordModify::create($this->ma,[
					'module'=>'account','moduleid'=>$moduleid,
					'field1'=>'value1','value1'=>$this->treeRS->getItem($v),
					'field2'=>'value2','value2'=>$this->treeData->getItem($v),
					'value5'=>$v,'names'=>'修改账户信息，将 '.$v,
					'summary'=>''
				]);
			}
		}
	}
	
}
?>