<?
trait CommonRefStatusX
{
	
	protected function parse()
	{
		$this->parseStatus();
	}

	protected function parseStatus()
	{
		$this->addVar('islocal.defined',defined('ISLOCAL')?'true':'false');
		$this->addVar('islocal',DCS::$local?'true':'false');

		$this->addVar('now',DCS::now());
		$this->addVar('timer',DCS::timer());
		$this->addVar('serverString',DCS::serverString());
		$this->addVar('browseDomain',DCS::browseDomain());
		
		$_db=DB::isQuery('select * from dbs_config');
		$this->addVar('db.status',$_db?'true':'false');

		$this->setSucceed();
	}

}
