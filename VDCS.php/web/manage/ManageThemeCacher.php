<?
class ManageThemeCacher extends BaseThemeCacher
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCachePre($re)
	{
		$re=parent::toDTMLCachePre($re);
		
		//control
		$re=PagesControlCache::toDTMLPreUI($re);
		
		return $re;
	}
	
	public function toDTMLCache($re)
	{
		$re=parent::toDTMLCache($re);
		
		//manager
		$re=CommonTheme::toCacheFilterTree($re,'manager','ma.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'ma','ma.','getData');
		
		return $re;
	}
}
?>