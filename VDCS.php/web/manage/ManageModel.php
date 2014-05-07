<?
class ManageModel
{
	const TABLE_SYS_CLASS_NAME='dbs_class';
	
	private $tableClass=null;
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unset($this->tableClass);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setChannel($s,$m){$this->channel=$s;$this->module=$m;}
	
	public function doInit()
	{
		global $chn;
		if(!$this->channel) $this->channel=$chn->getChannel();
		$this->channels=$this->channel;
		if($this->module) $this->channels.='.'.$this->module;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isClassPower()
	{
		global $chn;
		if(!$this->_isClassPowered){
			if($this->ma->getGrade()<9 && $chn->getConfig($chn->modulesc,'define.class.ispower')=='yes') $this->_isClassPower=true;
			$this->_isClassPowered=true;
		}
		return $this->_isClassPower;
	}
	
	public function loadClass()
	{
		if($this->_isLoadClass) return;
		$this->doInit();
		$sql='select * from '.self::TABLE_SYS_CLASS_NAME.' where channel=\''.$this->channels.'\' order by rootid,orderid';
		if($this->isClassPower()){
			$sql='select * from '.self::TABLE_SYS_CLASS_NAME.' where channel=\''.$this->channels.'\' and rootid in (select rootid from '.self::TABLE_SYS_CLASS_NAME.' where channel=\''.$this->channels.'\' and levelid=1 and PATINDEX(\'%'.$this->ma->name.'%\',managers)>0 group by rootid) order by rootid,orderid';
		}
		$this->tableClass=DB::queryTable($sql);
		$this->_isLoadClass=true;
	}
	
	public function doClassListAppend()
	{
		global $mp;
		if($this->isClassPower()){
			$tmpIDS=$this->getClassPowerIDS();
			if(len($tmpIDS)<1) $tmpIDS='0';
			$mp->setAppendQuery('classid in ('.$tmpIDS.')');
		}
	}
	
	public function doClassActionAppend()
	{
		global $ctl;
		if($this->isClassPower()){
			$ctl->pages->addFormVar('class.power.att',$this->getClassPowerAtt());
			$ctl->pages->addFormVar('ispower','no');
		}
		else{
			$ctl->pages->addFormVar('power.class','no');
		}
	}
	
	public function getClassPowerIDS()
	{
		$this->loadClass();
		$re='';
		if(isTable($this->tableClass)){
			$this->tableClass->doItemBegin();
			for($t=0;$t<$this->tableClass->getRow();$t++){
				$re.=','.$this->tableClass->getItemValue('classid');
				$this->tableClass->doItemMove();
			}
		}
		if(len($re)>1) $re=toSubstr($re,2);
		return $re;
	}
	
	public function getClassPowerAtt()
	{
		$this->loadClass();
		$re='';
		if(isTable($this->tableClass)){
			$this->tableClass->doItemBegin();
			for($t=0;$t<$this->tableClass->getRow();$t++){
				$tmpSpacer='';
				if($this->tableClass->getItemValueInt('levelid')<2){
					$tmpSpacer='';
				}
				else{
					for($j=0;$j<($this->tableClass->getItemValueInt('levelid')-1);$j++){
						$tmpSpacer.='--';
					}
				}
				$re.='|'.$this->tableClass->getItemValue('classid').':'.$tmpSpacer.$this->tableClass->getItemValue('name');
				$this->tableClass->doItemMove();
			}
		}
		if(len($re)>1) $re=toSubstr($re,2);
		return $re;
	}
	
	public function getClassList()
	{
		$this->loadClass();
		$re='';
		if(isTable($this->tableClass)){
			$this->tableClass->doItemBegin();
			for($t=0;$t<$this->tableClass->getRow();$t++){
				$tmpClassid=$this->tableClass->getItemValue('classid');
				$re.=NEWLINE.'modClass.Datas["c'.$tmpClassid.'"]={"name":"'.$this->tableClass->getItemValue('name').'","rootid":"'.$this->tableClass->getItemValue('rootid').'","fatherid":"'.$this->tableClass->getItemValue('fatherid').'","levelid":"'.$this->tableClass->getItemValue('levelid').'","url":"'.$mp->getURL('action=list&classid='.$tmpClassid).'"};';
				$this->tableClass->doItemMove();
			}
		}
		$re=NEWLINE.'<script type="text/javascript">'.$re.NEWLINE.'modClass.doShow();</script>';
		return $re;
	}
}
