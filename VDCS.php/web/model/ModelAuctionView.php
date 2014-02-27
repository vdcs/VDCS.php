<?
class ModelAuctionView extends ModelAuction
{
	protected $treeWinner,$treeAgentMax;
	public function __construct()
	{
		parent::__construct();
		$this->treeWinner=newTree();
		$this->treeAgentMax=newTree();
	}
	public function __destruct()
	{
		parent::__destruct();
		unset($this->treeWinner,$this->treeAgentMax);
	}
	
	
	public function doParse()
	{
		$this->loadData();
		//debugTree($this->treeData);
		if(!$this->_isData) return;
		
		$this->doParseData();
		$this->loadWinner();
	}
	public function doParseData()
	{
		//##########
		$price_bid=$this->treeData->getItemNum('price_bid');
		$price_start=$this->treeData->getItemNum('price_start');
		$price_degree=$this->treeData->getItemNum('price_degree');
		$price_bid_offer=$price_bid;
		if($price_bid_offer<$price_start) $price_bid_offer=$price_start;
		$price_bid_offer+=$price_degree;
		$this->price_degree=$price_degree;
		$this->price_bid_offer=$price_bid_offer;
		$this->treeData->addItem('price_bid_offer',$price_bid_offer);
		//##########
		$this->treeData->doBegin();
		for($t=0;$t<$this->treeData->getCount();$t++){
			$this->treeDatas->addItem(self::DataPX.$this->treeData->getItemKey(),$this->treeData->getItemValue());
			$this->treeData->doMove();
		}
		//debugTree($this->treeDatas);
	}
	
