<?
class PagePortal extends ManagePageBase
{
	public function doLoad()
	{
		global $mp;
		//debugx(ManageCommon::entryURL($mp->chn->get(),'mlog'));
		mgo(ManageCommon::entryURL($mp->chn->get(),'mlog'));
	}
}
?>