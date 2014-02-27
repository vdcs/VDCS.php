<?
//define('PATTERN__SPACE',			'.*?');
//define('PATTERN__SPACES',			'.*?\s+');
//define('PATTERN__QMARK',			'[\"|\']?');
class VDCSDTML		//CommonDTML
{
	const PATTERN_PARAM				= '(!([\w-\.\:][^!]+?))';
	const PATTERN_DTML_PRE			= '/<pre:([^<>]*)>/ies';
	const PATTERN_DTML_VAR			= '/<var:([^<>]*)>/ies';
	const PATTERN_DTML_VARS			= '/<var:([^<>!]*)(!([\w-\.][^!]+?))?(!([\w-\.][^!]+?))?>/ies';
	const PATTERN_DTML_DAT			= '/<dat:([^<>]*)>/ies';
	const PATTERN_DTML_DATS			= '/<dat:([^<>!]*)(!([\w-\.][^!]+?))?(!([\w-\.][^!]+?))?>/ies';
	const PATTERN_DTML_DATA			= '/<data:([^<>]*)>/ies';
	const PATTERN_DTML_DATAS		= '/<data:([^<>!]*)(!([\w-\.][^!]+?))?(!([\w-\.][^!]+?))?>/ies';
	const PATTERN_DTML_DTML			= '/<dtml:([^<>]*)>/ies';
	const PATTERN_DTML_DTMLS		= '/<dtml:([^<>!]*)(!([\w-\.][^!]+?))?(!([\w-\.][^!]+?))?>/ies';
	const PATTERN_DTML_KEY			= '/<{$key}:([^\[\]][\w-\.]+?)>/ies';
	const PATTERN_DTML_KEYS			= '/<{$key}:([^\[\]][\w-\.]+?)(!([\w-\.\:][^!]+?))?(!([\w-\.\:][^!]+?))?>/ies';
	const PATTERN_DTML_ITEM			= '/\[item:([^\[\]][\w-\.]+?)\]/ies';
	//const PATTERN_DTML_ITEMS		= '/\[item:([^\[\]][\w-\.]+?)(!([\w-\.\:][^!]+?))?(!([\w-\.\:][^!]+?))?\]/ies';
	const PATTERN_DTML_ITEMS		= '/\[item:([\w\-\_\.\:]*)(!([\w-\.\:]+?))?(!([\w-\.\:]+?))?\]/ies';		// ??? relate vdcs pattern
	const PATTERN_DTML_ITEM_KEY		= '/\[{$key}:([^\[\]][\w-\.]+?)\]/ies';
	const PATTERN_DTML_ITEMS_KEY		= '/\[{$key}:([^\[\]][\w-\.]+?)(!([\w-\.\:][^!]+?))?(!([\w-\.\:][^!]+?))?\]/ies';
	
	const PATTERN_DTML_DCS			= '/<dcs:([^<>]*)>/ies';
	const PATTERN_DTML_APP			= '/<app:([^<>]*)>/ies';
	
	const PATTERN_DTML_EXEC			= '/<exec:([\w-\.:][^"]*)\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>/ies';
	
	/* apps
	const PATTERN_DTML_FUNC			= '/<func:([\w-\.:][^"]*)\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>/ies';
	const PATTERN_DTML_INCLUDE_FILE		= '/<label:include file=\"(.[^\"]*)\">/ies';
	const PATTERN_DTML_INCLUDE_MODEL_FILE	= '/<label:include model=\"{$model}\" file=\"(.[^\"]*)\">/ies';
	const PATTERN_DTML_CONTROL		= '/<control:([\w-\.:][^"]*)\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>/ies';
	const PATTERN_DTML_CONTROL_UI		= '/<control:ui.([\w-\.:][^"]*)\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?(,\"(.[^\"]*)\")?\)>/ies';
	
	const PATTERN_DTML_PRE_CONFIG		'/\{\#config:([\w\-\.]*)?\(\"(.[^\"]*)\"(,\"(.[^\"]*)\")?\)\#\}/ies';
	*/
	
