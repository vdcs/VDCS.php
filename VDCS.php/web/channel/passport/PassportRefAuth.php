<?
trait PassportRefAuth
{
	
	public function doAuthe()
	{
		if(!$this->UURC){
			$urc=queryx('ua');
			if($urc) $this->UARC=$urc;
			$this->UURC=$this->UARC;
		}
		$this->ua=&Ua::instance($this->UURC);
		//$this->ua->doInit();
	}
	
	public function doAuth()
	{
		$this->doAuthe();
		$this->ua->setAuthMode(2);
		$this->ua->doInit();
	}
	
}
?>