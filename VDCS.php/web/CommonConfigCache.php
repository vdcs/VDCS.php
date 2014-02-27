<?
class CommonConfigCache
{
	public static function toDTMLCachePre($re)
	{
		global $cfg;
		$re=r($re,'{@channel}',$cfg->getChannel());
		$re=r($re,'{@classid}',$cfg->clas?$cfg->clas->getID():0);
		$re=r($re,'{@specialid}',$cfg->spec?$cfg->spec->getID():0);
		//$re=CommonTheme::toCacheFilterVar($re,'{@classid}','cfg.clas.getID()');
		//$re=CommonTheme::toCacheFilterVar($re,'{@specialid}','cfg.spec.getID()');
		return $re;
	}
	
	public static function toDTMLCache($re)
	{
		global $cfg;
		//####################
		$re=self::toDTMLCacheDCS($re);
		//####################
		$re=preg_replace('/<config:dat\(\"'.PATTERN_FLAG_VAR.'\"\)>/s',CommonTheme::HTMLMarkHeads.'$cfg->getDat(\'$1\')'.CommonTheme::HTMLMarkFoot,$re);
		$re=preg_replace('/<config:data\(\"'.PATTERN_FLAG_VAR.'\"\)>/s',CommonTheme::HTMLMarkHeads.'$cfg->getData(\'$1\')'.CommonTheme::HTMLMarkFoot,$re);
		//####################
		$re=preg_replace('/<chn:(var|num|url)\(\"'.PATTERN_FLAG_VAR.'\"\)>/ies','\$cfg->getConfigValue(\'$1.$2\')',$re);
		$re=preg_replace('/<channel:(var|num|url)\(\"'.PATTERN_FLAG_VAR.'\"\)>/ies','\$cfg->getConfigValue(\'$1.$2\')',$re);
		//$re=preg_replace('/<channel:(var|num|url)\(\"'.PATTERN_FLAG_VAR.'\"\)>/s',CommonTheme::HTMLMarkHeads.'$cfg->getConfigValue(\'$1.$2\')'.CommonTheme::HTMLMarkFoot,$re);
		$re=preg_replace('/<channel:nav\(\"'.PATTERN_FLAG_PARAMS.'\"(,\"'.PATTERN_FLAG_PARAMS.'\")?\)>/s',CommonTheme::HTMLMarkHeads.'$cfg->getTitles(\'$1\',\'$3\')'.CommonTheme::HTMLMarkFoot,$re);
		$re=preg_replace('/<channel:stat\(\"'.PATTERN_FLAG_PARAMS.'\"(,\"'.PATTERN_FLAG_PARAMS.'\")?\)>/s',CommonTheme::HTMLMarkHeads.'$cfg->getStat(\'$1\',\'$3\')'.CommonTheme::HTMLMarkFoot,$re);
		//$re=preg_replace(VDCSDTML::PATTERN_DTML_PRE_CONFIG,'\$this->getConfigValue(\'$1\',\'$2\',\'$4\')',$re);
		//####################
		$_pattern='<chn:(var|num|url)\(\"'.PATTERN_FLAG_LABEL.'\",\"'.PATTERN_FLAG_LABEL.'\"(,\"(.|[^<>"]*)\")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagOption=$_matches[5][$m];
			if(len($rFlagOption)<1) $rFlagOption='configure';
			$rFlagValue=$cfg->getChannelValue($_matches[2][$m],$rFlagOption,$_matches[1][$m].'.'.$_matches[3][$m]);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<channel:(var|num|url)\(\"'.PATTERN_FLAG_LABEL.'\"\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$cfg->getConfigValue($_matches[1][$m].'.'.$_matches[2][$m]);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<linkurl:([^\.]*)\.([^<>]*)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$cfg->getLinkURL($_matches[1][$m],$_matches[2][$m],'');
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<chn:(linkurl)\(\"'.PATTERN_FLAG_LABEL.'\",\"'.PATTERN_FLAG_LABEL.'\"(,\"(.+?)\")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue=$cfg->getLinkURL($_matches[2][$m],$_matches[3][$m],$_matches[5][$m]);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		/*
		$_pattern='<channel:(nav|stat)\(\"'.PATTERN_FLAG_PARAMS.'\"(,\"'.PATTERN_FLAG_PARAMS.'\")?\)>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			if($_matches[1][$m]=='nav'){
				$rFlagValue=CommonTheme::HTMLMarkHead.'=cfg.getTitles("'.$_matches[2][$m].'","'.$_matches[4][$m].'")'.CommonTheme::HTMLMarkFoot;
			}
			else{
				$rFlagValue=CommonTheme::HTMLMarkHead.'=cfg.getStat("'.$_matches[2][$m].'")'.CommonTheme::HTMLMarkFoot;
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		*/
		//####################
		unset($_matches);
		//####################
		global $cfg;
		if($cfg->clas){
			$re=self::toDTMLCacheClass($re);
			if($cfg->clas->isUse()) $re=$cfg->clas->toDTML($re);
		}
		if($cfg->spec){
			$re=self::toDTMLCacheSpecial($re);
			if($cfg->spec->isUse()) $re=$cfg->spec->toDTML($re);
		}
		//if(($cfg->clas && $cfg->clas->isUse()) || ($cfg->spec && $cfg->spec->isUse())) 
		$re=CommonLabelCache::toDTMLCache($re);
		return $re;
	}
	
	public static function toDTMLCacheClass($re)
	{
		global $cfg;
		//####################
		if(!$cfg->clas) return $re;
		$_channel=$cfg->clas->getChannel();
		$tableChannel=$cfg->clas->getTable();
		$tableRoot=$cfg->clas->getTableRoot();
		//####################
		$_pattern='<loop:class.root'.PATTERN_FLAG_OPTION.'>'.PATTERN_FLAG_CONTENT.'<\/loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			if(len($_matches[2][$m])>0){
				$patternRoot=rd(VDCSDTML::PATTERN_DTML_ITEMS_KEY,'key',$_matches[2][$m]);
			}
			else{
				$patternRoot=VDCSDTML::PATTERN_DTML_ITEMS;
			}
			$rFlagValue='';
			$tableRoot->doItemBegin();
			for($t=0;$t<$tableRoot->getRow();$t++){
				$treeItem=$tableRoot->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				//$treeItem->addItem('url',$cfg->getLinkURL($_channel,'list','classid='.$treeItem->getItem('classid')));
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=VDCSDTML::toReplaceEncodeFilter($_matches[3][$m],$treeItem,$patternRoot);
				$tableRoot->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<loop:class>'.PATTERN_FLAG_CONTENT.'<\/loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue='';
			$tableChannel->doItemBegin();
			for($t=0;$t<$tableChannel->getRow();$t++){
				$treeItem=$tableChannel->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				//$treeItem->addItem('url',$cfg->getLinkURL($_channel,'list','classid='.$treeItem->getItem('classid')));
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=utilRegex::toReplaceRegex($_matches[1][$m],$treeItem,VDCSDTML::PATTERN_DTML_ITEM);
				$tableChannel->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unsetr($_matches);
		unsetr($tableChannel,$tableRoot);
		//####################
		$re=ModelClassCache::toDTMLCache($re);
		return $re;
	}
	
	public static function toDTMLCacheSpecial($re)
	{
		global $cfg;
		
		
		return $re;
	}
	
	
	/* ################################## */
	public static function toDTMLCacheDCS($re)
	{
		$_matches=utilRegex::toMatches($re,'<dcs:([^<>]*)>');
		for($m=0;$m<count($_matches[0]);$m++){
			switch($_matches[1][$m]){
				case 'browse.paths'		: $var='DCS::browsePath(true)'; break;
				case 'time.now'			: $var='DCS::now()'; break;
				case 'time.today'		: $var='DCS::today()'; break;
				case 'time.timer'		: $var='DCS::timer()'; break;
				case 'browse.url'		: $var='DCS::browseURL()'; break;
				case 'browse.urls'		: $var='DCS::browseURL(true)'; break;
				case 'browse.file'		: $var='DCS::browseScript()'; break;
				case 'browse.path'		: $var='DCS::browsePath()'; break;
				case 'browse.ip'		: $var='DCS::ip()'; break;
				case 'browse.port'		: $var='DCS::port()'; break;
				case 'browse.agent'		: $var='DCS::agent()'; break;
				case 'memory.usage'		: $var='dcsMemoryUsage()'; break;
				case 'gzip.status'		: $var='appv(\'app.gzip.status\')'; break;
				default				: $var='[dcs:'.$_matches[1][$m].']'; break;
			}
			$re=r($re,$_matches[0][$m],CommonTheme::HTMLMarkHead.$var.CommonTheme::HTMLMarkFoot);
		}
		//####################
		unsetr($_matches);
		//####################
		return $re;
	}

	
	/*
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	@@@@@@@@@@@@@ DTML Dispose @@@@@@@@@@@@@
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	*/
	public function toDTML($re)
	{
		$re=r($re,'{@channel}',$this->_channel);
		$re=preg_replace('/<channel:(var|num|url)(\"'.PATTERN_FLAG_VAR.'\")>/ies','$this->getConfigValue(\'$1.$2\')',$re);
		
		$re=preg_replace('/<channel:nav(\"'.PATTERN_FLAG_PARAMS.'\"(,\"'.PATTERN_FLAG_PARAMS.'\")?)>/ies','$this->getTitles(\'$1\',\'$3\')',$re);
		$re=preg_replace('/<channel:stat(\"'.PATTERN_FLAG_PARAMS.'\"(,\"'.PATTERN_FLAG_PARAMS.'\")?)>/ies','$this->getStat(\'$1\',\'$3\')',$re);
		
		if($this->_isClass) $re=$this->toDTMLClass($re);
		return $re;
	}
	
	public function toDTMLChannel($re)
	{
		$re=r($re,'{@channel}',$this->_channel);
		$re=preg_replace('/<channel:(var|num|url)(\"'.PATTERN_FLAG_VAR.'\")>/ies','$this->getConfigValue(\'$1.$2\')',$re);
		return $re;
	}
	
	public function toDTMLClass($re)
	{
		$tableClass=null;
		$n=preg_match_all('/<loop:class>'.PATTERN_FLAG_CONTENT.'<\/loop:class>/ies',$re,$__matches);
		for($i=0;$i<$n;$i++){
			$rFlagValue='';
			if($tableClass==null) $tableClass=$this->toClassTable($this->tableChannelClass);
			$tableClass->doItemBegin();
			for($t=0;$t<$tableClass->getRow();$t++){
				$tmpTree=$tableClass->getItemTree();
				$tmpTree->addItem("url",$this->toLinkURL("list","classid=".$tableClass->getItemValue("classid")));
				$rFlagValue.=(utils.toReplaceRegex($__matches[1][$i],$tmpTree,VDCSDTML::PATTERN_DTML_ITEM));
				$tableClass->doItemMove();
			}
			$re=r($re,$__matches[0][$i],$rFlagValue);
		}
		return $re;
	}

}
?>