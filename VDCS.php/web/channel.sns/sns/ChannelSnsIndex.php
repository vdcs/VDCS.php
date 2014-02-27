<?
class ChannelSnsIndex extends ChannelSnsBase
{
	
	public function doLoadPre()
	{
	}
	
	public function doParse()
	{
		go(appURL('root'));
	}
	
}
?>