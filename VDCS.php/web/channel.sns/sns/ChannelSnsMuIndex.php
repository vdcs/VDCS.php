<?
class ChannelSnsMuIndex extends ChannelSnsMuBase
{
	public $tableBlogNew;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableBlog);
	}
	
	
	public function doLoad()
	{
		
	}
	
	public function doParse()
	{
		$this->doParseBlog();
	}
	
	public function doParseBlog()
	{
		$this->_var['query']=$this->_var['sqlquery.key'];
		$this->_var['query']=DB::toSQLAppend($this->_var['query'],'d_status=1');
		$this->_var['order']='d_tim desc';
		$sql=DB::sqlSelect($this->BlogDataTableName,'','*',$this->_var['query'],$this->_var['order'],5);
		$this->tableBlogNew=DB::queryTable($sql);
		$this->tableBlogNew->doFilter($this->BlogDataTablePX);
		$this->doDataBlogFilter($this->tableBlogNew);
	}
	public function doDataBlogFilter(&$tableData)
	{
		$tableData->doAppendFields('linkurl,sort.name,sort.linkurl');
		$tableData->doBegin();
		while($tableData->isNext()){
			if($tableData->getItemValueInt('tim_up')<1) $tableData->setItemValue('tim_up',$tableData->getItemValueInt('tim'));
			$tableData->setItemValue('linkurl',$this->cURL('blog.view','id='.$tableData->getItemValue('id')));
			$tableData->setItemValue('remark',VDCSCodes::toCodes($tableData->getItemValue('remark'),$tableData->getItemValueInt('sp_code')));
			//##########
			$treeSort=$this->tableBlogSort->getFieldItemTree('sortid','=',$tableData->getItemValueInt('sortid'));
			$tableData->setItemValue('sort.name',$treeSort->getItem('name'));
			$tableData->setItemValue('sort.linkurl',$treeSort->getItem('linkurl'));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		$this->theme->doCacheFilterLoop('blognew','cpo.tableBlogNew');
	}
	
}
?>