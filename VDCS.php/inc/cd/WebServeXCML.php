<?
class WebServeXCML
{
	const HeaderType	= 'xml';
	
	public $treeVar=null,$tableData=null,$maps=null;
	protected $isHeader=false;
	
	
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
	
	
	protected function setStat($s){$this->_var['_stat.is']=$s;}
	
	public function getStatus($k='status'){return $this->getVar($k);}
	public function setStatus($v,$k='status'){$this->addVar($k,$v);}
	public function getMessage($k='message'){return $this->getVar($k);}
	public function setMessage($v,$k='message'){$this->addVar($k,$v);}
	
	public function getVar($k){return $this->treeVar->getItem($k);}
	public function addVar($k,$v){$this->treeVar->addItem($k,$v);}
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
	
	
	public function addItem($otree){$this->tableData->addItem($otree);}
	public function setTable($otable){$this->tableData->setArray($otable->getArray());}
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
	public function isRaiseError($raise=true)
	{
		global $ctl;
		$re=$ctl->e->isCheck();
		if($re && $raise) $this->doRaiseError();
		return $re;
	}
	public function doRaiseError()
	{
		global $ctl;
		$re=$ctl->e->isCheck();
		if($re){
			$ctl->doRaiseError();
			//$this->addVar('message.script',$ctl->treeDTML->getItem('_message'));
			//$this->addVar('message.string',$ctl->treeDTML->getItem('_message.string'));
			$this->addVar('error_message',$ctl->treeDTML->getItem('_message.string'));
			$this->addVar('error_datas',$ctl->e->getDatas());
			$this->setStatus('error');
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function put($strs)
	{
		if($this->isput) return;$this->isput=true;
		$this->output($strs);
	}
	
	
	
	
	/*
	########################################
	########################################
	*/
	public function output($strs)
	{
		$this->putHead();
		if($this->ishead){
			$strs=r($strs,VDCSXCML::XMLHeader(),'');
		}
		echo $strs;
	}
	public function putHead($head=true)
	{
		if(!$this->isHeader){
			pageHeader(self::HeaderType);
			$this->isHeader=true;
		}
		if(!$this->ishead && $head){
			echo VDCSXCML::XMLHeader().NEWLINE;
			$this->ishead=true;
		}
	}
	public function putData()
	{
		$this->putMaps();
	}
	public function putDefault()
	{
		$this->put(VDCSXCML::getXMLDefault());
	}
	public function putMaps()
	{
		if($this->maps){
			$this->maps->addItem('var',$this->treeVar,'tree');
			$this->maps->addItem('item',$this->tableData,'table');
			$this->put(VDCSXCML::toMapsXML($this->maps));
		}
		else if($this->treeVar->getCount()>0 || $this->tableData->getRow()>0){
			$this->maps=newMap();
			$this->maps->addItem('var',$this->treeVar,'tree');
			$this->maps->addItem('item',$this->tableData,'table');
			$this->put(VDCSXCML::toMapsXML($this->maps));
			//$this->put(VDCSXCML::toMapXML($this->treeVar,$this->tableData,'',$this->_tableFields));
		}
		else{
			$this->put(VDCSXCML::getXMLDefault());
		}
	}
	public function putMap($otree,$otable)
	{
		$this->put(VDCSXCML::toMapXML($otree,$otable));
	}
	
}
