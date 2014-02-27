<?
class ChannelMmSettingsPhoto extends ChannelMmSettingsBase
{
	
	public function doParse()
	{
		switch($this->action){
			case 'demo':
				$this->theme->setAction($this->action);
				break;
			default:
				break;
		}
		
	}
	
}
?>