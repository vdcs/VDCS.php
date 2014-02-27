<?
class ModelAuctionManage extends ModelAuction
{
	const DataPX			= 'auction_';
	const TableFieldsAdd		= 'channel,rootid,price_start,price_degree,price_max,price_bid,price_strike,tim_begin,tim_end,tim_finish,total_bid,state,status,tim';
	const TableFieldsEdit		= 'price_start,price_degree,price_max,price_bid,price_strike,tim_begin,tim_end,tim_finish,total_bid,state,status,tim';
	
	
	
	/*
	########################################
	########################################
	*/
	public function getFormDataTree()
	{
		$reTree=newTree();
		$this->loadData();
		if($this->_isData){
			$this->treeData->doBegin();
			for($t=0;$t<$this->treeData->getCount();$t++){
				$reTree->addItem(self::DataPX.$this->treeData->getItemKey(),$this->treeData->getItemValue());
				$this->treeData->doMove();
			}
		}
		return $reTree;
	}
	public function doFormSave(&$tData)
	{
		//debugTree($treeData);
		if(!$this->_isData){
			$this->treeData=newTree();
			$this->treeData->addItem('channel',$this->channel);
			$this->treeData->addItem('rootid',$this->rootid);
			$this->treeData->addItem('price_start',0);
			$this->treeData->addItem('price_degree',0);
			$this->treeData->addItem('price_min',0);
			$this->treeData->addItem('price_max',0);
			$this->treeData->addItem('price_bid',0);
			$this->treeData->addItem('price_strike',0);
			$this->treeData->addItem('tim_begin',0);
			$this->treeData->addItem('tim_end',0);
			$this->treeData->addItem('tim_finish',0);
			$this->treeData->addItem('state',0);
			$this->treeData->addItem('status',1);
			$this->treeData->addItem('tim',DCS::timer());
		}
		$lenPX=len(self::DataPX);
		$tData->doBegin();
		for($t=0;$t<$tData->getCount();$t++){
			$key=$tData->getItemKey();
			if(left($key,$lenPX)==self::DataPX){
				$this->treeData->addItem(toSubstr($key,$lenPX+1),$tData->getItemValue());
			}
			$tData->doMove();
		}
		//debugTree($this->treeData);
		$this->doDataSave($this->treeData);
	}
	
	public function doDataSave($tData)
	{
		if($this->_isData){
			$sql=DB::sqlUpdate(self::TableName,self::TableFieldsEdit,$tData,$this->sqlKey);
		}
		else{
			$sql=DB::sqlInsert(self::TableName,self::TableFieldsAdd,$tData);
		}
		//debugx($sql);
		DB::exec($sql);
	}
	public function doDataRemove($ids)
	{
		$sqlQuery='channel=\''.$this->channel.'\' and rootid in ('.$ids.')';
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
}
?>