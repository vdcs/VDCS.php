<?
class ChannelClubMuBlog extends ChannelClubMuBase
{
	public $s,$p;
	protected $_pagenum=5;
	protected $_listnum=10;
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function setNum($pagenum,$listnum)
	{
	    if($pagenum>0) $this->_pagenum=$pagenum;
	    if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	public function doLoad()
	{
		
	}
	
	public function doParse()
	{
		$this->doParseList();
	}
	
	public function doParseList()
	{
		$this->_var['query']=$this->_var['sqlquery.key'];
		$this->_var['query']=DB::sqla($this->_var['query'],'d_status=1');
		$this->_var['url']=$this->cURL('blog');
		$this->_var['order']='d_tim desc';
		$sortid=query('sortid');
		if(len($sortid)>0 && isInt($sortid)){
			$this->_var['query']=DB::sqla($this->_var['query'],'sortid='.$sortid);
			$this->_var['url']=$this->cURL('blog.sort','sortid='.$sortid);
		}
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->BlogDataTableName);
		$this->p->setDB('id',$this->BlogDataFieldID);
		$this->p->setDB('field','*');
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order',$this->_var['order']);
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->BlogDataTablePX);
		$this->doDataFilter($this->tableList);
	}
	public function doDataFilter(&$tableData)
	{
		$tableData->doAppendFields('linkurl');
		$tableData->doBegin();
		while($tableData->isNext()){
			if($tableData->getItemValueInt('tim_up')<1) $tableData->setItemValue('tim_up',$tableData->getItemValueInt('tim'));
			$tableData->setItemValue('linkurl',$this->cURL('blog.view','id='.$tableData->getItemValue('id')));
		}
	}
	
}
?>