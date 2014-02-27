<?
class UcAuth
{
	const KEY			= 'auth';
	const TableName			= 'dbu_auth';
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
		$this->sorts='';
		$this->types='';
		
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
	public function is(){return $this->_is&&$this->_ischn&&$this->_isu;}
	
	
	public function setChannel($s){$this->channel=$s;}
	public function setAction($s){$this->action=$s;}
	public function setSorts($s){$this->sorts=$s;}
	public function setTypes($s){$this->types=$s;}
	
	public function setURC($s){$this->urc=$s;}
	public function setUID($id_){$this->uid=$id_;}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		//$this->mode=$cfg->cfg(self::KEY);$this->_is=len($this->mode)>0?true:false;
		$this->_ischn=(!!$this->channel);
		$this->_isu=($this->urc && $this->uid>0)?true:false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isData(){return $this->_isData;}
	public function loadData()
	{
		if(!$this->_is || $this->_isData) return;
		if(!$this->_ischn || !$this->_isu) return;
		if($this->_loadData) return;$this->_loadData=true;
		$sqlQuery='channel='.DB::q($this->channel,1).' and uurc='.DB::q($this->urc,1).' and uuid='.$this->uid.'';
		if($this->sorts) $sqlQuery=DB::sqlAppend($sqlQuery,'sorts='.DB::q($this->sorts,1).'');
		if($this->types) $sqlQuery=DB::sqlAppend($sqlQuery,'types='.DB::q($this->types,1).'');
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$this->treeData=DB::queryTree($sql);
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
	public function getDataInt($k){return $this->treeData->getItemInt($k);}
	public function setData($k,$v){return $this->treeData->addItem($k,$v);}
	
	public function getDatas($k){return $this->treeDatas->getItem($k);}
	
	public function getAuthStatus()
	{
		$re='un';
		$_status=$this->treeData->getItemInt('trans_status');
		switch($_status){
			case '1':	$re='yes';break;
			case '2':	$re='not';break;
		}
		return $re;
	}
	
}
?>