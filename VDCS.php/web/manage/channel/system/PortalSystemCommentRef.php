<?php
trait PortalSystemCommentRef
{
	
	protected function refLoad()
	{
		
	}
	public function refThemeCache()
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
	}
	
	
	protected function refEditLoad()
	{
	}
	
	protected function refViewLoad()
	{
		$id=queryi('id');
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		$this->treeView=DB::queryTree($sql);
		$trans_name=$this->treeView->getItem('trans_name');
		if(!$trans_name) $this->treeView->setItem('trans_name',$this->ma->name);
		$trans_tim=$this->treeView->getItem('trans_tim');
		if(!$trans_tim) $this->treeView->setItem('trans_tim',DCS::timer());
	}
}
?>