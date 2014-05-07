<?
define('APP_CHANNEL_ASSISTS','shop');
class AccountPaOrderTrack extends ChannelPaBase
{
	use ShopRefOrderView;
	
	public function doParse(&$that)
	{
		$this->refParse($that);
	}
	
	public function doThemeCache(&$that)
	{
		$this->refThemeCache($that);
	}
	
}
