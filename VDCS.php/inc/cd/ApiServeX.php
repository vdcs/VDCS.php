<?
class ApiServeX extends utilErrorBase
{
	public $serve;
	public $serveType		= 'xml';
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function serveInit()
	{
		define('DEBUG_TYPE',1);
		if(inp('j,json,xj',PAGE_X)>0){
			if(DEBUGV!='show') debugSet(false,false);
			require_once(VDCS_CD_PATH.'WebServeJSON.php');
			$this->serve=new WebServeJSON();
		}
		else{
			require_once(VDCS_CD_PATH.'WebServeXCML.php');
			$this->serve=new WebServeXCML();
		}
		$this->serve->putHead();
		$this->_var['_stat.begin']=timer();
	}
	public function serveParse()
	{
		$this->putStat();
		$this->doOutput();
		$this->putDebug();
	}
	
	public function putStat()
	{
		if($this->_var['_stat.is']){
			//$this->serve->addVar('stat.exec.tim',microtime(1)-$this->_var['_stat.begin']);
			$this->serve->addVar('stat.exec',$this->getExec());
		}
	}
	public function putDebug()
	{
		if(query('debug')){
			debugx('REQUEST URI = '.$_SERVER['REQUEST_URI']);
			debugx('Script Info = '.$_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING']);
			debugx('BROWSER URL = '.DCS::browseURL(true));
			debugx('Processed in '.dcsExecTime().' s, '.DB::getTotal().' queries. Gzip '.dcsGzipStatus().', Memory usage '.dcsMemoryUsage().'.');
		}
	}
	
	protected function doOutput()
	{
		$this->serve->putData();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function getExec($t=1,$len=4)
	{
		$_time=microtime(1)-$this->_var['_stat.begin'];
		return($t==1) ? number_format($_time,$len) : number_format($_time*1000,$len);
	}
	
	protected function isRaiseError($raise=true){return $this->serve->isRaiseError($raise=true);}
	protected function doRaiseError(){return $this->serve->doRaiseError();}
	
	
	/*
	########################################
	########################################
	*/
	public function setStat($s){$this->_var['_stat.is']=$s;}
	
	public function getStatus($k='status'){return $this->serve->getStatus($k);}
	public function setStatus($v,$k='status'){$this->serve->setStatus($v,$k);}
	public function getMessage($k='message'){return $this->serve->getMessage($k);}
	public function setMessage($v,$k='message'){$this->serve->setMessage($v,$k);}
	public function setMessages($tit,$msg,$url)
	{
		if($tit) $this->addVar('title',$tit);
		$this->setMessage($msg);
		if($url) $this->addVar('backurl',$url);
	}
	
	public function getVar($k){return $this->serve->getVar($k);}
	public function addVarTree($otree,$px='')
	{
		if(is_array($otree)){
			$ary=$otree;
			$otree=newTree();
			$otree->setArray($ary);
		}
		$this->serve->addVarTree($otree,$px);
	}
	public function addVar($k,$v)
	{
		$this->serve->addVar($k,$v);
		if($this->treeVar) $this->treeVar->addItem($k,$v);
	/*
		global $ctl;
		if($ctl && $ctl->treeVar) $ctl->treeVar->addItem($k,$v);
	*/
	}
	public function addTest($k,$v=null){$this->serve->addTest($k,$v);}
	
	public function addVarPaging()
	{
		if($this->p){
			$this->addVar('paging.listnum',$this->p->getListNum());
			$this->addVar('paging.numend',$this->p->getNumEnd());
			$this->addVar('paging.total',$this->p->getTotal());
			$this->addVar('paging.page',$this->p->getPage());
			$this->addVar('paging.pagenum',$this->p->getPageNum());
			$this->addVar('paging.pagetotal',$this->p->getPageTotal());
			$this->addVar('paging.pagebase',$this->p->getPageBase());
		}
	}
	
	public function addItem($otree){$this->serve->addItem($otree);}
	public function setTable($otable){$this->serve->setTable($otable);}
	public function setFields($fields){$this->serve->setFields($fields);}
	public function addTable($name,$otable){$this->serve->addTable($name,$otable);}
	
	public function testo($obj,$prefix='test')
	{
		if(isa($obj)){
			$obj2=$obj;
			$obj=newTree();
			$obj->setArray($obj2);
		}
		if(isTree($obj)){
			$obj->doBegin();
			for($t=1;$t<=$obj->getCount();$t++){
				$this->addVar($prefix.'-'.$obj->getItemKey(),$obj->getItemValue());
				$obj->doMove();
			}
		}
	}
	
}
