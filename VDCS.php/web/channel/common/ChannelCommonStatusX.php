<?
class ChannelCommonStatusX extends ChannelCommonBaseX
{
	use CommonRefStatusX;

	public function parseTest()
	{
		$this->addVar('value','test');
		$this->setStatus('test');
	}

}
