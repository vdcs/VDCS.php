<?
class ChannelTMlogLikeX extends ChannelTBaseX
{
	
	public function parselist(){
		$this->initPaging();
		$rootid=queryi('rootid');
		$this->tableList=TLike::querier($rootid,$this->p);
		
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
	}
	
	protected function initPaging(){
		$listnum=queryi('listnum');
		if($listnum<3 || $listnum>20) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);
		$this->p->setPage(queryi('page'));
	}
	
}
?>