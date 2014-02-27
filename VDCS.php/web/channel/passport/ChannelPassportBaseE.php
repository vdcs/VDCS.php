<?
class ChannelPassportBaseE extends ChannelPassportBase
{
	use WebServeRefEle;
	
	/*
	public function doParse()
	{
		$this->parserAction('parse');
	}
	*/
	public function parse()
	{
		$this->addVar('parse','parse');
	}
	public function parseDemo()
	{
		$this->addVar('parse','parseDemo');
	}
	
}
