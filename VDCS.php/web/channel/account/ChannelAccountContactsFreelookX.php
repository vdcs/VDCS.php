<?
class ChannelAccountContactsFreelookX extends ChannelAccountBaseX
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
		//随机获取30人
		$uids=DB::queryTable('select uid from db_user where uid!='.$this->ua->id.' and uid!=10000')->getValues('uid');
		$uidsAry=explode(',',$uids);
		$rand_key=array_rand($uidsAry,30);
		$uidsAryNew=array();
		foreach($rand_key as $v){
			$uidsAryNew[]=$uidsAry[$v];
		}
		$uids=implode($uidsAryNew,',');
		
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
		$this->tableList=ContactsFollow::searchPserson('play',$uids,$this->p,$uname);
		ContactsFollow::doFollowFilter($this->tableList,'all',$this->ua->id);
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
		
	}

}
?>