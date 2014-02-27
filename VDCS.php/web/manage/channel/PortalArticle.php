<?
class PortalArticle extends ManagePortalBase
{
	use PortalArticleRef;
	
	
	public function doLoad()
	{
		$this->pagex='article';
		$this->theme->setPage($this->pagex);
		$this->refLoad();
	}
	
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		$this->doPagesFormParse();
	}
	
	protected function parseList()
	{
		$this->doList();
	}
	
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>