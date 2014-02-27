<?
class apiEntry extends apiBase
{
	
	public function auth()
	{
		$this->authReset();
	}
	
	public function parse()
	{
		$this->parseStatus();
	}
	public function parseStatus()
	{
		$this->addVar('parser',$this->action);
		$this->setSucceed();
	}
	
}
