<?
class ChannelClubMyBaseX extends ChannelClubMyBase
{
	use WebServeRefXML,WebPortalRefAuthX;
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		$this->setStatus('init');
		switch($this->action){
			case 'digg':
				$this->doParseDigg();
				break;
			case 'send':
				$this->doParseSend();
				break;
		}
	}
	
	
}
?>