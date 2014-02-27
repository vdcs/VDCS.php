<?
trait ChannelRefListi
{
	
	use WebRefQuery;
	
	public $s,$p;
	public $tableList;
	protected $_pagenum=5,$_listnum=20;
	protected $issearch=null;
	
	
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
	
	public function doParse()
	{
		global $cfg;
		
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
	}
	
	public function doThemeCache()
	{
		if(!$channel) $channel=$this->cfg->getChannel();
		$this->theme->doCacheFilterList($channel,$key,'cpo.tableList');
		//$this->theme->doCacheFilterLoop('list','cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
