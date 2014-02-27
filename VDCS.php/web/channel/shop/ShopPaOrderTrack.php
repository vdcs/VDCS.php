<?php
class ShopPaOrderTrack extends ChannelPaBase
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
?>