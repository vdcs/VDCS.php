<?
class WebServeJSON
{
	//use WebServeBaseRef;
	public $oput=array();
	public $tableData=null;
	protected $isHeader=false;
	
	const HeaderType	= 'json';
	
	
	public function __construct()
	{
		//parent::__construct();
		$this->treeVar=newTree();
		$this->tableData=newTable();
	}
	public function __destruct()
	{
		//parent::__destruct();
		unset($this->treeVar,$this->tableData);
	}
	
	
	public function getStatus($k='')
	{
		if(!$k) $k='status';
		return $this->oput[$k];
	}
	public function setStatus($v,$k='')
	{
		if(!$k) $k='status';
		$this->addVar($k,$v);
	}
	
	public function getVar($k){return $this->oput[$k];}
	public function addVar($k,$v)
	{
		$this->oput[$k]=$v;
	}
	public function addVarTree($otree,$px='')
	{
		if(isTree($otree)){
			$otree->doBegin();
			for($t=0;$t<$otree->getCount();$t++){
				$this->addVar($px.$otree->getItemKey(),$otree->getItemValue());
				$otree->doMove();
			}
		}
	}
	public function addTest($k,$v=null)
	{
		if($v===null){
			if(!$this->testc) $this->testc=1;
			$v=$k;
			$k=$this->testc;
			$this->testc++;
		}
		$this->addVar('_test.'.$k,$v);
	}
	
	public function addItem($otree)
	{
		$this->tableData->addItem($otree);
	}
	public function setTable($otable)
	{
		$this->tableData->setArray($otable->getArray());
	}
	public function setFields($s){$this->_tableFields=$s;}
	
	public function addTable($name,$otable)
	{
		if(!$this->maps) $this->maps=newMap();
		$this->maps->addItem($name,$otable,'table');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isError($raise=true)
	{
		global $ctl;
		$re=$ctl->e->isCheck();
		if($re && $raise) $this->doRaiseError();
		return $re;
	}
	public function isRaiseError(){return $this->isError(true);}
	public function doRaiseError()
	{
		global $ctl;
		$re=$ctl->e->isCheck();
		if($re){
			$ctl->doRaiseError();
			//$this->addVar('message.script',$ctl->treeDTML->getItem('_message'));
			//$this->addVar('message.string',$ctl->treeDTML->getItem('_message.string'));
			$this->addVar('error_message',$ctl->treeDTML->getItem('_message.string'));
			$this->addVar('error_codes',$ctl->e->getCodes());
			$this->setStatus('error');
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function output($strs)
	{
		if(!$this->isHeader){
			pageHeader(self::HeaderType);
			$this->isHeader=true;
		}
		echo $strs;
	}
	public function put($strs)
	{
		if($this->isput) return;$this->isput=true;
		$this->output($strs);
	}
	public function putHead()
	{
		if(!$this->isHeader){
			pageHeader(self::HeaderType);
			$this->isHeader=true;
		}
	}
	public function putData()
	{
		$this->putJSON();
	}
	
	public function getOput()
	{
		if($this->tableData->getRow()>0) $this->oput['_table_item_']=$this->tableData->getArray();
		return $this->oput;
	}
	
	public function putJSON($oput=null)
	{
		if(DEBUG_OUT){
			debuga($this->getOput());
		}
		$this->put(json_encode($oput?$oput:$this->getOput()));
	}
	
}
?>