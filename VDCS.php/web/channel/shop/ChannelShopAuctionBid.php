<?
class ChannelShopAuctionBid extends ChannelShopAuction
{
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function doLoad()
	{
		$this->loadRoot(true);
		if(!$this->isRoot()) return;
		
	}
	
	public function doParse()
	{
		if(!$this->isParse()) return;
		
	}
	
}
?>