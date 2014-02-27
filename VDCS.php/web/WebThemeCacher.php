<?
class WebThemeCacher extends BaseThemeCacher
{
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCachePre($re)
	{
		$re=parent::toDTMLCachePre($re);
		
		//control
		$re=PagesControlCache::toDTMLPreUI($re);
		
		$re=CommonTheme::toCacheFilterTree($re,'ua','cpo.ua.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uac','cpo.uac.','getData');
		
		return $re;
	}
	
}
?>