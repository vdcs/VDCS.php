<?
class ChannelAccountContactsFollowX extends ChannelAccountBaseX
{
	
	protected function initPaging()
	{
		$listnum=queryi('listnum');
		//if($listnum<3 || $listnum>20) $listnum=10;
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);
		$this->p->setPage(queryi('page'));
	}
	
	protected function parseList()   //生成XML格式
	{
		$groupid=query('groupid');
		if($groupid!='') $groupid=queryi('groupid');
		$this->initPaging();
		$this->tableList=UcContactsFollow::querier('follow',$this->ua->id,$this->p,$groupid);
		$this->groupTableList=newTable();	//UcContactsGroup::getTableByID($this->ua->id);
		UcContactsFollow::doEachoFilter($this->tableList,'follow',$this->ua->id);
		//$this->doDataFileter($this->tableList);
		$this->setTable($this->tableList);
		$this->addTable('group',$this->groupTableList);
		$total=queryi('listnum');
		if(!$total) $total=$this->tableList->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();
		$this->setSucceed();
	}
	public function doDataFileter(&$tableList){
		//$tableList->doAppendFields('mainexp_now,vit,pro,fame,grade,gc_all_exp');
		$tableList->doItemBegin();
		while($tableList->isNext()){
			$uid=$tableList->getItemValue('uuid2');
			/*
			$userTree=TGamecard::getExpnow($uid);
			$tableList->setItemValue('mainexp_now',$userTree->getItem('mainexp_now'));
			$tableList->setItemValue('vit',$userTree->getItem('vit'));
			$tableList->setItemValue('pro',$userTree->getItem('pro'));
			$tableList->setItemValue('fame',$userTree->getItem('fame'));
			$tableList->setItemValue('grade',$userTree->getItem('grade'));
			$tableList->setItemValue('gc_all_exp',$userTree->getItem('gc_all_exp'));
			*/
		}
	}

	public function parseLists()
	{
		$this->tableList=UcContactsFollow::getList('follow',$this->ua->id,0,0);
		//debugTable($this->tableList);
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->setSucceed();
	}
        
	public function parseSearch()
	{
		$names=queryx('names');
		$this->initPaging();
		$this->tableList=UcContactsFollow::searchPserson('follow',$this->ua->id,$this->p,$names);
		$this->groupTableList=newTable();	//UcContactsGroup::getTableByID($this->ua->id);
		UcContactsFollow::doEachoFilter($this->tableList,'follow',$this->ua->id);
		$this->setTable($this->tableList);
		$this->addTable('group',$this->groupTableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
	}
	
        
	/*
	########################################
	########################################
	*/
	public function parseCreates()
	{
		$uids=query('uids');
		$idAry=explode(',',$uids);
		foreach($idAry as $uid){
			//TGamecard::addExpItem($uid,'gc_fans');//添加被关注
			UcContactsFollow::create($this->ua,$uid);	
		}
		$this->setSucceed();
	}
	public function parseCreate()
	{
		if(!$this->ready()) return;
		//$this->vcodeFormCheck();
		
		$uid=queryi('uid');
		$this->addVar('uid',$uid);
		if(!$this->isErrorCheck()){
			if($uid<1) $this->addError('关注对象为空！');
		}
		if(!$this->isErrorCheck()){
			if($uid==$this->ua->id) $this->addError('您有点自恋嘛？！');
		}
		
		if($this->isRaiseError()) return;
		//$this->addVarTree($->treeData,'data.');
		//$this->testo($->treeData,'treeData');
		
		$_status=UcContactsFollow::create($this->ua,$uid);
		//TGamecard::addExpItem($uid,'gc_fans');//添加被关注
		switch($_status){
			case 1:
				$this->setSucceed();
				break;
			case 2:
				$this->setStatus('already');
				break;
			default:
				$this->setStatus('failed');
				break;
		}
	}
	
	public function parseCancels()
	{
		if(!$this->ready()) return;
		$uids=query('uids');
		$uidsAry=explode(',',$uids);
		if(count($uidsAry)<1){
			$this->setStatus('not');
			return;
		}
		foreach($uidsAry as $uid){
			$_status=UcContactsFollow::cancel($this->ua,$uid);
			//TGamecard::addExpItem($uid,'gc_fans',1);//取消被关注
		}
		$this->setSucceed();
	}
	public function parseCancel()
	{
		if(!$this->ready()) return;
		
		$uid=queryi('uid');
		$this->addVar('uid',$uid);
		if(!$this->isErrorCheck()){
			if($uid<1) $this->addError('取消关注对象为空！');
		}
		if(!$this->isErrorCheck()){
			//if($uid==$this->ua->id) $this->addError('您有点自恋嘛？！');
		}
		
		if($this->isRaiseError()) return;
		
		$_status=UcContactsFollow::cancel($this->ua,$uid);
		//TGamecard::addExpItem($uid,'gc_fans',1);//取消被关注
		
		switch($_status){
			case 1:
				$this->setSucceed();
				break;
			case 2:
				$this->setStatus('not');
				break;
			default:
				$this->setStatus('failed');
				break;
		}
	}
	
}
