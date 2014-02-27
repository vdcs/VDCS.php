<?
class ChannelPassportActivation extends ChannelPassportBaseX
{
	
	public function parse()
	{
		$this->parseAction();
	}
	public function parseAction()
	{
		$this->addVar('parse','parseAction');
	}
	
}
