<?
class ChannelMmSettings extends ChannelMmSettingsBase
{
	use ChannelRefSettingsModify;
	
	
	protected function initModify()
	{
		//##########
		$this->_var['isedit.fields']='email,mobile,names,realname,company';
		//##########
	}
	
	public function doInit()
	{
		parent::doInit();
		$this->setActions('','info');
	}
	
	public function doLoad()
	{
		$this->theme->setModule('form');
	}
	
	public function doParse()
	{
		switch($this->action){
			case 'password':
				$this->doModifyPassword();
				break;
			default:
				$this->doModifyInfo();
				break;
		}
	}
	
}
?>