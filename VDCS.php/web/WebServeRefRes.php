<?
trait WebServeRefRes
{
	protected $iserve=true;
	public $serveType		= 'res';
	
	
	public function serveInit()
	{
		debugSet(false);
		//ob_end_clean();
	}
	public function serveParse()
	{
		$this->doPut();
	}
	
	public function themeInit(){$this->serveInit();}
	public function themeParse(){$this->serveParse();}
	
	
	protected function doPut()
	{
		
	}
	
}
