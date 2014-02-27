<?
class ChannelAccountContactsRelatedX extends ChannelAccountBaseX
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
		//统计订阅标签相同数
		$tagids=TTagsQuery::getFollowTagsByUid($this->ua->id,'tagid','status=1')->getValues('tagid');
		$tagidsAry=array_unique(explode(',',$tagids));
		$ary=array();
		$totalTimes='';
		foreach($tagidsAry as $tagid){
			$totalTimes.=DB::queryTable('select uuid from db_tags_follow where tagid='.$tagid.' and status=1')->getValues('uuid').',';
		}
		$totalTimes=trim($totalTimes,',');
		$ary=explode(',',$totalTimes);
		$toji=array_count_values($ary);
		asort($toji);
		$toji=array_slice($toji,0,30,true);
		foreach($toji as $k=>$v){
			if($k!=$this->ua->id){
				$uids.=$k.',';
			}
		}
		$uids=trim($uids,',');
		
		
		$this->initPaging();
		if($uids){
			$this->tableList=ContactsFollow::querier('all',$uids,$this->p);//$this->p为一个分页对象
			ContactsFollow::doFollowFilter($this->tableList,'all',$this->ua->id);
			$this->setTable($this->tableList);
			$this->addVar('total',$this->tableList->getRow());
			$this->addVarPaging();
		}
	
		$this->setTable($this->tableList);
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