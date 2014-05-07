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
		$_status=queryx('status');
		if($_status){
			$this->addVar('id',queryi('id'));
			$this->addVar('value',query('value'));
			$this->addVar('data',query('data'));
			$this->setStatus($_status);
		}
		else{
			$this->setSucceed();
		}
	}
	
}
