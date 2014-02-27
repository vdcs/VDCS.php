<?
class ChannelShopBaseX extends ChannelShopBase
{
	use WebServeRefXML,WebPortalRefAuthX;//
	use WebPortalRefControl;
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->initPages();
	}
	
}