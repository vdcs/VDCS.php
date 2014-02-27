<?
class PagesElementCache
{
	
	public static function toDTMLCache($re)
	{
		$re=self::toDTMLCacheVcode($re);
		return $re;
	}
	
	
	/* ################################## */
	public static function toDTMLCacheVcode($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='cpo.vcode';
		$oprefix=CommonTheme::toVarCache($oprefix,1);
		$_pattern='<(vcp|vcode):'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.'>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$tmpParam[0]=$_matches[0][$m];
			$tmpParam[1]=$_matches[1][$m];
			$tmpParam[2]=$_matches[2][$m];
			$tmpParam[4]=$_matches[4][$m];
			//debuga($tmpParam);
			$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'toDTMLValue('.CommonTheme::toVarParams($tmpParam[2]).')'.CommonTheme::HTMLMarkFoot;
			$re=r($re,$tmpParam[0],$tmpValue);
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}
	
}
?>