<?
class ChannelShopAuctionAgentX extends ChannelShopAuctionX
{
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		$this->loadRoot(true);if(!$this->isRoot()) return;
		$this->loadData(true);if(!$this->isData()) return;
		
	}
	
	public function doParse()
	{
		if(!$this->isParse()) return;
		switch($this->action){
			case 'set':
				if($this->isUser(true)) $this->doParseSet();
				break;
		}
	}
	
	protected function doParseSet()
	{
		$price_max=queryn('price_max');
		$price_degree=queryn('price_degree');
		$this->addVar('price_max',$price_max);
		$this->addVar('price_degree',$price_degree);
		//debugx($price_max.' , '.$price_degree);
		
		$isAction=true;
		$_status='';
		
		//判断 时效
		if($isAction && !$this->vauction->isTime()){
			$isAction=false;
			if($this->vauction->isTimeOut()){
				$_status='timeout';
			}
			else{
				$_status='notime';
			}
		}
		//判断 开始
		if($isAction && !$this->vauction->isStart()){
			$isAction=false;
			$_status='nostart';
		}
		
		//判断 低价
		if($isAction && $price_max<$this->vauction->price_bid_offer){
			$isAction=false;
			$_status='low';
		}
		
		//判断 是否存在
		if($isAction){
			$treeAgent=$this->vauction->getAgentTree(true,0,$this->ua->id);
			if($treeAgent->getCount()>0){
				$this->addVar('my.price_max',$treeAgent->getItem('price_max'));
				$this->addVar('my.price_degree',$treeAgent->getItem('price_max'));
				$isAction=false;
				$_status='exist';
			}
		}
		
		//保存 代理竞价
		if($isAction){
			$treeAct=newTree();
			$treeAct->addItem('uuid',$this->ua->id);
			$treeAct->addItem('price_max',$price_max);
			$treeAct->addItem('price_degree',$price_degree);
			$this->vauction->doAgentSave($treeAct,0);
			$_status='succeed';
		}
		
		$this->setStatus($_status);
		$this->isBid=false;
		if($isAction && $_status=='succeed'){
			$this->isBid=true;
		}
	}
	
}
?>