<?
class ChannelCompanyMuInfos extends ChannelCompanyMuBase
{
	public $s,$p;
	protected $_pagenum=5,$_listnum=10;
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
		$this->_var['query']=$this->_var['sqlquery.corp'];
		//$this->_var['query']=DB::sqlAppend($this->_var['query'],'c_status=1');
		$this->_var['url']=$this->cURL('blog');
		$this->_var['order']='c_tim desc';
		$sortid=query('sortid');
		if(len($sortid)>0 && isInt($sortid)){
			$this->_var['query']=DB::toSQLAppend($this->_var['query'],'sortid='.$sortid);
			$this->_var['url']=$this->cURL('blog.sort','sortid='.$sortid);
		}
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table','dbi_index');
		$this->p->setDB('id','c_id');
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