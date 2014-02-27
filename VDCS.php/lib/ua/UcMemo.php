<?
class UcMemo
{
	const KEY			= 'memo';
	const TableName			= 'dbu_memo';
	const TablePX			= '';
	
	protected $treeData,$treeDatas;
	public function __construct()
	{
		$this->_is=true;
		
		$this->_isu=true;
		$this->urc=0;
		$this->uid=0;
		
		$this->_ischn=true;
		$this->channel='';
		$this->action='';
		$this->rootid=0;
		$this->dataid=0;
		
		$this->_isData=false;
		$this->keyid=0;
		
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
	public function is(){return $this->_is&&$this->_ischn;}
	
	
	public function setChannel($s){$this->channel=$s;}
	public function setAction($s){$this->action=$s;}
	public function setRootid($s){$this->rootid=$s;}
	public function setDataid($s){$this->dataid=$s;}
	
	public function setURC($s){$this->urc=$s;}
	public function setUID($id_){$this->uid=$id_;}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		global $cfg;
		//$this->mode=$cfg->cfg(self::KEY);$this->_is=len($this->mode)>0?true:false;
		$this->_isu=len($this->urc)>0?true:false;
		$this->_ischn=len($this->channel)>0?true:false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isData(){return $this->_isData;}
	public function loadData()
	{
		if(!$this->_is || $this->_isData) return;
		if(!$this->channel || !$this->rootid) return;
		if($this->_loadData) return;$this->_loadData=true;
		$sqlQuery='channel=\''.$this->channel.'\' and rootid='.$this->rootid.'';
		//uurc=\''.$this->urc.'\' and uuid='.$this->uid.' and 
		if($this->action) $sqlQuery=DB::sqla($sqlQuery,'action=\''.$this->action.'\'');
		if($this->dataid>0) $sqlQuery=DB::sqla($sqlQuery,'dataid='.$this->dataid.'');
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
	public function getDataTree($key)
	{
		$reTree=newTree();
		$reTree->setArray($this->treeData->getArray());
		return $reTree;
	}
	public function getData($k){return $this->treeData->getItem($k);}
	
	public function getDatas($k){return $this->treeDatas->getItem($k);}
	
}
?>