	const URL_DEFAULT			= 'default';
	const URL_SCRIPT			= 'script';
	
	
	public static function toParseV($re)
	{
		$re=rv($re,'year',DCS::year());
		return $re;
	}
	public static function toParseApp($re)
	{
		global $_cfg;
		$re=self::toParseV($re);
		$re=preg_replace('/<app:'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'$1\']',$re);
		$re=preg_replace('/<app:'.PATTERN_FLAG_VAR.'\.'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'$1.$2\']',$re);
		$re=preg_replace('/<url:'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'url.$1\']',$re);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	// config
	public static function toConfigContent($xml){return self::toParseApp($xml);}
	public static function toConfigTree($xml,$k='',$v=''){return getXCML2Tree(self::toConfigContent($xml),$k,$v);}
	public static function toConfigTable($xml){return getXCML2Table(self::toConfigContent($xml));}
	
	public static function getConfigContent($f){return self::toParseApp(getFileContent(appFilePath($f)));}
	public static function getConfigTree($f,$k='',$v=''){return getXCML2Tree(self::getConfigContent($f),$k,$v);}
	public static function getConfigTable($f){return getXCML2Table(self::getConfigContent($f));}
	
	public static function getConfigxTree($f,$m=1,$k='',$v='')
	{
		$reTree=self::getConfigTree($f,$k,$v);
		if($m){
			$reTree->doAppendTree(self::getConfigTree($f.'@'.DCS::serverString(),$k,$v));
			$reTree->doAppendTree(self::getConfigTree($f.'@'.DCS::browseDomain(),$k,$v));
		}
		return $reTree;
	}
	
	public static function getConfigCacheTree($rfile,$reTree=null)
	{
		global $_cfg,$_VCACHE;
		$cfile=_BASE_PATH_ROOT.$_cfg['sys.dir']['data.cache'].'config/'.r($rfile,'/','__').EXT_CACHE;
		if(@is_file($cfile) && @filemtime($cfile)>@filemtime(appFilePath($rfile))){
			include_once($cfile);
			$reTree=new utilTree();
			if(is_array($_VCACHE[$rfile])) $reTree->setArray($_VCACHE[$rfile]);
		}
		else{
			if($reTree==null) $reTree=self::getConfigTree($rfile);
			VDCSCache::doUpdateTree($rfile,$reTree,$cfile);
		}
		return $reTree;
	}
	public static function getConfigCacheTable($rfile,$reTree=null)
	{
		global $_cfg,$_VCACHE;
		$cfile=_BASE_PATH_ROOT.$_cfg['sys.dir']['data.cache'].'config/'.r($rfile,'/','__').EXT_CACHE;
		if(@is_file($cfile) && @filemtime($cfile)>@filemtime(appFilePath($rfile))){
			include_once($cfile);
			$reTree=new utilTable();
			if(is_array($_VCACHE[$rfile])) $reTree->setArray($_VCACHE[$rfile]);
		}
		else{
			if($reTree==null) $reTree=self::getConfigTable($rfile);
			VDCSCache::doUpdateTable($rfile,$reTree,$cfile);
		}
		return $reTree;
	}
	
	public static function doConfigCacheTreeBrush($s,$o=null)
	{
		global $_cfg,$_VCACHE;
		$f=_BASE_PATH_ROOT.$_cfg['sys.dir']['data.cache'].'config/'.r($s,'/','__').EXT_CACHE;
		VDCSCache::doUpdateTree($s,$o,$f);
		utilFile::doFileCreate(appFilePath($s),VDCSXCML::toStringbyTree($o));
	}
	public static function doConfigCacheTableBrush($s,$o=null)
	{
		global $_cfg,$_VCACHE;
		$f=_BASE_PATH_ROOT.$_cfg['sys.dir']['data.cache'].'config/'.r($s,'/','__').EXT_CACHE;
		VDCSCache::doUpdateTable($s,$o,$f);
		utilFile::doFileCreate(appFilePath($s),VDCSXCML::toStringbyTable($o));
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toReplaceEncodeFilter($re,$strTree,$pattern='')
	{
		if(!$pattern) $pattern=self::PATTERN_DTML_ITEMS;
		//####################
		$_matches=utilRegex::toMatches($re,$pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagOption=$_matches[3][$m];
			$rFlagOption2=$_matches[5][$m];		//if(count($_matches)>5)
			$rFlagValue=$strTree->getItem($_matches[1][$m]);
			if(len($rFlagOption)>0) $rFlagValue=self::toEncodeFilterValue($rFlagValue,$rFlagOption,$rFlagOption2);
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unset($_matches);
		//####################
		return $re;
	}
	
	public static function toReplaceItems($re,$strTree,$pattern='')
	{
		if(!$pattern) $pattern=self::PATTERN_DTML_ITEMS;
		//####################
		$_matches=utilRegex::toMatches($re,$pattern);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagOption=$_matches[3][$m];
			$rFlagOption2=$_matches[5][$m];		//if(count($_matches)>5)
			$rFlagValue=$strTree->getItem($_matches[1][$m]);
			if(len($rFlagOption)>0) $rFlagValue=self::toEncodeFilterValue($rFlagValue,$rFlagOption,$rFlagOption2);
			$re=r($re,$_matches[0][$m],$rFlagValue);
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
	public static function toSwapFlag($s,$strPattern,$strFlag){return preg_replace($strPattern,'r(\$strFlag,SWAP_SYMBOL,\'$1\')',$s);}
	
	public static function toFlagSwap($re,$pattern,$strFlag,$isReplace=0)
	{
		$_matches=utilRegex::toMatches($re,$pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			if($isReplace==1){
				$re=r($re,$_matches[0][$m],r($strFlag,SWAP_SYMBOL,$_matches[1][$m]));
			}
			else{
				$re=r($re,$_matches[0][$m],$strFlag);
			}
		}
		unset($_matches);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toParseItems(&$re,$items='',$pattern=PATTERN_VAR)
	{
		if($items) $re=utilRegex::toReplaceRegex($re,utilString::toTree($items,'&','='),$pattern);
		return $re;
	}
	
	public static function toParseAppStat($re)
	{
		$re=r($re,'<web:stat.exectime>',dcsExecTime());
		$re=r($re,'<web:stat.query>',DB::getTotal());
		$re=r($re,'<dcs:memory.usage>',dcsMemoryUsage());
		$re=r($re,'<dcs:gzip.status>',dcsGzipStatus());
		return $re;
	}
	
	public static function toParseDCS($s){return preg_replace(self::PATTERN_DTML_DCS,'self::getValueDCS(\'$1\')',$s);}
	public static function getValueDCS($t)
	{
		$re='';
		switch($t){
			case 'browse.paths'		: $re=DCS::browsePath(true); break;
			case 'time.now'			: $re=DCS::now(); break;
			case 'time.today'		: $re=DCS::today(); break;
			case 'time.timer'		: $re=DCS::timer(); break;
			case 'browse.url'		: $re=DCS::browseURL(); break;
			case 'browse.urls'		: $re=DCS::browseURL(true); break;
			case 'browse.file'		: $re=DCS::browseScript(); break;
			case 'browse.path'		: $re=DCS::browsePath(); break;
			case 'browse.ip'		: $re=DCS::ip(); break;
			case 'browse.port'		: $re=DCS::port(); break;
			case 'browse.agent'		: $re=DCS::agent(); break;
			case 'session.id'		: $re=DCS::sessionid(); break;
			case 'memory.usage'		: $re=dcsMemoryUsage(); break;
			case 'gzip.status'		: $re=dcsGzipStatus(); break;
		}
		return $re;
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public static function toEncodeFilterValue($s,$fmt,$params='')
	{
		if(!$fmt) return $s;
		$isv=true;
		$re=utilCode::toEncodeFilterValue($s,$fmt,$params,$isv);
		if(!$isv){
			$isv=true;
			switch($fmt){
				case 'upload':
					$re=CommonTheme::toUploadURL($s);
					break;
				case 'codes':
					$re=VDCSCodes::toCodes($s,$params);
					break;
				//case 'num':		$re=toNum($s); break;
				default :		$re=$s; $isv=false; break;
			}
		}
		if(!$isv) $re=VDCSCode::toEncodeFilterValue($s,$fmt,$params,$isv);
		return $re;
	}
	
}
?>