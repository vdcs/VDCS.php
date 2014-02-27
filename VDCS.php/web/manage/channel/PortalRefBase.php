<?
trait PortalRefBase
{
	
	protected function refLoad()
	{
		
	}
	public function refThemeCache()
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
	}
	
	/*
	protected function parseList()
	{
		//##########
		//$this->setSearchMode(0);
		if($this->s->isQuery()){
			$this->doAppendQuery($sqlQuery);
		}
		//##########
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		$tableData->doBegin();
		while($tableData->isNext()){
			
			debugx($tableData->getItemValue('id'));
		}
	}
	*/

}
