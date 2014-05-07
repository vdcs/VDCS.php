<?
trait WebServeRefEle
{
	protected $iserve=true;
	public $serveType		= 'ele';
	
	
	public function serveInit()
	{
		if(DEBUGV!='e') debugSet(false);
		$this->theme->setExtend('e','.e');
	}
	public function serveParse()
	{
		
	}
	
}