	public function setDataBid($datid,$price,$total=-1)
	{
		if(!$datid) $datid=$this->datid;
		if(!$datid){
			return false;
		}
		$treeAct=newTree();
		$treeAct->addItem('price_bid',$price);
		if($total>0){
			$treeAct->addItem('total_bid',$this->treeData->getItemInt('total_bid')+$total);
		}
		$sqlQuery='id='.$datid;
		$sql=DB::sqlUpdate(self::TableName,$treeAct->getFields(),$treeAct,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadWinner()
	{
		if($this->_loadWinner) return;$this->_loadWinner=true;
		$this->treeWinner=$this->getBidWinnerTree(true,$this->datid);
		
	}
	public function isWinner(){return ($this->treeWinner->getCount()>0);}
	public function getWinner($k){return $this->treeWinner->getItem($k);}
	public function doWinnerOff()
	{
		if($this->treeWinner->getCount()<1) return;
		$id=$this->treeWinner->getItemInt('id');
		$this->setBidState($id,0);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getBidWinnerTree($filter=true,$datid=0,$channel='',$rootid=0)
	{
		if(!$datid) $datid=$this->datid;
		if(!$channel) $channel=$this->channel;
		if(!$rootid) $rootid=$this->rootid;
		if($datid>0){
			$sqlQuery='datid='.$datid;
		}
		else{
			$sqlQuery='channel=\''.$channel.'\' and rootid='.$rootid.'';
			$sql=DB::sqlSelect(self::TableName,'','id',$sqlQuery,'',1);
			$sqlQuery='datid=('.$sql.')';
		}
		$sql=DB::sqlSelect(self::BidTableName,'','*',$sqlQuery,'price desc',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		if($filter) $treeRS->doFilter(self::BidTablePX);
		return $treeRS;
	}
	public function doBidOffer($treeAct,$datid=0)
	{
		if(!$datid) $datid=$treeAct->getItemInt('datid');
		if(!$datid) $datid=$this->datid;
		if(!$datid){
			return false;
		}
		//BidTableFieldsAdd	= ',,price,isauto,state,status,tim';
		if(!$treeAct->isItem('datid')) $treeAct->addItem('datid',$datid);
		if(!$treeAct->isItem('uuid')) $treeAct->addItem('uuid',$this->ua->id);
		if(!$treeAct->isItem('price')) $treeAct->addItem('price',0);
		if(!$treeAct->isItem('isauto')) $treeAct->addItem('isauto',0);
		if(!$treeAct->isItem('state')) $treeAct->addItem('state',1);
		if(!$treeAct->isItem('tim')) $treeAct->addItem('tim',DCS::timer());
		$sql=DB::sqlInsert(self::BidTableName,self::BidTableFieldsAdd,$treeAct);
		//debugx($sql);
		DB::exec($sql);
		
		//更新竞拍价
		//$this->setDataPriceBid(0,$treeAct->getItemNum('price'));
	}
	public function setBidState($id,$state=0)
	{
		$treeAct=newTree();
		$treeAct->addItem('state',$state);
		$sqlQuery='id='.$id;
		$sql=DB::sqlUpdate(self::BidTableName,$treeAct->getFields(),$treeAct,$sqlQuery);
		DB::exec($sql);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadAgentMax()
	{
		if($this->_loadAgentMax) return;$this->_loadAgentMax=true;
		$this->treeAgentMax=$this->getAgentMaxTree(true,$this->datid);
		
	}
	public function isAgentMax(){return ($this->treeAgentMax->getCount()>0);}
	public function getAgentMax($k){return $this->treeAgentMax->getItem($k);}
	public function doAgentMaxBid()
	{
		if($this->treeWinner->getCount()<1) return;
		$id=$this->treeWinner->getItemInt('id');
		$this->setBidState($id,0);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getAgentMaxTree($filter=true,$datid=0)
	{
		if(!$datid) $datid=$this->datid;
		$sqlQuery='datid='.$datid;
		$sql=DB::sqlSelect(self::AgentTableName,'','*',$sqlQuery,'price_max desc',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		if($filter) $treeRS->doFilter(self::AgentTablePX);
		return $treeRS;
	}
	public function doAgentSave($treeAct,$datid=0,$uid=0)
	{
		if(!$datid) $datid=$treeAct->getItemInt('datid');
		if(!$datid) $datid=$this->datid;
		if(!$uid) $uid=$treeAct->getItemInt('uuid');
		if(!$uid) $uid=$this->ua->id;
		if(!$datid || !$uid){
			return false;
		}
		//AgentTableFieldsAdd	= 'datid,uuid,price_degree,price_max,price_bid,state,status,tim';
		if(!$treeAct->isItem('datid')) $treeAct->addItem('datid',$datid);
		if(!$treeAct->isItem('uuid')) $treeAct->addItem('uuid',$uid);
		if(!$treeAct->isItem('price_degree')) $treeAct->addItem('price_degree',0);
		if(!$treeAct->isItem('price_max')) $treeAct->addItem('price_max',0);
		if(!$treeAct->isItem('state')) $treeAct->addItem('state',1);
		if(!$treeAct->isItem('tim')) $treeAct->addItem('tim',DCS::timer());
		$sql=DB::sqlInsert(self::AgentTableName,self::AgentTableFieldsAdd,$treeAct);
		//debugx($sql);
		DB::exec($sql);
	}
	public function doAgentUpdate($id,$treeAct)
	{
		if(isTree($treeAct) && $treeAct->getCount()>0){
			$sqlQuery='id='.$id;
			$sql=DB::sqlUpdate(self::AgentTableName,$treeAct->getFields(),$treeAct,$sqlQuery);
			DB::exec($sql);
		}
	}
	public function setAgentPriceBid($id,$bid)
	{
		$treeAct=newTree();
		$treeAct->addItem('price_bid',$bid);
		$this->doAgentUpdate($id,$treeAct);
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCache($re,$vo='')
	{
		if(!$vo) $vo=self::KEY.'.';
		if(right($vo,1)!='.') $vo.='.';
		//####################
		$re=CommonTheme::toCacheFilterTree($re,self::KEY,$vo,'getData');
		$re=CommonTheme::toCacheFilterTree($re,self::KEY.'.winner',$vo,'getWinner');
		//####################
		return $re;
	}
}
?>