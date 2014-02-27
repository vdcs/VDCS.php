<?
class utilRegex
{
	
	public static function toPattern($pattern,$safe=false)
	{
		if($safe){
			$pattern=self::toPatternSafe($pattern);
			$pattern=self::toPatternKey($pattern);
		}
		if(substr($pattern,0,1)!='/' && substr($pattern,0,1)!='|') $pattern='/'.$pattern.'/ies';
		//$pattern='|'.$pattern.'|u';
		return $pattern;
	}
	public static function toMatches($re,$pattern=PATTERN_VAR,&$_matches=null,&$_m=-1)
	{
		//debugvc(self::toPattern($pattern));
		$re=r($re,"\r\n","\n");
		$re=r($re,"\n\n","\n");
		$re=r($re,"\n","\r\n");
		$_m=preg_match_all(self::toPattern($pattern),$re,$_matches);
		return $_matches;
	}
	
	public static function isMatch($re,$patterns,&$_matches=null,&$_m=-1)
	{
		if(isa($patterns)){
			foreach($patterns as $pattern){
				$re=preg_match(self::toPattern($pattern),$re,$_matches);
				if(!$re) return $re;
			}
			return $re;
		}
		else{
			$pattern=$patterns;
			return preg_match(self::toPattern($pattern),$re,$_matches);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toReplacePattern($re,$vstr,$pattern) { return preg_replace(self::toPattern($pattern),$vstr,$re); }
	
	public static function toReplaceRegex($re,$o,$pattern=PATTERN_VAR)
	{
		$func='';
		if(isTree($o)){
			$func='\$o->getItem(\'$1\')';
			/*$func=create_function(
				'$matches',
				'return \$o->getItem($matches[1]);'
			);*/
		}
		elseif(isa($o)) $func='\$o[\'$1\']';
		else $func=$o;
		//debugx($func);
		//debugx(self::toPattern($pattern));
		return preg_replace(self::toPattern($pattern),$func,$re);
	}
	public static function toReplacePre($s,$o) { return self::toReplaceRegex($s,$o,PATTERN_PRE); }
	public static function toReplaceVar($s,$o) { return self::toReplaceRegex($s,$o,PATTERN_VAR); }
	
	//public static function toReplaceFilter($s,$pattern,$filter) { return preg_replace(self::toPattern($pattern),'\$filter',$s); }
	
	
	/*
	########################################
	########################################
	*/
	public static function toDisplaceRegex($re,$strTree,$pattern=PATTERN_VAR)
	{
		$_mn=preg_match_all(self::toPattern($pattern),$re,$_matches);
		for($_mi=0;$_mi<$_mn;$_mi++){
			if($strTree->isItem($_matches[1][$_mi])) $re=r($re,$_matches[0][$_mi],$strTree->getItem($_matches[1][$_mi]));
		}
		unset($_matches);
		return $re;
	}
	public static function toDisplacePre($re,$strTree){ return self::toDisplaceRegex($re,$strTree,PATTERN_PRE); }
	public static function toDisplaceVar($re,$strTree){ return self::toDisplaceRegex($re,$strTree,PATTERN_VAR); }
	
	
	/*
	########################################
	########################################
	*/
	public static function toParsePatternAry($contents,$_pattern,$filter=false)
	{
		if($filter){
			$_pattern=self::toPatternSafe($_pattern);
			$_pattern=self::toPatternKey($_pattern);
		}
		//debugvc($_pattern);
		$_matches=self::toMatches($contents,$_pattern);
		//debuga($_matches);
		//debugx(count($_matches[1]));
		$re=array();
		for($m=1;$m<count($_matches);$m++){
			$re[]=$_matches[$m][0];
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toPatternSafe($re)
	{
		$re=r($re,'<','\\<');
		$re=r($re,'>','\\>');
		$re=r($re,'(','\\(');
		$re=r($re,')','\\)');
		$re=r($re,'[','\\[');
		$re=r($re,']','\\]');
		$re=r($re,'.','\\.');
		$re=r($re,'/','\\/');
		$re=r($re,'\'','\\\'');
		$re=r($re,'=','\\=');
		$re=r($re,'"','\\"');
		$re=r($re,':','\\:');
		$re=r($re,'?','\\?');
		$re=r($re,'!','\\!');
		$re=r($re,'$','\\$');
		return $re;
	}
	
	public static function toPatternKey($re)
	{
		$re=r($re,'{\\$id\\$}','([0-9]*)');
		$re=r($re,'{\\$int\\$}','([0-9]*)');
		$re=r($re,'{\\$date\\$}','([^\<\>]*)');
		$re=r($re,'{\\$url\\$}','([^\<\>]*)');
		$re=r($re,'{\\$var\\$}','([^\<\>]*)');
		$re=r($re,'{\\$date\\$}','([^\<\>]*)');
		$re=r($re,'{\\$content\\$}',PATTERN_FLAG_CONTENT);
		$re=r($re,'\\[\\$id\\$\\]','[0-9]*');
		$re=r($re,'\\[\\$var\\$\\]','[^\<\>]*');
		
		$re=r($re,'\\(\\$\\$\\$','(');
		$re=r($re,'\\$\\$\\$\\)\\?',')?');
		$re=r($re,'\\[\\$\\$\\$','[');
		$re=r($re,'\\$\\$\\$\\]\\?',']?');
		return $re;
	}
	
}
?>