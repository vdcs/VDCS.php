<?
class ChannelSupportAnnounce extends ChannelSupportBase
{
	use WebRefQuery;
	
	public $tableChannel,$treeChannel,$tableSort,$treeSort;
	public $s,$p,$tableList;
	protected $_pagenum=5,$_listnum=20;
	
	public function setNum($pagenum,$listnum)
	{
		if($pagenum>0) $this->_pagenum=$pagenum;
		if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		//$this->theme->setSubDir($this->_p_);
		
		$this->_var['url.root']=$this->cfg->toLinkURL('announce');
		//$this->cfg->setTitleURL($this->_var['url.root']);
		
		$this->TableName=$this->cfg->vp($this->_p_.':table.name');
		$this->TablePX=$this->cfg->vp($this->_p_.':table.px');
		$this->names=$this->cfg->vp($this->_p_.':names');
		$this->addVar('names',$this->names);
		
		$this->tableChannel=$this->cfg->getTable('data.'.$this->_p_.'.channel');
		$this->tableSort=$this->cfg->getTable('data.'.$this->_p_.'.sort');
		
		$this->subchannel=queryx('subchannel');
		if($this->subchannel>0){
			$_var_=$this->subchannel;$this->subchannel='';
			$this->tableChannel->doBegin();
			while($this->tableChannel->isNext()){
				if($_var_==$this->tableChannel->getItemValue('key')){
					$this->subchannel=$_var_;
					$this->treeChannel=$this->tableChannel->getItemTree();
					break;
				}
			}
		}
		/*
		if(len($this->subchannel)<1){
			$this->tableChannel->doBegin();
			$this->subchannel=$this->tableChannel->getItemValue('key');
			$this->treeChannel=$this->tableChannel->getItemTree();
		}
		*/
	}
	
	public function doParse()
	{
		$this->doParseList();
	}
	
	public function doParseList()
	{
		$this->theme->setModule('list');
		
		$this->portalPage=$this->_p_;
		$this->_var['url']=$this->cfg->getConfigValue('url.'.$this->portalPage.'.page');
		/*
		if(len($this->subchannel)<1){
			$this->tableChannel->doBegin();
			$this->subchannel=$this->tableChannel->getItemValue('key');
			$this->treeChannel=$this->tableChannel->getItemTree();
		}
		*/
		if(len($this->subchannel)>0){
			$this->setQuery('channel='.DB::q($this->subchannel,1),$this->_var['url']);
		}
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->getQuery('url'));
		$this->p->setDB('table',$this->cfg->vp($this->portalPage.':table.name'));
		$this->p->setDB('id','id');
		$this->p->setDB('field','*');
		$this->p->setDB('query',$this->getQuery());
		$this->p->setDB('order','orderid desc,tim desc');
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter('a_');
		
		if($this->p->getPage()>1) $this->cfg->setTitle('page','第'.$this->p->getPage().'页');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		$this->theme->doCacheFilterLoop('channel','cpo.tableChannel');
		$this->theme->doCacheFilterLoop('sort','cpo.tableSort');
		$this->theme->doCacheFilterTree('channel','cpo.treeChannel');
		$this->theme->doCacheFilterTree('sort','cpo.treeSort');
		$this->doThemeCacheList();
	}
	
	public function doThemeCacheList()
	{
		$this->theme->doCacheFilterList($this->cfg->getChannel().'.'.$this->_p_,'','cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
?>