<?
trait WebServeRefDat
{
	use WebServeRefBase;
	public $serveType		= 'dat';
	
	
	public function serveInit()
	{
		debugSet(false);
		define('DEBUG_TYPE',1);
		$this->serve=new PageServeDat();
		$this->serve->putHead();
	}
	public function serveParse()
	{
		$this->putStat();
		$this->doOutput();
		$this->putDebug();
	}
	
	public function themeInit(){$this->serveInit();}
	public function themeParse(){$this->serveParse();}
	
	
	protected function doPut()
	{
		
	}
	
}
