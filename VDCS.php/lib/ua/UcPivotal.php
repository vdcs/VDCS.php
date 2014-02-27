<?
class UcPivotal
{
	const KEY			= 'pivotal';
	const TableName			= 'dbu_pivotal';
	const TablePX			= '';
	
	protected $treeData,$treeDatas;
	public function __construct()
	{
		$this->_is=true;
		
		$this->_isUrc=true;
		$this->urc=0;
		$this->uid=0;
		
		$this->_isData=false;
		$this->keyid=0;
		
		$this->treeData=newTree();
		$this->treeDatas=newTree();
	}
	public function __destruct()
	{
		unset($this->treeData,$this->treeDatas);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function is(){return $this->_is&&$this->_isUrc;}
	
	public function setURC($rc){$this->urc=$rc;}
	public function setUID($id){$this->uid=$id;}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		global $cfg;
		//$this->mode=$cfg->cfg(self::KEY);$this->_is=len($this->mode)>0?true:false;
		$this->_isUrc=len($this->urc)>0?true:false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isData(){return $this->_isData;}
	public function loadData()
	{
		if(!$this->_is || $this->_isData) return;
		if(!$this->urc || !$this->uid) return;
		if($this->_loadData) return;$this->_loadData=true;
		$sqlQuery='uurc=\''.$this->urc.'\' and uuid='.DB::q($this->uid,1).'';
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$this->treeData=DB::queryTree($sql);
		//debugx($sql);
		//debugTree($this->treeData);
		$this->sqlKey=$sqlQuery;
		if($this->treeData->getCount()>0){
			$this->_isData=true;
			$this->keyid=$this->treeData->getItemInt('id');
			//debugx('keyid='.$this->keyid);
		}
	}
	public function getDataTree()
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
	public function toTerm($term=0)
	{
		$re=$term;
		if(is_int($term)){
			$id=($term==0)?$this->uid:$id;
			$re='uurc=\''.$this->urc.'\' and uuid='.$id.'';
		}
		return $re;
	}
	
	public function set($fields,$values,$term=0)
	{
		$fields=r($fields,'{tpx}',self::TablePX);
		$sql=DB::sqlUpdate(self::TableName,$fields,$values,$this->toTerm($term));
		//dcsLog('ua.set',$sql);
		$isexec=DB::exec($sql);
		return $isexec;
	}
	public function sets($sets,$term=0)
	{
		$term=$this->toTerm($term);
		if($term){
			$sets=r($sets,'{tpx}',self::TablePX);
			$sql='update '.self::TableName.' set '.$sets.' where '.$term;
			//dcsLog('sql',$sql);
			$isexec=DB::exec($sql);
		}
		return $isexec;
	}
	
}
?>