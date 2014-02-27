<?
class ChannelPassportRegisterX extends ChannelPassportBaseX
{
	use PassportRefServeRegister;
	
	public function doParse()
	{
		$this->doParseServe();
	}
	
}
?>