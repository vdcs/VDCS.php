<?
trait ChannelRefViewCall
{
	
	public function doLoad()
	{
		$this->doLoadView();
	}
	
	public function doParse()
	{
		$this->doParseView();
		//if(!$this->isView()) return;
	}
	
	public function doThemeCache()
	{
		$this->doThemeCacheView();
	}
	
}
?>