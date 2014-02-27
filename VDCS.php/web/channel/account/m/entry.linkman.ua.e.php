<?
class PagePortal extends ManagePortalBaseE
{

	public function parseAdd(){$this->parseForm();}
	public function parseEdit(){$this->parseForm();}
	public function parseForm()
	{
		$this->theme->setAction('form');

		//$this->ua=;
		$this->treeData->doAppendTree(UcLinkman::getDataTree($this->ua,queryi('id')));
	}
	
	public function doThemeCache()
	{
		//$this->theme->doCacheFilterTree('view','cpo.treeView');
	}

}
?>