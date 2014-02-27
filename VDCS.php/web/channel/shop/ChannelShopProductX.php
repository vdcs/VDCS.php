<?php
class ChannelShopProductX extends ChannelShopBaseX{
	public function parseList()
	{
		$this->initPaging();//初始化分页
		$this->tableList=ShopProduct::queryData('p_status=1','p_id desc',$this->p);
		$this->setTable($this->tableList);
		if(!$total) $total=$this->tableList->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();
		$this->setStatus('succeed');
	}
	
	protected function initPaging()
	{
		$listnum=queryi('listnum');//每页显示的数目
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
	}
	
	public function parseTest()
	{
		$tree=DB::queryTree('select * from db_shop_orderi');
		debugx($tree->getFields());
	}
}
?>