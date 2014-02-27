<?
class PassportPaLogout extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		/*
		if(!$this->UURC){
			$urc=queryx('ua');
			if($urc) $this->UARC=$urc;
			$this->UURC=$this->UARC;
		}
		*/
		$urc=queryx('ua');
		if($urc){
			$this->ua=&Ua::instance($urc);
		}
		$this->ua->doLogout();
		$this->ua->save();
		//return;
		$this->ua->setAuth(1);
		$this->ua->doAuth(0);
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
