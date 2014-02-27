<?
trait ChannelRefListx
{
	use WebRefQuery;
	
	public $s,$p;
	public $tableList;
	protected $_pagenum=5,$_listnum=20;
	protected $issearch=null;
	
	
	public function paInitList(&$that)
	{
		$that->portalPage=&$this->portalPage;
		$that->p=&$this->p;
		$that->s=&$this->s;
		$that->tableList=&$this->tableList;
		$that->classid=&$this->classid;
		$that->specialid=&$this->specialid;
		$that->tableClass=&$this->tableClass;
		$that->tableClassRoot=&$this->tableClassRoot;
		$that->tableClassSub=&$this->tableClassSub;
	}
	
	public function setNum($pagenum,$listnum)
	{
		if($pagenum>0) $this->_pagenum=$pagenum;
		if($listnum>0) $this->_listnum=$listnum;
	}
	
	public function initPaging()
	{
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initSearch()
	{
		$this->s=new libSearch();
		$this->s->doInit();
		if(ins($this->s->keyword,'关键字')>0) $this->s->keyword='';
	}
	
	public function doSearchSave()
	{
		//ModelLinkitemWord::saveSearch($this->s->keyword,$this->_chn_);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheList($channel='',$key='')
	{
		global $cfg;
		if(!$channel) $channel=$cfg->getChannel();
		$this->theme->doCacheFilterList($channel,$key,'cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
?>