<?
class ChannelTBaseU extends ChannelTBase
{
	
	/*
	########################################
	########################################
	*/
	public function doAuth()
	{
		$this->doAuthed(1);
		//debugs($this->ua->getData('u_birthday'));
		//debugs($this->ua->getData('birthday'));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadPre()
	{
		//$this->initPages();
	}
	
	
}
?>