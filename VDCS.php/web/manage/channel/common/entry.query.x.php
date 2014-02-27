<?
class PagePortal extends PortalCommonBaseX
{
	
	protected function parseDicts()
	{
		$_items=PagesCommon::dictItemString();
		//debugx($_items);
		$this->addVar('dicts.string',$_items);
		$this->setSucceed();
	}
	
}
