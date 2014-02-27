<?
class ChannelCompanyView extends ChannelCompanyBase
{
	use ChannelRefView,ChannelRefViewCall;
	
	public function doLoadPos()
	{
		global $cfg;
		$cfg->setTitle('');
	}
	
}
?>