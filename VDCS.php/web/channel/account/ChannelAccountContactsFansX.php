<?
class ChannelAccountContactsFansX extends ChannelAccountBaseX
{
	
	protected function initPaging()
	{
		$listnum=queryi('listnum');
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
	}
	public function parseList()   //生成XML格式
	{
		$this->initPaging();
		$this->tableList=UcContactsFollow::querier('fans',$this->ua->id,$this->p);//$this->p为一个分页对象
		//debugx('total='.$this->p->getTotal().', page='.$this->p->getPage().', pagetotal='.$this->p->getPageTotal().', listnum='.$this->p->getListNum());
		//debugTable($this->tableList);
		//debugx(queryi('p'));
		
		UcContactsFollow::doEachoFilter($this->tableList,'fans',$this->ua->id);
		NoticeAction::setx($this->ua,'new_fans',0);
		
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
		$this->setSucceed();
	}
        
        
	/*
	########################################
	########################################
	*/
	public function parseCreate()
	{
		if(!$this->ready()) return;
		
		$uid=queryi('uid');
		if(!$this->isErrorCheck()){
			if($uid<1) $this->addError('关注对象为空！');
		}
		if(!$this->isErrorCheck()){
			if($uid==$this->ua->id) $this->addError('您有点自恋嘛？！');
		}
		
		if($this->isRaiseError()) return;
		
		$_status=UcContactsFollow::create($this->ua,$uid);
		switch($_status){
			case 1:			$this->setSucceed();break;
			case 2:			$this->setStatus('already');break;
			default:		$this->setStatus('failed');break;
		}
	}
	
	public function parseCancel()
	{
		if(!$this->ready()) return;
		
		$uid=queryi('uid');
		if(!$this->isErrorCheck()){
			if($uid<1) $this->addError('取消关注对象为空！');
		}
		if(!$this->isErrorCheck()){
			if($uid==$this->ua->id) $this->addError('您有点自恋嘛？！');
		}
		
		if($this->isRaiseError()) return;
			$_status=UcContactsFollow::cancel($this->ua,$uid);
			
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
	
	public function parseSearch()
	{
		$names=queryx('names');
		$this->initPaging();
		$this->tableList=UcContactsFollow::searchPserson('fans',$this->ua->id,$this->p,$names);
		$this->groupTableList=UcContactsGroup::getTableByID($this->ua->id);
		UcContactsFollow::doEachoFilter($this->tableList,'fans',$this->ua->id);
		$this->setTable($this->tableList);
		$this->addTable('group',$this->groupTableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
	}
	
}
?>