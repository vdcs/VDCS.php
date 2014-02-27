<?
class TAt
{
	
	public static function toMatches($content)
	{
		$pattern='@([^\s:@<>,;:\.，：；。]+)';
		$pattern='@([^\s:@<>,;:]+)';
		$_matches=utilRegex::toMatches($content,$pattern);
		//测试连续@效果 @小彬@maric @章鱼大叔 @测试员
		return $_matches;
	}
	
	public static function values($uvalue,&$uid,&$unames)
	{
		global $ua;
		//debugs($uvalue);
		if(isInt($uvalue)){
			$uid=0;
			$unames=$ua->getInfoField($uvalue,'names');
			if($unames) $uid=$uvalue;
		}
		else{
			//$uid=$ua->getInfoIDbyNo($uvalue);
			$unames='';
			$uid=$ua->getInfoIDbyNames($uvalue);
			if($uid) $unames=$uvalue;
		}
		//debugvc($uvalue.': '.$uid.'='.$unames);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function trans($content)
	{
		$_matches=self::toMatches($content,$pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			//$_matches[0][$m]
			$uvalue=trim($_matches[1][$m]);
			//debugs($uvalue);
			self::values($uvalue,$uid,$unames);
			//debugvc($uvalue.': '.$uid.'='.$unames);
			if($uid>0){
				$value='<a href="/u/'.$uid.'" uid="'.$uid.'">@'.$unames.'</a>';
				$content=r($content,$_matches[0][$m],$value);
			}
		}
		return $content;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function filterID($content)
	{
		$_matches=self::toMatches($content,$pattern);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			//$_matches[0][$m]
			$uvalue=trim($_matches[1][$m]);
			//debugs($uvalue);
			self::values($uvalue,$uid,$unames);
			//debugvc($uvalue.': '.$uid.'='.$unames);
			if($uid>0 && !isInt($uvalue)){
				$value='@'.$uid.'';
				$content=r($content,$_matches[0][$m],$value);
			}
		}
		return $content;
	}
	
	public static function filterNames($content)
	{
		$_matches=self::toMatches($content,$pattern);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			//$_matches[0][$m]
			$uvalue=trim($_matches[1][$m]);
			//debugs($uvalue);
			self::values($uvalue,$uid,$unames);
			//debugvc($uvalue.': '.$uid.'='.$unames);
			if($uid>0 && isInt($uvalue)){
				$value='@'.$unames.'';
				$content=r($content,$_matches[0][$m],$value);
			}
		}
		return $content;
	}
	
}
?>