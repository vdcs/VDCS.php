<?
class ChannelCompanyIndex extends ChannelCompanyBase
{
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function doLoadPre()
	{
		$this->cfg->setTitle('');
	}
	
	public function doParse()
	{
		
	}
	
}
?>