<?
class CommonLabelCache
{
	public static function toDTMLCache($re)
	{
		global $label;
		//####################
		//<class:value("news","3","name")>
		$_matches=utilRegex::toMatches($re,'<class:value\("'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_VAR.'"\)>');
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$cfg->clas->getChannelValue('.CommonTheme::toVarParams($_matches[1][$m]).','.toInt($_matches[2][$m]).','.CommonTheme::toVarParams($_matches[3][$m]).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<label:class\("'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_PARAMS.'"\)>'.PATTERN_FLAG_CONTENT.'<\/label:end>');
		for($m=0;$m<count($_matches[0]);$m++){
			//$re=r($re,$_matches[0][$m],toParseClass(rMatch.SubMatches(0), rMatch.SubMatches(1), rMatch.SubMatches(2)))
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->toParseClass('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toVarParams($_matches[2][$m]).','.CommonTheme::toDTMLCacheValue($_matches[3][$m],1).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<label:special\("'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_PARAMS.'"\)>'.PATTERN_FLAG_CONTENT.'<\/label:end>');
		for($m=0;$m<count($_matches[0]);$m++){
			//$re=r($re,$_matches[0][$m],toParseSpecial(rMatch.SubMatches(0), rMatch.SubMatches(1), rMatch.SubMatches(2)))
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->toParseSpecial('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toVarParams($_matches[2][$m]).','.CommonTheme::toDTMLCacheValue($_matches[3][$m],1).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,CommonTheme::PATTERN_DTML_LABEL_BLOCK);
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->toParseBlock('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toVarParams($_matches[2][$m]).','.CommonTheme::toDTMLCacheValue($_matches[3][$m],1).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<label:links\("'.PATTERN_FLAG_VAR.'"\)>'.PATTERN_FLAG_CONTENT.'<\/label:end>');
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->toParseLinks('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toDTMLCacheValue($_matches[2][$m],1).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<xcml:value\("'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_VAR.'"(,"'.PATTERN_FLAG_VAR.'")?\)>');
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->getXCMLValue('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toVarParams($_matches[2][$m]).','.CommonTheme::toVarParams($_matches[4][$m]).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<xcml:item\("'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_VAR.'","'.PATTERN_FLAG_VAR.'"(,"'.PATTERN_FLAG_VAR.'")?\)>');
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.'=$theme->label->getXCMLItem('.CommonTheme::toVarParams($_matches[1][$m]).','.CommonTheme::toVarParams($_matches[2][$m]).','.CommonTheme::toVarParams($_matches[3][$m]).','.CommonTheme::toVarParams($_matches[5][$m]).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
}
?>