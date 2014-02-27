<?
class ChannelPassportBaseX extends ChannelPassportBase
{
	use WebServeRefX;
	
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
