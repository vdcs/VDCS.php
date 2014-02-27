<?php
class AccountPaTicket extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$that->treeO=UcLinkmanDomain::getTree($that->ua,'types='.DB::q('owner',1));
		$that->treeT=UcLinkmanDomain::getTree($that->ua,'types='.DB::q('technician',1));
		$that->treeM=UcLinkmanDomain::getTree($that->ua,'types='.DB::q('manager',1));
		$that->treeP=UcLinkmanDomain::getTree($that->ua,'types='.DB::q('payer',1));
	}
	
	
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('owner','cpo.treeO');
		$that->theme->doCacheFilterTree('technician','cpo.treeT');
		$that->theme->doCacheFilterTree('manager','cpo.treeM');
		$that->theme->doCacheFilterTree('payer','cpo.treeP');
	}
	
}