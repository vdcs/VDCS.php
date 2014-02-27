<?
class ChannelCompanyClass extends ChannelCompanyBase
{
	use ChannelRefList,ChannelRefListCallc;
	
	public function doLoad()
	{
		$this->doLoadClass();
		$this->cfg->setTitle('chn',$this->cfg->v('title'));
	}
	
}
?>