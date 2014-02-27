<?
class ChannelCommonXmerger extends WebPortalBase
{
	use WebServeRefRes;
	
	public function doParse()
	{
		ChannelXmerger::parser();
	}
	
}
