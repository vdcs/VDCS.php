<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parseDemo();
	}
	public function parseDemo()
	{
		$this->addVar('parser','parseDemo');
		$this->setSucceed();
	}
	
}
