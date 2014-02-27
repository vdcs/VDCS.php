<?
class ChannelMmGbookX extends ChannelMmGbook
{
	use WebServeRefXML;
	use ModuleRefOperServe;
	
	public function doParse()
	{
		$this->doParseHandler();
	}
	
}
?>