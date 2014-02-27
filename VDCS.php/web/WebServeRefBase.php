<?
trait WebServeRefBase
{
	protected $iserve=true;
	public $serve;
	public $serveType		= 'xml';
	
	
	public function initServeX()
	{
		if(inp('j,json',PAGE_X)>0){
			$this->serveType='json';
			initServeJSON();global $serve;$this->serve=&$serve['json'];
			if(queryx('debug')!='show') debugSet(false,false);
		}
		else{
			$this->serve=&initServeXCML();
		}
		$this->serve->putHead();
		$this->initServeBase();
	}
	
	public function initServeBase()
	{
		$this->initControl();
		$this->serveBase();
		if(ISLOCAL){
			if($this->_chn_) $this->addVar('PAGE_CHN',$this->_chn_);
			if($this->_p_) $this->addVar('PAGE_P',$this->_p_);
			if($this->_m_) $this->addVar('PAGE_M',$this->_m_);
			if($this->_mi_) $this->addVar('PAGE_MI',$this->_mi_);
			if($this->_x_) $this->addVar('PAGE_X',$this->_x_);
		}
		if($this->action) $this->addVar('action',$this->action);
	}

	
	/*
	########################################
	########################################
	*/
	public function serveBase()
	{
		$this->_var['_stat.begin']=timer();
	}
	
	public function putStat()
	{
		if($this->_var['_stat.is']){
			$this->addVar('stat.exec.tim',microtime(1)-$this->_var['_stat.begin']);
			$this->addVar('stat.exec',$this->getExec());
		}
	}
	public function putDebug()
	{
		if(query('debug')=='info'){
			debugx('REQUEST URI = '.$_SERVER['REQUEST_URI']);
			debugx('Script Info = '.$_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING']);
			debugx('BROWSER URL = '.DCS::browseURL(true));
			debugx('Processed in '.dcsExecTime().' s, '.DB::getTotal().' queries. Gzip '.dcsGzipStatus().', Memory usage '.dcsMemoryUsage().'.');
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	/*
	protected function ready($ispost=false)
	{
		$this->setStatus('ready');
		if($ispost && !isPost()) return false;
		$this->setStatus('parser');
		return true;
	}
	
	public function parserAction($prefix='parse')
	{
		global $ctl;
		$action=$this->action?$this->action:$ctl->action;
		$this->setStatus('init');
		$funcname=$prefix.ucfirst($action);
		//method_exists,is_callable
		if(!method_exists($this,$funcname)){
			$this->setStatus('noaction');
			return;
		}
		$this->$funcname();
	}
	public function doParse1()
	{
		$this->parserAction('parse');
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	protected function getExec($t=1,$len=4)
	{
		$_time=microtime(1)-$this->_var['_stat.begin'];
		return($t==1) ? number_format($_time,$len) : number_format($_time*1000,$len);
	}
	
	protected function setStat($s){$this->_var['_stat.is']=$s;}
	
	protected function doOutput()
	{
		$this->serve->putData();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function addError($msg,$field=null,$code=null){$GLOBALS['ctl']->e->addItem($msg,$field,$code);}
	protected function isErrorCheck(){return $GLOBALS['ctl']->e->isCheck();}
	protected function isRaiseError($raise=true){return $this->serve->isRaiseError($raise=true);}
	protected function doRaiseError(){return $this->serve->doRaiseError();}
	
	
	/*
	########################################
	########################################
	*/
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
	
	protected function addVarPaging()
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
	
	protected function addItem($otree){$this->serve->addItem($otree);}
	protected function setTable($otable){$this->serve->setTable($otable);}
	protected function setFields($fields){$this->serve->setFields($fields);}
	protected function addTable($name,$otable){$this->serve->addTable($name,$otable);}
	
	protected function testo($obj,$prefix='test')
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
?>