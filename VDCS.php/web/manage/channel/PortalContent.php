<?
class PortalContent extends ManagePortalBase
{
	use PortalContentRef;
	
	
	public function doLoad()
	{
		$this->pagex=$this->_chn_;
		$this->pagex='content';
		$this->theme->setPage($this->pagex);
		//$this->theme->setPage('content');
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