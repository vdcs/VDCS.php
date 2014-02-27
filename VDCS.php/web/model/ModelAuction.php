<?
class ModelAuction
{
	const KEY			= 'auction';
	const TableName			= 'dbd_auction';
	const TablePX			= '';
	
	const DataPX			= 'auction.';
	const BidTableName		= 'dbd_auction_bid';
	const BidTablePX		= '';
	const BidTableFieldsAdd		= 'datid,uuid,price,isauto,state,status,tim';
	const AgentTableName		= 'dbd_auction_agent';
	const AgentTablePX		= '';
	const AgentTableFieldsAdd	= 'datid,uuid,price_degree,price_max,price_bid,state,status,tim';
	
	const RelateField		= 'sp_auction';
	const RelateOn			= 'on';
	
	protected $ua;
	protected $treeData,$treeDatas;
	public function __construct()
	{
		$this->ua=&$GLOBALS['ua'];
		
		$this->_is=false;
		$this->_isRoot=true;
		$this->_isData=false;
		$this->_isStart=false;
		$this->_isOver=false;
		$this->_isTime=false;
		$this->_isTimeOut=false;
		$this->datid=0;
		$this->rootid=0;
		
		$this->treeData=newTree();
		$this->treeDatas=newTree();
	}
	public function __destruct()
	{
		unsetr($this->treeData,$this->treeDatas);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function is(){return $this->_is&&$this->_isRoot;}
	
	public function setChannel($channel_){$this->channel=$channel_;}
	
	public function setRootID($id_){$this->rootid=$id_;}
	public function setRootMode($s)
	{
		$this->RootMode=$s;
		$this->_isRoot=($this->RootMode==self::RelateOn);
	}
	
	public function getRelateField(){return self::RelateField;}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		global $cfg;
		$this->mode=$cfg->cfg(self::KEY);$this->_is=len($this->mode)>0?true:false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isData(){return $this->_isData;}
	public function isStart(){return $this->_isStart;}
	public function isOver(){return $this->_isOver;}
	public function isTime(){return $this->_isTime;}
	public function isTimeOut(){return $this->_isTimeOut;}
	public function loadData()
	{
		if(!$this->_is || $this->_isData) return;
		if(!$this->channel || !$this->rootid) return;
		if($this->_loadData) return;$this->_loadData=true;
		$sqlQuery='channel=\''.$this->channel.'\' and rootid='.$this->rootid.'';
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$this->treeData=DB::queryTree($sql);
		//debugx($sql);
		//debugTree($this->treeData);
		$this->sqlKey=$sqlQuery;
		if($this->treeData->getCount()>0){
			$this->_isData=true;
			$this->datid=$this->treeData->getItemInt('id');
			switch($this->treeData->getItemInt('state')){
				case '1':
					$this->state='over';
					$this->_isOver=true;
					break;
				case '2':
					$this->state='start';
					$this->_isStart=true;
					break;
				case '0':
				default:
					$this->state='un';
					break;
			}
			$nowtim=DCS::timer();
			if($nowtim>=$this->treeData->getItemInt('tim_begin') && $nowtim<=$this->treeData->getItemInt('tim_end')) $this->_isTime=true;
			if($nowtim>$this->treeData->getItemInt('tim_end')) $this->_isTimeOut=true;
			/*
			debugx(VDCSTIME::toConvert($this->treeData->getItemInt('tim_begin'),1));
			debugx($this->treeData->getItemInt('tim_begin'));
			debugx($this->treeData->getItemInt('tim_end'));
			debugx($nowtim);
			*/
			//debugx('datid='.$this->datid);
		}
	}
	public function getDataTree($key)
	{
		$reTree=newTree();
		$reTree->setArray($this->treeData->getArray());
		return $reTree;
	}
	public function getData($k){return $this->treeData->getItem($k);}
	
	public function getDatas($k){return $this->treeDatas->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function getBidTable($filter=true,$extend=false,$datid=0,$channel='',$rootid=0)
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
		$sql=DB::sqlSelect(self::BidTableName,'','*',$sqlQuery,'tim desc,price desc');
		//debugx($sql);
		$tableRS=DB::queryTable($sql);
		if($filter) $tableRS->doFilter(self::BidTablePX);
		if($extend){
			UaExtend::appendInfo($tableRS);
			$tableRS->doAppendFields('_sn,_date,_time,isauto.name,state.name');
			$row=$tableRS->getRow();
			$tableRS->doBegin();
			while($tableRS->isNext()){
				$_sn=$row-$tableRS->getI()+1;
				$tableRS->setItemValue('_sn',$_sn);
				$tim=$tableRS->getItemValueInt('tim');
				$tableRS->setItemValue('_date',VDCSTIME::toConvert($tim,4));
				$tableRS->setItemValue('_time',VDCSTIME::toConvert($tim,1));
			}
		}
		return $tableRS;
	}
	public function getAgentTree($filter=true,$datid=0,$uid=0)
	{
		if(!$datid) $datid=$this->datid;
		if(!$uid) $uid=$this->ua->id;
		$sqlQuery='datid='.$datid.' and uuid='.$uid;
		$sql=DB::sqlSelect(self::AgentTableName,'','*',$sqlQuery,'price_max desc',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		if($filter) $treeRS->doFilter(self::AgentTablePX);
		return $treeRS;
	}
	public function getAgentTable($filter=true,$datid=0,$price=0)
	{
		if(!$datid) $datid=$this->datid;
		$sqlQuery='datid='.$datid;
		if($price>0) $sqlQuery=DB::toSQLAppend($sqlQuery,'price_max>'.$price);
		$sql=DB::sqlSelect(self::AgentTableName,'','*',$sqlQuery,'price_max desc');
		//debugx($sql);
		$tableRS=DB::queryTable($sql);
		if($filter) $tableRS->doFilter(self::AgentTablePX);
		return $tableRS;
	}
}
?>