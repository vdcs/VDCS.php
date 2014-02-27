<?
class PagePagePortal extends ManagePortalBase
{
	protected $page='';
	
	public function doDestroy()
	{
		//unset($this->abc);
	}
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setAuth(false);
		$this->theme->setMode('common');
	}
	
	public function doLoad()
	{
		$this->theme->setPage('page');
	}
	
	public function doParse()
	{
		$this->addError('测试提示信息');
		if($this->isRaiseError()) return;
	}
	
	public function doTheme()
	{
	}
	public function doThemeCache()
	{
	}
	
	
	//########################################
	//########################################
	private function DemoFunction()
	{
		$re='';
		
		return $re;
	}
	
}
?>