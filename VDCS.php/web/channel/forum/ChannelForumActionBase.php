<?
class ChannelForumActionBase extends ChannelForumBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	/*use WebPortalRefControl{
		WebPortalRefControl::doMessage as public doMessageBase;
	}
	*/
	
	public function doAuth()
	{
		$this->doAuthed();
		$this->initUser();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doMessage($type,$state,$msg='',$url='')
	{
		parent::doMessageBase($type,$state,$msg,$url);
		$theme->setPage('my');
		$theme->setModule('handle');
	}
	
}
?>