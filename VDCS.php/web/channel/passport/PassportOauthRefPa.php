<?
trait PassportOauthRefPa
{
	
	protected function oauthLoad(&$that)
	{
		$this->treeVar=$that->treeVar;
		$this->oa=&$that->oa;
		$isinit=$this->oauthInit();
		if(!$isinit) return $isinit;
		
		//$theme->setSubdir('oauth');
		//$theme->setPage('oauth');
		$this->theme->setModule('');
		return $isinit;
	}
	
	protected function addVar($k,$v)
	{
		$this->treeVar->addItem($k,$v);
	}
	
	public function doThemeCache(&$that)
	{
		$this->theme->doCacheFilterTree('oauth','cpo.oa','getVar');
	}
	
}
?>