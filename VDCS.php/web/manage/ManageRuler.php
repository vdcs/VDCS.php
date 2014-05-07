<?
class ManageRuler extends UaRuler
{
	
	public function getRolePopedoms($roles)
	{
		$re='';
		if($roles){
			$sql='select popedoms from dbe_role where id in('.$roles.')';
			$tableRole=DB::queryTable($sql);
			$re=$tableRole->getValues('popedoms');
		}
		return $re;
	}
	
	public function getValuePopedoms()
	{
		$re=$this->ma->getData('popedoms');
		$roles=$this->ma->getData('roles');
		//$roles=$this->ma->uValue('emp.roles');
		if($roles) $re.=','.$this->getRolePopedoms($roles);
		if(DEBUGV=='popedoms'){
			debugx($roles);
			debugx($re);
		}
		return $re;
	}
	public function getValueGrade()
	{
		return $this->ma->getGrade();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doAuth()				//用户检测
	{
		$this->ma->setAuth(1);
		$this->ma->doInit();
	}
	
	public function doPopedomCheck()
	{
		
		parent::doPopedomCheck();
	}

}
