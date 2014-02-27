<?
trait ChannelRefList
{
	use ChannelRefListx;
	
	public $tableClass,$tableClassRoot,$tableClassSub;
	public $classid,$specialid,$year,$mode;
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadClass()
	{
		global $cfg;
		$cfg->doClassInit();
		$this->classid=queryi('classid');
		$cfg->clas->setID($this->classid);
		$this->theme->setWeb('classid',$this->classid);
		$this->theme->setWeb('class.name',$cfg->clas->getName($this->classid));
		$this->theme->setWeb('class.url',$cfg->clas->getValue($this->classid,'linkurl'));
		$this->tableClass=$cfg->clas->getTable();
		$this->tableClassRoot=ModelClassExtend::toTable($this->tableClass);
		if($this->classid>0){
			$cfg->setTitle('');
			$cfg->setTitle('class',$cfg->clas->getName($this->classid),$cfg->clas->getValue($this->classid,'linkurl'));
			$this->_var['classids']=$cfg->clas->getIDS($this->classid);
			if(len($this->_var['classids'])>0){
				$this->treeClassFather=ModelClassExtend::toTree($cfg->clas->getTable(),$cfg->clas->getValue($this->classid,'fatherid'));
				if($this->treeClassFather->getCount()>0){
					$cfg->setTitle('portal',$this->treeClassFather->getItem('name'),$this->treeClassFather->getItem('curl'));
				}
			}
			$this->tableClassSub=ModelClassExtend::toTable($this->tableClass,$this->classid);
			//debugTable($this->tableClassSub);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseClass()
	{
		
	}
	
	public function doParseList()
	{
		global $cfg;
		if(!$this->portalPage) $this->portalPage=$this->_p_;
		if(inp('list,class',$this->portalPage)>0){
			$this->portalPage='list';
			$this->_var['url']=$cfg->getConfigValue('url.'.$this->portalPage.'.page');
			//debugx($this->_var['url']);
			$this->_var['url']=rd($this->_var['url'],'classid',$this->classid);
		}
		else{
			$this->portalPage='search';
			$this->_var['url']=$cfg->getConfigValue('url.'.$this->portalPage);
			$this->_var['url']=urlLink($this->_var['url'],'classid='.$this->classid);
		}
		//debugx($this->_var['url']);
		$this->setQuery($cfg->chn->getSQLStruct('list.query'),$this->_var['url']);
		if($this->_var['classids']){
			$this->queryAppend('classid in ('.$this->_var['classids'].')');
		}
		$this->doParseListQuery();
		
		if(isn($this->issearch) && $this->portalPage=='search') $this->issearch=true;
		if($this->issearch){
			$this->doParseSearch();
		}
		
		$this->initPaging();
		$this->p->setConfig('url',$this->getQuery('url'));
		$this->p->setDB('table',$cfg->chn->getSQLStruct('table.name'));
		$this->p->setDB('id',$cfg->chn->getSQLStruct('table.field.id'));
		$this->p->setDB('field',$cfg->chn->getSQLStruct('list.fields'));
		$this->p->setDB('query',$this->getQuery());
		$this->p->setDB('order',$cfg->chn->getSQLStruct('list.order'));
		$this->p->setDB('orders',$cfg->chn->getSQLStruct('list.orders'));
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		
		if($this->p->getPage()>1) $cfg->setTitle('page','第'.$this->p->getPage().'页');
	}
	
	public function doParseListQuery()
	{
		
	}
	
	public function doParseSearch()
	{
		global $cfg;
		$this->specialid=queryi('specialid');
		$this->year=queryi('year');
		$this->mode=query('mode');
		
		$this->initSearch();
		//debugx($cfg->chn->getSQLStruct('list.search.fields'));
		$this->s->setFields($cfg->chn->getSQLStruct('list.search.fields'));
		//$this->s->setFieldsProperty($cfg->chn->getSQLStruct('list.search.fields.property'));
		$this->s->setTermType($cfg->chn->getSQLStruct('list.search.term.type'));
		
		if($this->mode){
			if($cfg->chn->isSQLStruct('list.query.'.$this->mode)){
				$this->queryAppend($cfg->chn->getSQLStruct('list.query.'.$this->mode),'mode='.$this->mode);
			}
			else{
				$this->mode='';
			}
		}
		if($this->specialid>0) $this->queryAppend('specialid='.$this->specialid.'','specialid='.$this->specialid);
		if($this->year>0) $this->queryAppend('year('.$cfg->chn->getSQLStruct('table.px').'tim'.')='.$this->year.'','year='.$this->year);
		
		$this->s->doParse();
		if($this->s->isQuery()){
			$this->doSearchSave();
			$this->queryAppend($this->s->getQuery(),$this->s->getQueryURL());
		}
		if($this->isQuery()){
			$this->theme->setAction('result');
			if(inp('pic',$this->mode)>0) $this->theme->setModule('result.'.$this->mode);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheClass()
	{
		$this->theme->doCacheFilterLoop('class','cpo.tableClass');
		$this->theme->doCacheFilterLoop('classroot','cpo.tableClassRoot');
		$this->theme->doCacheFilterLoop('classsub','cpo.tableClassSub');
	}
	
	public function doThemeCacheList($channel='',$key='')
	{
		global $cfg;
		$this->doThemeCacheClass();
		if(!$channel) $channel=$cfg->getChannel();
		$this->theme->doCacheFilterList($channel,$key,'cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
?>