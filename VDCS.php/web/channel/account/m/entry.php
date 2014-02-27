<?
class PagePortal extends ManagePortalBase
{
	
	public function doLoad()
	{
		//debugx($this->chn->get());
		//debugx(ManageCommon::entryURL($this->chn->get(),APP_UA));
		mgo(ManageCommon::entryURL($this->chn->get(),APP_UA));
	}
	
}
