<?
class ChannelAccountContactsRecentX extends ChannelAccountBaseX
{
	
	public function doParse()
	{
		$this->setStatus('init');
		switch($ctl->action){
			case 'list':
				$this->doParseList();
				break;
			case 'searchPerson':
				$this->doParseSearchPerson();
				break;
		}
	}
	
        
	/*
	########################################
	########################################
	*/
	public function doParseList()   //生成XML格式
	{
		$this->initPaging();
		$uids='';
		$uids=ContactsFollow::getRecentPerson($this->ua->id);
		if($uids){
			$this->tableList=ContactsFollow::querier('all',$uids,$this->p);//$this->p为一个分页对象
			ContactsFollow::doFollowFilter($this->tableList,'all',$this->ua->id);
			$this->setTable($this->tableList);
			$this->addVar('total',$this->tableList->getRow());
			$this->addVarPaging();
		}
	}
        
	protected function initPaging()
	{
		$listnum=queryi('listnum');
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
	}
	
	public function doParseSearchPerson()
	{
		$uname=queryx('uname');
		$this->initPaging();
		$uids='';
		$uids=ContactsFollow::getRecentPerson($this->ua->id);
		if($uids){
			$this->tableList=ContactsFollow::searchPserson('all',$uids,$this->p,$uname);
			ContactsFollow::doFollowFilter($this->tableList,'all',$this->ua->id);
			$this->setTable($this->tableList);
			$this->addVar('total',$this->tableList->getRow());
			$this->addVarPaging();
		}
	}

}
?>