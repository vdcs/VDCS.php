<?
class CommonTheme
{
	const HTMLMarkHead		= '<?';
	const HTMLMarkHeads		= '<?=';
	const HTMLMarkFoot		= '?>';
	
	const PATTERN_DTML_LABEL_TEMPLAT		= '<label:{$flag}>([\s\S.]*?)</label:end>';
	const PATTERN_DTML_LABEL_CLASS			= '<label:class\("([\w\-\.\:]*)","([\s\w-\.=;\'\(\)<>\[\]{\$}]*)"\)>([\s\S.]*?)<\/label:end>';
	const PATTERN_DTML_LABEL_BLOCK			= '<label:block\("([\w\-\.\:]*)","([\s\w-\.=;\'\(\)<>\[\]{\$}]*)"\)>([\s\S.]*?)<\/label:end>';
	const PATTERN_DTML_LABEL_LIST			= '<label:list>([\s\S.]*?)</label:end>';
	const PATTERN_DTML_LABEL_LIST_MODULE		= '<label:list.{$module}>([\s\S.]*?)</label:end>';
	const PATTERN_DTML_LABEL_DATA			= '<label:data>([\s\S.]*?)</label:end>';
	const PATTERN_DTML_LABEL_OBLOCK			= '<label:oblock\("([\w\-\.\:]*)","([\s\w-\.=;\'\(\)<>\[\]{\$}]*)"\)>([\s\S.]*?)<\/label:end>';
	const PATTERN_DTML_INCLUDE_FILE			= '<label:include file=\"(.[^\"]*)\">';
	
	
	public static $CONTENTTYPE=array(
			'html'=>CONTENT_TYPE_HTML,
			'txt'=>CONTENT_TYPE_TXT,
			'xml'=>CONTENT_TYPE_XML,
			'wml'=>CONTENT_TYPE_WML,
			'json'=>CONTENT_TYPE_JSON,
			'js'=>CONTENT_TYPE_JS,
			'css'=>CONTENT_TYPE_CSS
	);
	public static function toContentType($t='html'){return self::$CONTENTTYPE[$t];}
	
	
	public static function toExpression($exps)
	{
		$exps=r($exps,'=','==');
		$exps=r($exps,'====','==');
		$exps=r($exps,'<>','!=');
		$exps=r($exps,'not ','!');
		return $exps;
	}
	
	public static function toVarArray($opre)
	{
		$arypre=explode('.',$opre);
		$re=array($arypre[0]);
		for($a=1;$a<count($arypre);$a++){
			$re=array($re,$arypre[$a]);
		}
		return $re;
	}
	public static function toVarCache($v,$obj=0)
	{
		if(left($v,1)!='$') $v='$'.$v;
		$v=str_replace(substr($v,1,-1),str_replace('.','->',substr($v,1,-1)),$v);
		$v=str_replace('$$','$',$v);
		if($obj==1){
			if(right($v,1)=='.') $v=substr($v,0,-1).'->';
			if(right($v,2)!='->') $v.='->';
		}
		return $v;
	}
	public static function toVarParams($v,$quote=1,$t=1)
	{
		if(left($v,2)!='\'.' && right($v,2)!='.\''){
			if($t==0){
				if(left($v,1)=='$'){
					$quote=0;
					$t=0;
				}
			}
			if($t==1){
				$v=str_replace('\'','\\\'',$v);
			}
		}
		if($quote==1) $v='\''.$v.'\'';
		return $v;
	}
	public static function toVarFilter($re,$s,$v) { return r($re,$s,HTMLMarkHead.'='.$v.HTMLMarkFoot); }
	
	public static function toEncodeFilterValue($type,$s,$fmt,$params)
	{
		if($fmt) $re='VDCSDTML::toEncodeFilterValue('.$s.','.self::toVarParams($fmt).','.self::toVarParams($params).')';
		else $re=$s;
		/*
		switch($fmt){
			case 'codes'	: $re='$theme->toCodes('.$s.','.self::toVarParams($params).')'; break;
			default		:
				if(len($fmt)>0) $re='VDCSDTML::toEncodeFilterValue('.$s.','.self::toVarParams($fmt).','.self::toVarParams($params).')';
				else $re=$s;
				break;
		}
		*/
		if($type==1) $re=self::HTMLMarkHeads.$re.self::HTMLMarkFoot;
		return $re;
	}
	
