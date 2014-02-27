<?
class CommonUpload
{
	const TableName			= 'dbd_upload';
	const FieldID			= 'id';
	
	
	/*
	########################################
	########################################
	*/
	public static function getTotalToday($uurc=APP_UA,$uuid=0)
	{
		$re=0;
		if($uuid>0){
			if(!$uurc) $uurc=APP_UA;
			//tmpTime='DateDiff('.dcs.db.getDateInterval('d').',up_tim,'.dcs.db.DateFunctionName.')';
			$queryTim=DB::sqlSerachTime('tim','tim',DCS::today(),DCS::today());
			$sql='select count(*) from '.self::TableName.' where uurc='.DB::q($uurc,1).' and uuid='.$uuid.' and '.$queryTim;
			$re=DB::queryInt($sql);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getQueryTree($query)
	{
		$sql=DB::sqlSelect(self::TableName,'','*',$query,'');
		return DB::queryTree($sql);
	}
	public static function getQueryTable($query)
	{
		$sql=DB::sqlSelect(self::TableName,'','*',$query,'');
		return DB::queryTable($sql);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getURLBase($type='')
	{
		switch($type){
			case 'download':
				$re=appURL('common.download');
				if(!$re) $re=appURL('common').'download'.EXT_SCRIPT;
				$re=urlLink($re,'res=upload');
				break;
			case 'upload':
				$re=appURL('common.upload');
				if(!$re) $re=appURL('common').'upload'.EXT_SCRIPT;
				break;
			default:
				$re=appURL('upload');
				if(!$re) $re='/upload/';
				break;
		}
		return $re;
	}
	
	public static function getFilenameMake()
	{
		return VDCSTIME::toConvert('',10).utilCode::getRand(5,6);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toDTMLCache($re)
	{
		$re=CommonTheme::toCacheFilterTree($re,'up','cpo.up','getVar');
		//####################
		$_pattern='<up:(config)\(\"'.PATTERN_FLAG_VAR.'\"\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		//debugAry($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=CommonTheme::HTMLMarkHead.'=$cpo->up->getConfig('.CommonTheme::toVarParams($_matches[2][$m]).')'.CommonTheme::HTMLMarkFoot;
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
}