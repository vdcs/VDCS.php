<?
class AccountPaLinkmanDomain extends ChannelPaBase
{	
	public function doParse(&$that)
	{
		$that->treeOwner=UcLinkmanDomain::getTree($that->ua,'type='.DB::q('owner',1));
		$that->treeTechnical=UcLinkmanDomain::getTree($that->ua,'type='.DB::q('technical',1));
		$that->treeAdmin=UcLinkmanDomain::getTree($that->ua,'type='.DB::q('admin',1));
		$that->treeBilling=UcLinkmanDomain::getTree($that->ua,'type='.DB::q('billing',1));
	}
	
	
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('owner','cpo.treeOwner');
		$that->theme->doCacheFilterTree('technical','cpo.treeTechnical');
		$that->theme->doCacheFilterTree('admin','cpo.treeAdmin');
		$that->theme->doCacheFilterTree('billing','cpo.treeBilling');
	}
	
}
?>