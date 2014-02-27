<?
class ChannelAccountSettings extends ChannelAccountSettingsBase
{
	use ChannelRefSettingsModify;
	

	public function initModify()
	{
		//##########
		$this->_var['isedit.fields']='gender,email,mobile,idtype,idcard,names,nickname,realname';
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
	
	public function doParse_()
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