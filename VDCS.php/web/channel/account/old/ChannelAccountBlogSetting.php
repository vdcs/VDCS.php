<?
class ChannelAccountBlogSetting extends ChannelAccountBlogBase
{
	public function doLoad()
	{
		$this->loadVcode($this->channelKey.'setting');
		$this->loadShield();
		
	}
	
	public function doParse()
	{
		
		
	}
	
}
?>