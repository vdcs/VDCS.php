<?
class ChannelAccountSettingsModifyX extends ChannelAccountSettingsBaseX
{	
	public function parseInfo()
	{
		$_status=0;
		if(!isPost()){
			$this->setStatus('notpost');
			return;
		}
		$this->doFormData();
		if($this->isRaiseError()){
			$this->setStatus('error');
			return;
		}
		
		$_status=$this->ua->updateTree($this->treeData);
		if($_status){
			$sql='update '.$this->ua->struct('TableName','info').' set sign='.DB::q($this->treeData->getItem('sign'),1).' where uid='.$this->ua->id;;
			DB::exec($sql);
		}
		$this->ua->clearCache();
		
		if($_status) $this->setSucceed();
		else $this->setStatus('save');
	}
	
	protected function doFormData()
	{
		$names=posts('names');
		$sign=posts('sign');
		$gender=posti('gender');
		$bday_year=posti('bday_year');
		$bday_month=posti('bday_month');
		$bday_day=posti('bday_day');
		
		if(!utilCheck::isName($names)) $this->addError('昵称为空 或 格式不正确');
		//'select count(*) from '.$this->ua->TableName.' where names='.DB::q($names,1).' and '.$this->ua->FieldID.'!='.$this->ua-id;
		
		//if(!$gender) $this->addError('性别不能为空');
		$birthday=$bday_year.'-'.$bday_month.'-'.$bday_day;
		if(($bday_year || $bday_month || $bday_day) && (!utilCheck::isDate($birthday))) $this->addError('生日格式不正确');
		
		$this->addData('names',$names);
		$this->addData('sign',$sign);
		$this->addData('gender',$gender);
		$this->addData('birthday',$birthday);
		
		
	}
}
?>