<?
class ChannelCommonVcodei extends WebPortalBase
{
	use WebServeRefRes;
	
	public function doParse()
	{
		//utilIO::outputImage(_BASE_PATH_VDCS.'lib/vcode/securimg/backgrounds/gray.png');
		ChannelXvcodei::parser();
	}
	
}
