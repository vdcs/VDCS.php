<?
class ChannelShopAuctionBidX extends ChannelShopAuctionX
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
		$this->islist=true;
		switch($this->action){
			case 'offer':
				if($this->isUser(true)) $this->doParseOffer();
				break;
			case 'list':
				$this->islist=false;
				$this->doParseList();
				break;
		}
		
		if($this->islist){
			$this->doParseList();
		}
	}
	
	
	protected function doParseList()
	{
		$this->setTable($this->vauction->getBidTable(true,true));
	}
	
	protected function doParseOffer()
	{
		$price=queryn('price');
		$this->addVar('price',$price);
		//debugx($price);
		
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
		if($isAction && $price<$this->vauction->price_bid_offer){
			$isAction=false;
			$_status='low';
		}
		
		//判断 优胜者
		if($isAction && $this->vauction->isWinner()){
			if($this->ua->id==toInt($this->vauction->getWinner('uuid'))){
				$isAction=false;
				$_status='winner';
			}
		}
		
		$istest=false;
		$total_bid=0;
		//保存 竞拍记录
		if($isAction){
			if($istest) debugx($this->vauction->price_bid_offer);
			//########## 代理竞价 列表
			$tableAgent=$this->vauction->getAgentTable(true,0,$this->vauction->price_bid_offer);
			$tableAgent->doItemBegin();
			$treeMax=$tableAgent->getItemTree();
			$price_max=$treeMax->getItemNum('price_max');
			$uid_max=$treeMax->getItemInt('uuid');
			//##########
			$treeAct=newTree();
			$treeAct->addItem('uuid',$this->ua->id);
			$treeAct->addItem('price',$price);
			if($price_max>$price && $uid_max>0){
				$treeAct->addItem('state',0);
			}
			if(!$istest){
				$this->vauction->doBidOffer($treeAct,0);
				$this->vauction->doWinnerOff();
				$total_bid++;
			}
			//########## 代理竞价 自动出价
			$tableAgent->doItemEnd();
			for($t=0;$t<$tableAgent->getRow()-1;$t++){
				$treeAgent=$tableAgent->getItemTree();
				$uid=$treeAgent->getItemInt('uuid');
				if($uid>0){
					$price_max=$treeAgent->getItemNum('price_max');
					if($price<$price_max) $price=$price_max;
					if($istest) debugx($uid.'= '.$price);
					$treeAct=newTree();
					$treeAct->addItem('uuid',$uid);
					$treeAct->addItem('price',$price);
					$treeAct->addItem('isauto',1);
					$treeAct->addItem('state',0);
					if(!$istest){
						$this->vauction->doBidOffer($treeAct,0);
						$this->vauction->setAgentPriceBid($treeAgent->getItemInt('id'),$price);
						$total_bid++;
					}
				}
				$tableAgent->doMove(-1);
			}
			//##### 代理竞价 最高价
			if($uid_max>0){
				$uid=$uid_max;
				$price_max=$treeMax->getItemNum('price_max');
				$price_degree=$treeMax->getItemNum('price_degree');
				if($price_degree<=0) $price_degree=$this->vauction->price_degree;
				$price+=$price_degree;
				if($price>$price_max) $price=$price_max;
				if($istest) debugx($uid.'== '.$price);
				$treeAct=newTree();
				$treeAct->addItem('uuid',$uid);
				$treeAct->addItem('price',$price);
				$treeAct->addItem('isauto',1);
				if(!$istest){
					$this->vauction->doBidOffer($treeAct,0);
					$this->vauction->setAgentPriceBid($treeMax->getItemInt('id'),$price);
					$total_bid++;
				}
			}
			//##########
			$_status='succeed';
		}
		
		$this->setStatus($_status);
		$this->isBid=false;
		if($isAction && $_status=='succeed'){
			$this->isBid=true;
			$this->addVar('price_bid',$price);
			
			//########## 更新竞拍价
			if(!$istest){
				$this->vauction->setDataBid(0,$price,$total_bid);
			}
		}
	}
	
}
?>