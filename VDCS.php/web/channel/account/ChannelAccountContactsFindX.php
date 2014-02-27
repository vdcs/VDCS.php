<?
class ChannelAccountContactsFindX extends ChannelAccountBaseX
{
	
	public function doParseList()
	{
		$this->initPaging();
		$uids='';
		$uidsAry='';				
		//$uids=DB::queryTable('select uid from db_user order by u_total_post desc limit 30')->getValues('uid');
		$uids=DB::queryTable('select uid from db_user order by uid desc limit 30')->getValues('uid');
		$arr=explode(',',$uids);				
		foreach($arr as $uid){
			if($uid!=$this->ua->id){
				$uidsAry.=$uid.',';			
			}		
		}
		$uids=trim($uidsAry,',');
		if($uids){
			$this->tableList=ContactsFollow::querier('all',$uids,$this->p);//$this->p为一个分页对象
			ContactsFollow::doFollowFilter($this->tableList,'all',$this->ua->id);
			$this->setTable($this->tableList);
			$this->addVar('total',$this->tableList->getRow());
			$this->p->setTotal($this->tableList->getRow());
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
		$this->p->setTotal($this->tableList->getRow());
		$this->addVarPaging();
		//debugx($a);
	}

}
?>