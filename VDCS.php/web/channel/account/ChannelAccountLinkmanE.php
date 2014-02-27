<?
class ChannelAccountLinkmanE extends ChannelAccountBaseE
{

	public function parseAdd(){$this->parseForm();}
	public function parseEdit(){$this->parseForm();}
	public function parseForm()
	{
		$this->theme->setAction('form');
		
		$this->treeData->doAppendTree(UcLinkman::getDataTree($this->ua,queryi('id')));
	}                                                
	
	public function doThemeCache()
	{
		//$this->theme->doCacheFilterTree('view','cpo.treeView');
	}

}
?>