	public static function toPatternLoop($k) { return '<loop:('.$k.')>'.PATTERN_FLAG_CONTENT.'<\/loop>'; }
	
	
	public static function toDTMLCache($re)
	{
		$re=str_replace('\'','\\\'',$re);		//单引号
		//$re=str_replace('"','"',$re);			//双引号
		$re=str_replace(NEWLINE,'\'.NEWLINE.\'',$re);	//大换行
		return $re;
	}
	public static function toDTMLCacheValue($re,$t=0)
	{
		$re=self::toDTMLCache($re);
		switch($t){
			case 1:		$re='\''.$re.'\'';;break;
			case 2:		$re=HTMLMarkHead.$re.HTMLMarkFoot;break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function doItemAppend(&$treeItem,$t)
	{
		$treeItem->addItem('sn',$t);
		$treeItem->addItem('oe',((($t-1)%2)+1));
	}
	
	public static function toUploadURL($re,$def_='no_pic.gif')
	{
		if(!$re) $re=$def_?$def_:'no_pic.gif';
		if(ins($re,'://')<1 && left($re,1)!='/'){
			if(left($re,7)=='upload/') $re=substr($re,7);
			if(left($re,8)=='/upload/') $re=substr($re,8);
			$re=appURL('upload').$re;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toCacheFilterVar($re,$k,$v='')
	{
		if(!$v) $v=$k;
		$v=self::toVarCache($v);
		$re=r($re,$k,self::HTMLMarkHeads.$v.self::HTMLMarkFoot);
		return $re;
	}
	
	public static function toCacheFilterTree($re,$k,$v='',$func='')
	{
		if(!$v) $v=$k;
		$v=self::toVarCache($v,1);
		if(!$func) $func='getItem';
		//####################
		$_pattern='<'.$k.':'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($i=0;$i<count($_matches[0]);$i++){
			$rFlagValue=$v.$func.'('.self::toVarParams($_matches[1][$i]).')';
			$rFlagOption=$_matches[3][$i];
			if(len($rFlagOption)>0) $rFlagValue=self::toEncodeFilterValue(0,$rFlagValue,$rFlagOption,$_matches[5][$i]);
			$re=r($re,$_matches[0][$i],self::HTMLMarkHeads.$rFlagValue.self::HTMLMarkFoot);
		}
		//####################
		$_pattern='{(\@|\$|\$\$)'.$k.'(:|\.)'.PATTERN_FLAG_STR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($i=0;$i<count($_matches[0]);$i++){
			if($_matches[2][$i]=='.'){
				$rFlagValue=$v.$_matches[3][$i];
			}
			else{
				$rFlagValue=$v.$func.'('.self::toVarParams($_matches[3][$i]).')';
			}
			$rFlagOption=$_matches[5][$i];
			if(len($rFlagOption)>0) $rFlagValue=self::toEncodeFilterValue(0,$rFlagValue,$rFlagOption,$_matches[7][$i]);
			switch($_matches[1][$i]){
				case '@':	$rFlagValue=self::HTMLMarkHeads.$rFlagValue.self::HTMLMarkFoot; break;
				case '$$':	$rFlagValue='\'.'.$rFlagValue.'.\''; break;
				default:	$rFlagValue=$rFlagValue; break;
			}
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
	public static function toCacheFilterLoop($re,$k,$v,$funcAry='') { return self::toTransactLoop($re,self::toPatternLoop($k),$v,$k,$funcAry); }
	
	
	public static function toTransactLoop($re,$pattern,$nTable='',$nTree='',$funcAry='')
	{
		if(!isa($funcAry)) $funcAry=array('templat'=>$funcAry);
		//debugx($nTable.'--'.$nTree.'--'.$pattern);
		//debuga($funcAry);
		$fTable=$nTable;$fTree=$nTree;
		$_matches=utilRegex::toMatches($re,$pattern);
		//debuga($_matches);
		for($i=0;$i<count($_matches[0]);$i++){
			if(count($_matches)>3){
				$fTable=$_matches[1][$i];
				$fTree=$_matches[2][$i];
				$tmpBody=$_matches[3][$i];
			}
			else{
				if(len($fTree)<1) $fTree=$_matches[1][$i];
				if(len($fTable)<1) $fTable=$_matches[1][$i];
				$tmpBody=$funcs['templat'];
				if(len($tmpBody)<1) $tmpBody=$_matches[2][$i];
			}
			$fTable=self::toVarCache($fTable);
			$tmpBody=trim($tmpBody);
			//if(isa($funcAry['templat.filter'])) $tmpBody=call_user_func_array($funcAry['templat.filter'],array($tmpBody));
			$kMD5=utilCoder::toMD5($pattern.','.$nTable.','.$nTree.','.time()).($i+1);
			$kTable='dtl_ts'.$kMD5.'_';$kTree='dtl_t'.$kMD5.'_'; $vTable=self::toVarCache($kTable,1);$vTree=self::toVarCache($kTree,1);
			$rFlagValue=self::HTMLMarkHead;
			$rFlagValue.=NEWLINE.'if(isTable('.$fTable.')){';
			$rFlagValue.=NEWLINE.self::toVarCache($kTable,0).'='.$fTable.';'.$vTable.'doBegin();';
			$rFlagValue.=NEWLINE.'while('.$vTable.'isNext()){';
			$rFlagValue.=self::toVarCache($kTree,0).'='.$vTable.'getItemTree(1);';
			if($funcAry['item.func']) $rFlagValue.=self::toVarCache($kTree,0).'='.CommonTheme::toVarCache($funcAry['item.func']).'('.self::toVarCache($kTree,0).')';
			$rFlagValue.=self::HTMLMarkFoot.NEWLINE;
			$_matches2=utilRegex::toMatches($re,'\[item:'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'\]');
			//debugAry($_matches2);
			for($i2=0;$i2<count($_matches2[0]);$i2++){
				$rFlagValue2=$vTree.'getItem('.self::toVarParams($_matches2[1][$i2]).')';
				if(len($_matches2[3][$i2])>0) $rFlagValue2=self::toEncodeFilterValue(0,$rFlagValue2,$_matches2[3][$i2],$_matches2[5][$i2]);
				$tmpBody=r($tmpBody,$_matches2[0][$i2],self::HTMLMarkHeads.$rFlagValue2.self::HTMLMarkFoot);
			}
			$_matches2=utilRegex::toMatches($re,'{@'.$fTree.':'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}');
			for($i2=0;$i2<count($_matches2[0]);$i2++){
				$rFlagValue2=$vTree.'getItem('.self::toVarParams($_matches2[1][$i2]).')';
				if(len($_matches2[3][$i2])>0) $rFlagValue2=self::toEncodeFilterValue(0,$rFlagValue2,$_matches2[3][$i2],$_matches2[5][$i2]);
				$tmpBody=r($tmpBody,$_matches2[0][$i2],self::HTMLMarkHeads.$rFlagValue2.self::HTMLMarkFoot);
			}
			$_matches2=utilRegex::toMatches($re,'{\$'.$fTree.':'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}');
			for($i2=0;$i2<count($_matches2[0]);$i2++){
				$rFlagValue2=$vTree.'getItem('.self::toVarParams($_matches2[1][$i2]).')';
				if(len($_matches2[3][$i2])>0) $rFlagValue2=self::toEncodeFilterValue(0,$rFlagValue2,$_matches2[3][$i2],$_matches2[5][$i2]);
				$tmpBody=r($tmpBody,$_matches2[0][$i2],$rFlagValue2);
			}
			$_matches2=utilRegex::toMatches($re,'{\$\$'.$fTree.':'.PATTERN_FLAG_VAR.PATTERN_FLAG_OPTION.PATTERN_FLAG_OPTION.'}');
			for($i2=0;$i2<count($_matches2[0]);$i2++){
				$rFlagValue2=$vTree.'getItem('.self::toVarParams($_matches2[1][$i2]).')';
				if(len($_matches2[3][$i2])>0) $rFlagValue2=self::toEncodeFilterValue(0,$rFlagValue2,$_matches2[3][$i2],$_matches2[5][$i2]);
				$tmpBody=r($tmpBody,$_matches2[0][$i2],'\'.'.$rFlagValue2.'.\'');
			}
			$rFlagValue.=$tmpBody;
			$rFlagValue.=NEWLINE.self::HTMLMarkHead.'}}'.self::HTMLMarkFoot;
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
	
	public static function toTransactor($re)
	{
		//####################		   func replace
		$_matches=utilRegex::toMatches($re,'<@replace\(\"'.PATTERN_FLAG.'\",\"'.PATTERN_FLAG.'\"\)>');
		for($i=0;$i<count($_matches[0]);$i++){
			$rFlagValue=$_matches[1][$i];
			$treeTerm=utilString::toTree($_matches[2][$i],';','=');
			$treeTerm->doBegin();
			for($t=0;$t<$treeTerm->getCount();$t++){
				$rFlagValue=r($rFlagValue,$treeTerm->getItemKey(),$treeTerm->getItemValue());
				$treeTerm->doMove();
			}
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		$re=self::toTransactLoop($re,'<!--{loops '.PATTERN_FLAG_STR.' as '.PATTERN_FLAG_VAR.'}-->'.PATTERN_FLAG_CONTENT.'<!--{\/loops}-->');
		$re=self::toTransactLoop($re,'<!--{loop '.PATTERN_FLAG_STR.' as '.PATTERN_FLAG_VAR.'}-->'.PATTERN_FLAG_CONTENT.'<!--{\/loop}-->');
		//####################
		$_matches=utilRegex::toMatches($re,'<!--{if (.+?)}-->');
		for($i=0;$i<count($_matches[0]);$i++){
			//debugx(self::toVarCache(self::toExpression($_matches[1][$i])));
			$express=$_matches[1][$i];
			$express=r($express,'isBool','');
			$rFlagValue=self::HTMLMarkHead.'if('.self::toExpression($express).'){'.self::HTMLMarkFoot;
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<!--{elseif (.+?)}-->');
		for($i=0;$i<count($_matches[0]);$i++){
			$express=$_matches[1][$i];
			$express=r($express,'isBool','');
			$rFlagValue=self::HTMLMarkHead.'elseif('.self::toExpression($express).'){'.self::HTMLMarkFoot;
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<!--{else}-->');
		for($i=0;$i<count($_matches[0]);$i++){
			$rFlagValue=self::HTMLMarkHead.'}else{'.self::HTMLMarkFoot;
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		$_matches=utilRegex::toMatches($re,'<!--{\/if}-->');
		for($i=0;$i<count($_matches[0]);$i++){
			$rFlagValue=self::HTMLMarkHead.'}'.self::HTMLMarkFoot;
			$re=r($re,$_matches[0][$i],$rFlagValue);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toCachePaging($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='p';
		$opre=CommonTheme::toVarCache($oprefix);$oprefix=CommonTheme::toVarCache($oprefix,1);
		if(ins($re,'<paging:')>0) $re=r($re,'<paging:string>',CommonTheme::HTMLMarkHead.'if('.$opre.') echo '.$oprefix.'toString()'.CommonTheme::HTMLMarkFoot);
		if(ins($re,'<paging:')>0){
			$re=r($re,'<paging:listnum>',CommonTheme::HTMLMarkHeads.$oprefix.'getListNum()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:numend>',CommonTheme::HTMLMarkHeads.$oprefix.'getNumEnd()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:total>',CommonTheme::HTMLMarkHeads.$oprefix.'getTotal()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:pagenum>',CommonTheme::HTMLMarkHeads.$oprefix.'getPageNum()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:page>',CommonTheme::HTMLMarkHeads.$oprefix.'getPage()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:pagetotal>',CommonTheme::HTMLMarkHeads.$oprefix.'getPageTotal()'.CommonTheme::HTMLMarkFoot);
			$re=r($re,'<paging:pagebase>',CommonTheme::HTMLMarkHeads.$oprefix.'getPageBase()'.CommonTheme::HTMLMarkFoot);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toParseInclude($s,$basePath,$real=true)
	{
		//VDCSDTML::PATTERN_DTML_INCLUDE_FILE
		return preg_replace('/'.self::PATTERN_DTML_INCLUDE_FILE.'/ies','self::getIncludeFile(\'$1\',$basePath,$real,\'$0\')',$s);
	}
	public static function toParseIncludeTree($s,$treeInclude)
	{
		return utilRegex::toDisplaceRegex($s,$treeInclude,self::PATTERN_DTML_INCLUDE_FILE);
	}
	public static function getIncludeFile($file,$basePath,$real=true,$tags)
	{
		//debugx($file.'--'.$basePath);
		//debugx(self::getIncludeFilePath($file,$basePath));
		$re=getFileContent(self::getIncludeFilePath($file,$basePath));
		if(len($re)<1){
			$re=$real?('<!-- '.toPathRel(isa($basePath)?$basePath['p1']:$basePath).''.appExt($file,EXT_CONFIG).' -->'):r($tags,'\\"','"');
		}
		return $re;
	}
	
	public static function getIncludeFilePath($s,$basePath)	// ??? PATH_SYMBOL
	{
		$re=$s;
		if(!isRealPath($re)){
			$filename=appExt($s,EXT_CONFIG);
			if(!isa($basePath)){
				$basePath['p']=$basePath;
			}
			foreach($basePath as $k=>$v){
				$re=$v.$filename;
				if(isFile($re)) break;
			}
			/*
			if(ins($basePath,PATH_SYMBOL)>0) $re=$basePath.$s;	 //绝对基地址
			else{
				if(len($basePath)>0 && ins($re,DIR_SEPARATOR)<1) $re=$basePath.DIR_SEPARATOR.$re;
				$re=appFilePath($re);
			}
			$re=appExt($re,EXT_CONFIG);
			*/
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toParseControl($re)
	{
		global $ctl;
		//####################
		$_matches=utilRegex::toMatches($re,'<control:([\w-\.\:][^"]*)\(\"(.+?)\"'.PATTERN_FLAG_PARAMQ.PATTERN_FLAG_PARAMQ.PATTERN_FLAG_PARAMQ.'\)>');
		for($mi=0;$mi<count($_matches[0]);$mi++){
			$tmpParam[0]=$_matches[0][$mi];
			$tmpParam[1]=$_matches[1][$mi];
			$tmpParam[2]=$_matches[2][$mi];
			$tmpParam[3]=$_matches[4][$mi];
			$tmpParam[4]=$_matches[6][$mi];
			$tmpParam[5]=$_matches[8][$mi];
			if($tmpParam[3]=='__value'){
				$tmpParam[3]='';
				$_n=preg_match_all("/(.[^\.\s]*)\.(.[^\$\s]*)(\$\$\$(.[^\$\s]*))?/ies",$tmpParam[2],$_matches);
				if($_n && strlen($_matches[2][$mi])>0) $tmpParam[3]=$ctl->treeData->getItem($_matches[2][$mi]);
			}
			//debuga($tmpParam);
			$re=r($re,$tmpParam[0],self::getFuncValueParam('',$tmpParam[1],$tmpParam[2],$tmpParam[3],$tmpParam[4],$tmpParam[5]));
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
	public static function getFuncValueParam($s0,$s1,$s2,$s3,$s4,$s5) { return self::getFuncValue(array($s0,$s1,$s2,$s3,$s4,$s5)); }
	public static function getFuncValue($arParam)
	{
		$re='';
		switch($arParam[1]){
			case 'att':	if(count($arParam)>3) $re=PagesCommon::getAtt($arParam[2],$arParam[3]); break;
			case 'dict':	if(count($arParam)>3) $re=PagesCommon::getDict($arParam[2],$arParam[3]); break;
			case 'form.element':
				if(count($arParam)>4){
					if(count($arParam)>5 && $arParam[5]) $arParam[4]=PagesCommon::getAtt($arParam[4],$arParam[5]);
					$re=PagesCommon::getFormElement($arParam[2],$arParam[3],$arParam[4]);
				}
				break;
			default:	$re=$arParam[0]; break;
		}
		return $re;
	}
	
	
	
	
	/*
	########################################
	########################################
	*/
	public static function toReplaceVar($strer,$strTree) { return self::toReplaceEncodeFilter($strer,$strTree,VDCSDTML::PATTERN_DTML_VARS); }
	public static function toReplaceDat($strer,$strTree) { return self::toReplaceEncodeFilter($strer,$strTree,VDCSDTML::PATTERN_DTML_DATS); }
	public static function toReplaceData($strer,$strTree) { return self::toReplaceEncodeFilter($strer,$strTree,VDCSDTML::PATTERN_DTML_DATAS); }
	public static function toReplaceDTML($strer,$strTree) { return self::toReplaceEncodeFilter($strer,$strTree,VDCSDTML::PATTERN_DTML_DTMLS); }
	
	public static function toReplaceEncodeFilter($strer,$strTree,$strPattern) { return preg_replace($strPattern,'CommonTheme::toEncodeFilterValue(0,$strTree->getItem(\'$1\'),\'$2\',\'$4\')',$strer); }
	
	public static function toReplaceTable($re,$strTable,$sPattern)
	{
		$_mn=preg_match_all($sPattern,$re,$_matches);		//'/<loop:list>'.PATTERN_FLAG_CONTENT.'<\/loop:list>/ies'
		for($_mi=0;$_mi<$_mn;$_mi++){
			$re=r($re,$_matches[0][$_mi],self::toParseTableItems($_matches[1][$_mi],$strTable));
		}
		unset($_matches);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toFlagEncode($s)
	{
		$re=$s;
		$re=self::toSwapFlag(re,"{\$([^{\$}]*)}","{#".PATTERN_SYMBOL_SWAP."}");
		$re=self::toSwapFlag(re,"{\$=([^{\$=}]*)\$}","{#=".PATTERN_SYMBOL_SWAP."#}");
		return $re;
	}
	
	public static function toFlagDecode($s)
	{
		$re=$s;
		$re=self::toSwapFlag(re,"{\#([^{\#}]*)}","{\$".PATTERN_SYMBOL_SWAP."}");
		$re=self::toSwapFlag(re,"{\#=([^{\#=}]*)\#}","{\$=".PATTERN_SYMBOL_SWAP."$}");
		return $re;
	}
	
}
?>