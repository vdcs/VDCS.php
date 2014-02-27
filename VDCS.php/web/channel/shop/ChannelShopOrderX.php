<?
class ChannelShopOrderX extends ChannelShopBaseX
{
	
	protected function initPaging()
	{
		$listnum=queryi('listnum');//每页显示的数目
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
	}
	
	public function parseList()
	{
		$this->initPaging();
		$this->tableList=ShopOrder::query('uuid='.$this->ua->id,'id desc',$this->p);
		
		if(!$total) $total=$this->tableList->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();
		$this->setTable($this->tableList);
		$this->setSucceed();
	}
	
	public function parseChange()
	{
		$orderid=queryi('orderid');
		$status=queryi('status');
		$_status=ShopOrder::editStatus($orderid,$status);
		if($_status) $this->setSucceed();
		else $this->setStatus('failed');
	}
}
?>