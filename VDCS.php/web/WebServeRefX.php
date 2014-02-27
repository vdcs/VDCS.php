<?
trait WebServeRefX
{
	use WebServeRefBase;
	

	public function serveInit()
	{
		$this->initServeX();
	}
	public function serveParse()
	{
		$this->putStat();
		$this->doOutput();
		$this->putDebug();
	}
	
	public function themeInit(){}	//$this->serveInit();
	public function themeParse(){$this->serveParse();}
	
}
