<?
class ModelAttr
{
	const KEY		= 'attr';
	const TableName		= 'dbd_attr';
	const TablePX		= '';
	const RelateField	= 'attrtype';
	const RelateOn		= 'on';
	public $tableData,$tableDataVar,$tableDataSel;
	
	public function __construct()
	{
	}
	public function __destruct()
	{
		unsetr($this->tableData,$this->tableDataVar,$this->tableDataSel);
	}
	
	
	public function setChannel($channel_){$this->channel=$channel_;}
	public function is(){return $this->_is;}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		global $cfg;
		$this->loadType();
		$this->mode=$cfg->cfg(self::KEY);$this->_is=len($this->mode)>0?true:false;
		$this->modetype=$cfg->cfg(self::KEY.'.type');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isType(){return len($this->type)>0?true:false;}
	public function setType($type_){$this->type=$type_;}
	public function getType($t=1){return $t==0?$this->modetype:$this->type;}
	public function loadType()
	{
		if(!$this->tableType) $this->tableType=VDCSDTML::getConfigTable('common.channel/'.$this->channel.'/attr/attrtype');
	}
	public function getTypeTable()
	{
		$this->loadType();
		return $this->tableType;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setRootID($id_){$this->rootid=$id_;}
	public function loadData()
	{
		if(!$this->_is || $this->isData) return;
		if(!$this->channel || !$this->rootid) return;
		$sqlQuery='channel=\''.$this->channel.'\' and rootid='.$this->rootid.'';
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'');
		$this->tableData=DB::queryTable($sql);
		//debugTable($this->tableData);
		$this->sqlKey=$sqlQuery;
		$this->isData=true;
	}
	public function getDataTree($key)
	{
		$reTree=newTree();
		$this->loadData();
		$this->tableData->doBegin();
		while($this->tableData->isNext()){
			if($this->tableData->getItemValue('key')==$key){
				$reTree=$this->tableData->getItemTree();
				break;
			}
		}
		return $reTree;
	}
	public function getData($k){return $this->treeData->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function loadStruct()
	{
		if(!$this->tableStruct){
			$this->tableStruct=VDCSDTML::getConfigTable('common.channel/'.$this->channel.'/attr/attr.'.$this->type);
			self::doStructClean($this->type,$this->tableStruct);
		}
	}
	public function getStructTable()
	{
		$this->loadStruct();
		return $this->tableStruct;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function doStructClean($type,&$tableStruct)
	{
		$tableStruct->doAppendFields('keys');
		$tableStruct->doBegin();
		while($tableStruct->isNext()){
			$key=$tableStruct->getItemValue('key');
			if(len($key)<1){
				$key=$tableStruct->getItemValue('name');
				$tableStruct->setItemValue('key',$key);
			}
			$tableStruct->setItemValue('keys',$type.'__'.$key);
			if(len($tableStruct->getItemValue('type'))<1) $tableStruct->setItemValue('type','only');
			if(len($tableStruct->getItemValue('inputtype'))<1) $tableStruct->setItemValue('inputtype','text');
			
		}
	}
	
}
?>