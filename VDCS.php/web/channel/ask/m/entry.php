<?
class PagePortal extends ManagePageBase
{
	public function doLoad()
	{
		global $mp;
		mgo(ManageCommon::entryURL($mp->chn->get(),'question'));
	}
}
?>