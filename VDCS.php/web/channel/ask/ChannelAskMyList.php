<?
class ChannelAskMyList extends ChannelAskMyBase
{
	public $s,$p;
	public $tableList;
	protected $_pagenum=5;
	protected $_listnum=10;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->tableList);
	}
	
	public function setNum($pagenum,$listnum)
	{
	    if($pagenum>0) $this->_pagenum=$pagenum;
	    if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	public function doLoad()
	{
		global $cfg;
		
		$this->_var['url']=$cfg->url('my.'.$this->_m_);
		$this->_var['query']=$cfg->chn->getSQLStruct('list.query');
	}
	
	public function doParse()
	{
		global $cfg;
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$cfg->chn->getSQLStruct('table.name'));
		$this->p->setDB('id',$cfg->chn->getSQLStruct('table.field.id'));
		$this->p->setDB('field',$cfg->chn->getSQLStruct('list.fields'));
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order',$cfg->chn->getSQLStruct('list.order'));
		$this->p->setDB('orders',$cfg->chn->getSQLStruct('list.orders'));
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
	}
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		global $cfg;
		$this->theme->doCacheFilterList($cfg->getChannel(),'','cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
}
?>