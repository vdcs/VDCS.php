<?
class PagesControlCache
{
	
	public static function toDTMLCache($re)
	{
		global $ctl;
		$re=self::toDTMLCacheBase($re);
		$re=self::toDTMLCacheUI($re);
		$re=self::toDTMLCachePages($re,'ctl.pages');
		$re=self::toDTMLCacheControl($re,'ctl.pages');
		//####################
		if(ins($re,'<ctl:')>0){
			$re=preg_replace_callback('/\<ctl\:'.PATTERN_FLAG_VAR.'\>/is',create_function('$matches',"return CommonTheme::HTMLMarkHeads.'\$ctl->'.\$matches[1].CommonTheme::HTMLMarkFoot;"),$re);
			//$re=r($re,'{@ctl.module}',CommonTheme::HTMLMarkHead.'='.$vctl.'module'.CommonTheme::HTMLMarkFoot);
		}
		if(ins($re,'{@ctl:')>0){
			$re=preg_replace_callback('/\{\@ctl\:'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return CommonTheme::HTMLMarkHeads.'\$ctl->'.\$matches[1].CommonTheme::HTMLMarkFoot;"),$re);
			//$re=r($re,'{@ctl.module}',CommonTheme::HTMLMarkHead.'='.$vctl.'module'.CommonTheme::HTMLMarkFoot);
		}
		if(ins($re,'{$ctl:')>0){
			$re=preg_replace_callback('/\{\$ctl\:'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return '\$ctl->'.\$matches[1];"),$re);
			//$re=r($re,'{$ctl.module}',$vctl.'module');
		}
		if(ins($re,'{$$ctl:')>0){
			$re=preg_replace_callback('/\{\$\$ctl\:'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return '\'.\$ctl->'.\$matches[1].'.\'';"),$re);
			//$re=r($re,'{$$ctl.module}','''&ctl.module&''');
		}
		if(ins($re,'{@ctl.')>0){
			$re=preg_replace_callback('/\{\@ctl.'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return CommonTheme::HTMLMarkHeads.'\$ctl->'.\$matches[1].CommonTheme::HTMLMarkFoot;"),$re);
			//$re=r($re,'{@ctl.module}',CommonTheme::HTMLMarkHead.'='.$vctl.'module'.CommonTheme::HTMLMarkFoot);
		}
		if(ins($re,'{$ctl.')>0){
			$re=preg_replace_callback('/\{\$ctl.'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return '\$ctl->'.\$matches[1];"),$re);
			//$re=r($re,'{$ctl.module}',$vctl.'module');
		}
		if(ins($re,'{$$ctl.')>0){
			$re=preg_replace_callback('/\{\$\$ctl.'.PATTERN_FLAG_VAR.'\}/is',create_function('$matches',"return '\'.\$ctl->'.\$matches[1].'.\'';"),$re);
			//$re=r($re,'{$$ctl.module}','''&ctl.module&''');
		}
		//####################
		//VDCSDTML::toRepaceVar/Dat/Data/DTML		(\\w+)(\!(\\w+))?
		$_pattern='<(var|dat|data|dtml):'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=self::toDTMLCacheVarsString($_matches[1][$m],$_matches[2][$m],$_matches[4][$m],$_matches[6][$m]);
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHeads.$rFlagValue.CommonTheme::HTMLMarkFoot);
		}
		//####################
		$_pattern='\{\$(var|dat|data|dtml):'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'\}';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=self::toDTMLCacheVarsString($_matches[1][$m],$_matches[2][$m],$_matches[4][$m],$_matches[6][$m]);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='\{\$\$(var|dat|data|dtml):'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'\}';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=self::toDTMLCacheVarsString($_matches[1][$m],$_matches[2][$m],$_matches[4][$m],$_matches[6][$m]);
			$re=r($re,$_matches[0][$m],'\'.'.$rFlagValue.'.\'');
		}
		//####################
		unsetr($_matches);
		//####################
		$re=CommonTheme::toCachePaging($re,'ctl.p');
		return $re;
	}
	
	public static function toDTMLCacheVarsString($vars,$key,$option1,$option2)
	{
		$re='$ctl->';
		switch($vars){
			case 'dat':	$re.='treeDat';break;
			case 'data':	$re.='treeData';break;
			case 'dtml':	$re.='treeDTML';break;
			default:	$re.='treeVar';break;
		}
		$re.='->getItem(\''.$key.'\')';
		if($option1) $re='VDCSDTML::toEncodeFilterValue('.$re.','.CommonTheme::toVarParams($option1).','.CommonTheme::toVarParams($option2).')';
		return $re;
	}
	
	
	/* ################################## */
	public static function toDTMLCacheBase($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='ctl.ui';
		$oprefix=CommonTheme::toVarCache($oprefix,1);
		$_pattern='<control:(att)\("(.[^<>"]*)"(,"(.|[^"]*)")?(,"(.|[^"]*)")?(,"(.|[^"]*)")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$tmpParam[0]=$_matches[0][$m];
			$tmpParam[1]=$_matches[1][$m];
			$tmpParam[2]=$_matches[2][$m];
			$tmpParam[3]=$_matches[4][$m];
			$tmpParam[4]=$_matches[6][$m];
			$tmpParam[5]=$_matches[8][$m];
			//debugAry($tmpParam);
			$tmpValue=PagesCommon::getValueControlParams($tmpParam[1],$tmpParam[2],$tmpParam[3],$tmpParam[4],$tmpParam[5]);
			$re=r($re,$tmpParam[0],$tmpValue);
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}
	
	public static function toDTMLCacheUI($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='ctl.ui';
		$oprefix=CommonTheme::toVarCache($oprefix,1);
		$_pattern='<control\:ui\.([\w-\.][^\"]*)\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$tmpParam[0]=$_matches[0][$m];
			$tmpParam[1]=$_matches[1][$m];
			$tmpParam[2]=$_matches[2][$m];
			$tmpParam[3]=$_matches[4][$m];
			$tmpParam[4]=$_matches[6][$m];
			$tmpValue='';
			//debugArray($tmpParam);
			switch($tmpParam[1]){
				case 'form':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getForm('.CommonTheme::toVarParams($tmpParam[2]).','.CommonTheme::toVarParams($tmpParam[3]).')'.CommonTheme::HTMLMarkFoot; break;
				case 'table':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getTable('.CommonTheme::toVarParams($tmpParam[2]).','.CommonTheme::toVarParams($tmpParam[3]).','.CommonTheme::toVarParams($tmpParam[4]).')'.CommonTheme::HTMLMarkFoot; break;
				case 'frame':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getFrame('.CommonTheme::toVarParams($tmpParam[2]).')'.CommonTheme::HTMLMarkFoot; break;
				case 'space':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getSpace('.CommonTheme::toVarParams($tmpParam[2]).')'.CommonTheme::HTMLMarkFoot; break;
				case 'value':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getValue('.CommonTheme::toVarParams($tmpParam[2]).')'.CommonTheme::HTMLMarkFoot; break;
				case 'lang':
				case 'langs':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getLangs('.CommonTheme::toVarParams($tmpParam[2]).')'.CommonTheme::HTMLMarkFoot; break;
				//case 'error':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getError('.CommonTheme::toVarParams($tmpParam[2]).','.CommonTheme::toVarParams($tmpParam[3]).')'.CommonTheme::HTMLMarkFoot; break;
				//case 'message':	$tmpValue=CommonTheme::HTMLMarkHeads.$oprefix.'getMessage('.CommonTheme::toVarParams($tmpParam[2]).','.CommonTheme::toVarParams($tmpParam[3]).','.CommonTheme::toVarParams($tmpParam[4]).')'.CommonTheme::HTMLMarkFoot; break;
			}
			$re=r($re,$tmpParam[0],$tmpValue);
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}
	
	public static function toDTMLCachePages($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='ctl.pages';
		$oprefix=CommonTheme::toVarCache($oprefix,1);
		$_pattern='<control:form\.element\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		//debugAry($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$tmpParam[0]=$_matches[0][$m];
			$tmpParam[1]='form.element';
			$tmpParam[2]=$_matches[1][$m];
			$tmpParam[3]=$_matches[3][$m];
			$tmpParam[4]=$_matches[5][$m];
			$tmpParam[5]=$_matches[7][$m];
			$tmpParam3=null;
			if($tmpParam[3]=='__value'){
				$tmpParam[3]='';
				$_n=preg_match_all("/(.[^\.\s]*)\.(.[^\$\s]*)(\$\$\$(.[^\$\s]*))?/ies",$tmpParam[2],$__matches);
				//debugx($_n.','.CommonTheme::toVarParams($__matches[2][0]));
				if($_n && strlen($__matches[2][0])>0) $tmpParam3='$ctl->treeData->getItem('.CommonTheme::toVarParams($__matches[2][0]).')';	//$ctl->treeData->getItem($_matches[2][$m]);
			}
			if($tmpParam3==null) $tmpParam3=CommonTheme::toVarParams($tmpParam[3]);
			//debugAry($tmpParam);
			$tmpValue=CommonTheme::HTMLMarkHeads.'PagesCommon::getValueControlParams('.CommonTheme::toVarParams($tmpParam[1]).','.CommonTheme::toVarParams($tmpParam[2]).','.$tmpParam3.','.CommonTheme::toVarParams($tmpParam[4]).','.CommonTheme::toVarParams($tmpParam[5]).')'.CommonTheme::HTMLMarkFoot;
			$re=r($re,$tmpParam[0],$tmpValue);
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}
	
	public static function toDTMLCacheControl($re,$oprefix='',$repet=1)
	{
		if(!$oprefix) $oprefix='ctl.pages';
		$oprefix=CommonTheme::toVarCache($oprefix,1);
		for($r=0;$r<$repet;$r++){
			$_pattern='<control:([\w\-\.][^"]*)\("(.[^<>"]*)"(,"(.|[^"]*)")?(,"(.|[^"]*)")?(,"(.|[^"]*)")?\)>';
			$_matches=utilRegex::toMatches($re,$_pattern);
			//debugAry($_matches);
			for($m=0;$m<count($_matches[0]);$m++){
				$tmpParam[0]=$_matches[0][$m];
				$tmpParam[1]=$_matches[1][$m];
				$tmpParam[2]=$_matches[2][$m];
				$tmpParam[3]=$_matches[4][$m];
				$tmpParam[4]=$_matches[6][$m];
				$tmpParam[5]=$_matches[8][$m];
				//debugAry($tmpParam);
				$tmpValue=CommonTheme::HTMLMarkHeads.'PagesCommon::getValueControlParams('.CommonTheme::toVarParams($tmpParam[1]).','.CommonTheme::toVarParams($tmpParam[2]).','.CommonTheme::toVarParams($tmpParam[3]).','.CommonTheme::toVarParams($tmpParam[4]).','.CommonTheme::toVarParams($tmpParam[5]).')'.CommonTheme::HTMLMarkFoot;
				$re=r($re,$tmpParam[0],$tmpValue);
			}
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}
	
	
	public static function toDTMLPreUI($re)
	{
		global $ctl;
		if($ctl){
			$re=$ctl->toDTMLUI($re,'value');
		}
		return $re;
	}
	
}
?>