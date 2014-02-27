<?
class ChannelShopAuctionAuto extends ChannelShopAuction
{
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function doLoad()
	{
		$this->loadRoot(true);
	}
	
	public function doParse()
	{
		
	}
	
}
?>