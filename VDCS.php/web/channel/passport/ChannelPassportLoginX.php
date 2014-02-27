<?
class ChannelPassportLoginX extends ChannelPassportBaseX
{
	use PassportRefServeLogin;
	
	public function doParse()
	{
		$this->doParseServe();
	}
	
}
?>