<?
class ChannelCommonTestX extends ChannelCommonBaseX
{
	use CommonRefTestX;

	public function parseTest()
	{
		$this->addVar('value','test');
		$this->setStatus('test');
	}

}
