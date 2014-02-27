<?
class SupportPaAnnounce extends ChannelPaBase
{
	use WebRefQuery;
	
	public $tableChannel,$treeChannel,$tableSort,$treeSort;
	public $s,$p,$tableList,$view;
	protected $_pagenum=5,$_listnum=20;
	
	public function setNum($pagenum,$listnum)
	{
		if($pagenum>0) $that->_pagenum=$pagenum;
		if($listnum>0) $that->_listnum=$listnum;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad(&$that)
	{
		if ($that->action=='view'){
			$this->doLoadView($that);
			return;
		}

		$that->tableList=newTable();
		//$that->theme->setSubDir($that->_p_);
		
		$this->_var['url.root']=$that->cfg->toLinkURL('announce');
		//$that->cfg->setTitleURL($that->_var['url.root']);
		
		$that->TableName=$that->cfg->vp($that->_p_.':table.name');
		$that->TablePX=$that->cfg->vp($that->_p_.':table.px');
		$that->names=$that->cfg->vp($that->_p_.':names');
		
		$that->tableChannel=$that->cfg->getTable('data.'.$that->_p_.'.channel');
		$that->tableSort=$that->cfg->getTable('data.'.$that->_p_.'.sort');
		
		/*
		$that->subchannel=queryx('subchannel');
		if($that->subchannel>0){
			$_var_=$that->subchannel;$that->subchannel='';
			$that->tableChannel->doBegin();
			while($that->tableChannel->isNext()){
				if($_var_==$that->tableChannel->getItemValue('key')){
					$that->subchannel=$_var_;
					$that->treeChannel=$that->tableChannel->getItemTree();
					break;
				}
			}
		}
		if(len($that->subchannel)<1){
			$that->tableChannel->doBegin();
			$that->subchannel=$that->tableChannel->getItemValue('key');
			$that->treeChannel=$that->tableChannel->getItemTree();
		}
		*/
	}
	
	public function doParse(&$that)
	{
		if ($that->action=='view'){
			$this->doParseView($that);
			return;
		}
		$this->doParseList($that);
	}
	
	/*
	########################################
	########################################
	*/
	public function doLoadView(&$that)
	{
		$that->TableName=$that->cfg->vp($that->_p_.':table.name');
		$that->TablePX=$that->cfg->vp($that->_p_.':table.px');
		$that->names=$that->cfg->vp($that->_p_.':names');

		$that->view=new WebPageView();
		$that->view->setStruct('table.name',$that->TableName);
		$that->view->setStruct('table.px',$that->TablePX);
		$that->view->setStruct('query','{$table.px}status=1 and {$table.px}id={$id}');
		$that->view->setStruct('sql.update','update {$table.name} set {$table.px}total_view={$table.px}total_view+1 where {$table.px}id={$id}');
		$that->view->setAuthMode(0);
		$that->view->doLoad();
	}
	
	public function doParseView(&$that)
	{
		$that->theme->setModule('view');

		if(!$that->view->isDat()){
			go($that->_var['url.root']);
			return;
		}
		$that->id=$that->view->getVar('id');
		$that->view->doParse();
		
		$that->cfg->setTitle('sub',$that->view->getDatas('topic'));
	}

	public function doParseList(&$that)
	{
		$that->theme->setModule('list');
		
		$that->portalPage=$that->_p_;
		/*
		if(len($that->subchannel)<1){
			$that->tableChannel->doBegin();
			$that->subchannel=$that->tableChannel->getItemValue('key');
			$that->treeChannel=$that->tableChannel->getItemTree();
		}
		if(len($that->subchannel)>0){
			$that->setQuery('channel='.DB::q($that->subchannel,1),$that->_var['url']);
		}
		*/
		
		$that->p=new libPaging();
		$that->p->setPageNum($that->_pagenum);
		$that->p->setListNum($that->_listnum);
		$that->p->setConfig('url',$that->getQuery('url'));
		$that->p->setDB('table',$that->cfg->vp($that->portalPage.':table.name'));
		$that->p->setDB('id','id');
		$that->p->setDB('field','*');
		$that->p->setDB('query',$that->getQuery());
		$that->p->setDB('order','orderid desc,tim desc');
		$that->p->setTotal(DB::queryInt($that->p->getSQL('count')));
		$that->p->doParse();
		$that->tableList=$that->p->toTable();
		//$that->tableList->doFilter('a_');
		
		if($that->p->getPage()>1) $that->cfg->setTitle('page','第'.$that->p->getPage().'页');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterLoop('channel','cpo.tableChannel');
		$that->theme->doCacheFilterLoop('sort','cpo.tableSort');
		$that->theme->doCacheFilterTree('channel','cpo.treeChannel');
		$that->theme->doCacheFilterTree('sort','cpo.treeSort');

		if ($that->action=='view'){
			$this->doThemeCacheView($that);
			return;
		}
		$this->doThemeCacheList($that);
	}
	
	public function doThemeCacheList(&$that)
	{
		$that->theme->doCacheFilterList($that->cfg->getChannel().'.'.$that->_p_,'','cpo.tableList');
		$that->theme->doCacheFilterPaging($that->p,'cpo.p');
	}
	
	public function doThemeCacheView(&$that)
	{
		global $theme,$cfg;
		$theme->output=$that->view->toDTMLCache($theme->output,'cpo.view');
	}
}
?>