<?
trait ChannelRefListCalls
{
	
	public function doLoad()
	{
		$this->doLoadClass();
	}
	
	public function doParse()
	{
		$this->doParseClass();
		$this->doParseList();
	}
	
	public function doThemeCache()
	{
		$this->doThemeCacheClass();
		$this->doThemeCacheList();
	}
	
}
?>