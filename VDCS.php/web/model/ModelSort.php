<?
class ModelSort
{
	private $_isCache=true;
	private $_channel='',$_classid=0;
	private $tableChannel=null;
	private $_id=0;
	
	public function __construct()
	{
	}
	public function __destruct()
	{
		unsetr($this->tableChannel);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setCache($b) { $this->_isCache=$b; }
	
	public function setUse($s){$this->_isuse=$s;}
	public function isUse(){return $this->_isuse;}
	
	public function setChannel($s){$this->_channel=$s;}
	public function getChannel(){return $this->_channel;}
	
	public function setClassid($s){$this->_classid=$s;}
	public function getClassid(){return $this->_classid;}
	
	public function setID($s){$this->_id=$s;}
	public function getID(){return $this->_id;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->_isinit) return;$this->_isinit=true;
		$this->tableChannel=$this->getChannelTable($this->_channel,$this->_classid);
	}
	
	
	public function getTable() { return $this->tableChannel; }
	public function getTableRoot() { return ModelClassExtend::toTable($this->tableChannel); }
	public function getTableSub($id) { if($id<1) $id=-1; return ModelClassExtend::toTable($this->tableChannel,$id); }
	
	public function getValue($strClassid,$strKey) { return utilTableExtend::getItem($this->tableChannel,'classid='.$strClassid,$strKey); }
	public function getName($strClassid) { return $this->getChannelName($this->_channel,$strClassid); }
	
	
	
	/*
	########################################
	########################################
	*/
	public function getChannelTable($strChannel,$classid=0,$cachename=null,$query=null,$order=null,$limit=null)
	{
		if(!$cachename) $cachename=$strChannel.$classid;
		if($this->_isCache){
			$arys=ModelSortExtend::getCacheAry($cachename);
			if(isAry($arys)){
				$reTable=newTable();
				$reTable->setArray($arys);
				return $reTable;
			}
		}
		//$sql=dcs.getConfigTree('common.config/data/config').getItem('model.class.sql')
		if(!$query) $query='channel=\'{$channel}\' and classid={$classid}';
		if(!$order) $order='rootid,orderid';
		if($limit && $limit>0) $limits='limit 0,'.$limit;
		if(len($sql)<1) $sql='select channel,rootid,sortid,classid,name,levelid,fatherid,mode,sort,type,value from {$tablename} where '.$query.' order by '.$order.' '.$limits;
		$sql=rd($sql,'tablename',ModelSortExtend::TableName);
		$sql=rd($sql,'channel',$strChannel);
		$sql=rd($sql,'classid',$classid);
		$reTable=DB::queryTable($sql);
		ModelSortExtend::doTableFilter($reTable,$strChannel);
		//##########
		if($this->_isCache){
			ModelSortExtend::setCache($cachename,$reTable);
		}
		return $reTable;
	}
	
	public function getChannelValue($strChannel,$strClassid,$strKey) { return utilTableExtend::getItem($this->getChannelTable($strChannel),'classid='.$strClassid,$strKey); }
	public function getChannelName($strChannel,$strClassid) { return $this->getChannelValue($strChannel,$strClassid,'name'); }
	public function getChannelAtt($strChannel) { return $this->getTableAtt($this->getClassTable($strChannel),'classid','name','levelid','--'); }
	
	public function doChannelUpdate($channel,$classid,$cname=''){ModelSortExtend::doCacheUpdate($channel,$classid,$cname);}
	
	
	/*
	########################################
	########################################
	*/
	
	
	
	
	
}
?>