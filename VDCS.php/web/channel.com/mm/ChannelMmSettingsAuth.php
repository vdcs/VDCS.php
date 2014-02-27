<?
class ChannelMmSettingsAuth extends ChannelMmSettingsBase
{
	
	public function doParse()
	{
		switch($this->action){
			case 'certic':
			case 'certif':
				$this->theme->setAction($this->action);
				break;
			default:
				break;
		}
		
	}
	
}
?>