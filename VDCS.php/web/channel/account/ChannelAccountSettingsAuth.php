<?
class ChannelAccountSettingsAuth extends ChannelAccountSettingsBase
{
	
	public function doParse()
	{
		switch($this->action){
			case 'idcard':
			case 'certif':
				$this->theme->setAction($this->action);
				break;
			default:
				break;
		}
		
	}
	
}
?>