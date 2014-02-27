<?
trait PortalUaRefUa
{
	use PortalUaRefBase;
	
	
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
		if(!$this->refListLoad()) return;
		$this->doList();
	}
	
	protected function parseView()
	{
		if(!$this->refViewLoad()) return;
	}

	protected function parseAutologin()
	{
		if(!$this->refAutologinLoad()) return;
	}
	
	
	//####################
	public function doThemeCache()
	{
		$this->refThemeCache();
	}
	
}
?>