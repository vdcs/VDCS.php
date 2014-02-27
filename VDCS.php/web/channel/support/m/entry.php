<?
class PagePortal extends ManagePortalBase
{
	
	public function doLoad()
	{
		mgo(ManageCommon::entryURL($this->chn->get(),'faq'));
	}
	
}
