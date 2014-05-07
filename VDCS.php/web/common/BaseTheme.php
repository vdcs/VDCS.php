<?
defined('THEME_DEFAULT')		|| define('THEME_DEFAULT','default');
define('THEME_DEFAULT_DIR',THEME_DEFAULT?THEME_DEFAULT.'/':'');
defined('APP_THEMER')			|| define('APP_THEMER','html5');
define('APP_THEMER_DIR',APP_THEMER?APP_THEMER.'/':'');

class BaseTheme
{
	public $_var=array(),$_path=array(),$_def=array(),$_pre=array(),$_web=array();
	public $output='';
	
	protected static $ThemeDefault			= THEME_DEFAULT;
	protected static $ThemeDefaultDir		= THEME_DEFAULT_DIR;
	protected static $MessageTitle			= 'Theme Parse Error';
	
	protected static $FormatKey			= 'fmt';
	protected static $FormatSymbol			= '!';
	protected static $FormatXML			= 'map,xmls,xml,rss';
	
	public function __construct()
	{
		$this->_var['_mode']		= 'value';	// include basepath
		$this->_var['channel']		= '';
		$this->_var['dir']		= '';
		$this->_var['page']		= '';
		$this->_var['module']		= '';
		$this->_var['modulei']		= '';
		$this->_var['child']		= '';
		$this->_var['format']		= '';
		$this->_var['ext']		= EXT_TEMPLATE;
		$this->_var['content.type']	= CONTENT_TYPE;
		$this->_var['content.type.v']	= CONTENT_TYPE;
		$this->_var['content.charset']	= CHARSET_HTML;
		// define
		$this->_var['themedef']		= self::$ThemeDefault;
		$this->_var['themedef.dir']	= self::$ThemeDefaultDir;
		// app
		$this->_var['themeapp']		= APP_THEMER;
		$this->_var['themeapp.dir']	= APP_THEMER_DIR;
		// stance
		$this->_var['themes']		= self::$ThemeDefault;
		$this->_var['themes.dir']	= self::$ThemeDefaultDir;
		//now
		$this->_var['theme']		= self::$ThemeDefault;
		$this->_var['theme.dir']	= self::$ThemeDefaultDir;
		
		$this->_var['error.show']	= true;
		$this->_var['error.is']		= false;
		$this->_var['error.status']	= '';
	}
	
	public function __destruct()
	{
		unset($this->_var,$this->_path,$this->_def,$this->_pre,$this->_web);
		unset($this->output);
	}
	
	
	public function isError(){return $this->_var['error.is'];}
	public function setErrorShow($v){$this->_var['error.show']=$v;}
	public function getErrorStatus(){return $this->_var['error.status'];}
	
	
	public function setMode($t){$this->_var['_mode']=$t;}
	public function setCache($t){$this->_var['_cache']=$t;}
	
	public function getVar($k){return $this->_var[$k];}
	public function setVar($k,$v){$this->_var[$k]=$v;}
	
	public function getPath($k){return $this->_path[$k];}
	public function setPath($k,$v){$this->_path[$k]=$v;$this->_var['path.'.$k]=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function setDefine()
	{
		global $_cfg;
		$_servetype='server';$_cache=1;
		if(DCS::isLocal()){
			$_servetype='local';
			$_cache=3;
		}
		$this->_web['var.serve']	= $_servetype;
		//debugx($_cache);
		$this->_var['serve.type']	= $_servetype;
		if(empty($_cfg['app']['app.theme.cache'])) $_cfg['app']['app.theme.cache']=$_cache;
		/*if(!isset($this->_var['_module'])){	//模式 0 1 2 3
			if(strlen($_cfg['app']['app.theme.module'])<1) $_cfg['app']['app.theme.module']='1';
			$this->_var['_module']=intval($_cfg['app']['app.theme.module']);
		}*/
		if(!isset($this->_var['_cache'])){	//缓存 0=不 1-2=缓存解析 3=实时解析
			$this->_var['_cache']=intval($_cfg['app']['app.theme.cache']);
		}
		!$this->_var['themedef.path'] && $this->_var['themedef.path']	= $this->_var['themes.path'].$this->_var['themedef.dir'];
	}
	
	public function setTheme($s)
	{
		$this->_web['url.themeb']	= $this->_var['themes.url'];
		$this->_var['theme']		= $s;
		$this->_var['theme.dir']	= $this->toThemeDir($s);
		$this->_var['theme.path']	= $this->_var['themes.path'].$this->_var['theme.dir'];
		$this->_web['theme']		= $this->_var['theme'];
		$this->_web['url.theme']	= $this->_var['themes.url'].$this->_var['theme.dir'];
		// default
		$this->_var['themed']		= $this->_var['themedef'];
		$this->_var['themed.dir']	= $this->toThemeDir($this->_var['themed']);
		$this->_web['url.themed']	= $this->_var['themes.url'].$this->_var['themed.dir'];
		// stance
		$this->_var['themes']		= $this->_var['theme'];		//($this->_var['_module']==1)?$this->_var['theme']:$this->_var['themedef'];
		$this->_var['themes.dir']	= $this->toThemeDir($this->_var['themes']);
		$this->_web['url.themes']	= $this->_var['themes.url'].$this->_var['themes.dir'];
		//debuga($this->_var);
		//debugx('setTheme');
		//debuga($this->_web);
	}
	public function getTheme(){return $this->_var['theme'];}
	public function setThemex($s)
	{
		$this->_var['themex']		= $s;
		$this->_var['themex.dir']	= $this->toThemeDir($this->_var['themex']);
	}
	
	
	public function setContentType($t)
	{
		$re=CommonTheme::toContentType($t);
		if($re){
			$this->_var['content.type']=$re;
			$this->_var['content.type.v']=$re;
		}
	}
	public function setContentCharset($s){if($s) $this->_var['content.charset']=$s;}
	
	public function setChannel($s,$t=0)
	{
		if(!$s&&$t==0) return;
		$this->_var['dir']		= $s;
		$this->_var['channel']		= $s;
		$this->_pre['channel']		= $s;
		$this->_pre['channeln']		= $s;
		$this->_pre['channelc']		= $s;
		$this->_web['channel']		= $this->_var['channel'];
		$this->_web['dir']		= $s;
		$this->setChannelDir($s);
	}
	public function setChannelDir($s)
	{
		$this->_var['channel.dir']	= $s?($s.'/'):'';
		$this->_web['channel.dir']	= $this->_var['channel.dir'];
	}
	public function getChannel(){return $this->_var['channel'];}
	public function getDir(){return $this->_var['dir'];}
	public function getChannelDir(){return $this->_var['channel.dir'];}
	public function setDir($s,$t=0)
	{
		if(!$s&&$t==0) return;
		$this->_var['dir']		= $s;
		$this->_var['channel.dir']	= isset($s{0})?$s.'/':'';
		$this->_web['dir']		= $this->_var['dir'];
		$this->_web['channel.dir']	= $this->_var['channel.dir'];
	}
	public function setSubdir($s){$this->_var['subdir']=$s; $this->_var['subdirs']=$s?($s.'/'):'';}
	public function setPrefix($s,$s2=''){$this->_var['prefix']=$s; $this->_var['prefixs']=$s2?$s2:$s;}
	public function setPageHead($s){$this->_var['pagehead']=$s;}
	public function setPage($s){$this->_var['page']=$s;}
	public function setPageEnd($s){$this->_var['pageend']=$s;}
	public function setModule($s){$this->_var['module']=$s;$this->_var['modules']='';if($s)$this->_var['modules']='.'.$s;}
	public function setModulei($s){$this->_var['modulei']=$s;$this->_var['moduleis']='';if($s)$this->_var['moduleis']='.'.$s;}
	public function setAction($s){$this->_var['action']=$s;$this->_var['actions']='';if($s)$this->_var['actions']='.'.$s;}
	//public function setPmode($s){$this->_var['smode']=$s;$this->_var['modes']='';if($s)$this->_var['modes']='.'.$s;}
	//public function setPtype($s){$this->_var['type']=$s;$this->_var['types']='';if($s)$this->_var['types']='.'.$s;}
	public function setStatus($s){$this->_var['status']=$s;$this->_var['statuss']='';if($s)$this->_var['statuss']='.'.$s;}
	public function setExtend($s,$s2=''){$this->_var['extend']=$s; $this->_var['extends']=$s2?$s2:$s;}
	public function setPostfix($s,$s2=''){$this->_var['postfix']=$s; $this->_var['postfixs']=$s2?$s2:$s;}
	public function setChild($s){$this->_var['child']=$s;$this->_var['childs']='';if($s)$this->_var['childs']='~'.$s;}
	
	public function setDP($k,$v){$this->_def[$k]=$v;$this->_pre[$k]=$v;$this->_web[$k]=$v;}
	public function getDef($k){return $this->_def[$k];}
	//public function addDef($k,$v){$this->_def[$k]=$v;}
	public function setDef($k,$v){$this->_def[$k]=$v;}
	public function setDefs($ar){$this->_def=$ar;}
	
	public function getPre($k){return $this->_pre[$k];}
	//public function addPre($k,$v){$this->_pre[$k]=$v;}
	public function setPre($k,$v){$this->_pre[$k]=$v;}
	public function setPres($ar){$this->_pre=$ar;}
	
	public function getWeb($k){return $this->_web[$k];}
	public function setWeb($k,$v){$this->_web[$k]=$v;}
	public function setWebs($ary)
	{
		if(isTree($ary)) $ary=$ary->getArray();
		foreach($ary as $k=>$v){$this->_web[$k]=$v;}
	}
	
	public function setOutput($s){$this->output=$s;}
	public function getOutput(){return $this->output;}
	/*
	public function getURL(){return appURL('theme');}
	public function getPath(){return $this->_var['theme'];}
	*/
	
	
	public function toThemeDir($s){ return isset($s{0})?($s==$this->_var['themedef']?$this->_var['themedef.dir']:($s.'/')):'';}
	
	public function toThemePath($s)
	{
		/*$this->_var['themes.path'].*/
		//debugx($s);
		return appExt($s,$this->_var['ext']);
	}
	public function toCachePath($s)
	{
		return appExt($this->_var['cache.path'].$this->_var['theme'].'__'.r($s,'/','__').$this->_var['childs'],$this->_var['ext']);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		global $_cfg;
		$this->_var['themebase.path']	= appDirPath('vdcs.web/themes/'.$this->_var['themedef.dir']);
		$this->_var['themeapp.path']	= appDirPath('vdcs.web/themes/'.$this->_var['themeapp.dir']);
		$this->_var['themes.url']	= appURL('themes');
		$this->_var['themes.path']	= appDirPath('themes/');
		$this->_var['cache.path']	= appDirPath('data.cache/themes/');
		//debuga($this->_var);
		$this->setDefine();
		$this->setTheme($_cfg['app']['app.theme']?$_cfg['app']['app.theme']:$this->_var['themedef']);
		
		//$this->_var['format']=query(self::$FormatKey);
		
		$this->doInitWeb();		//??
	}
	
	public function doInitWeb()
	{
		global $_cfg;
		$this->_web['meta.contenttype']=$this->_var['content.type'];
		$this->_web['meta.charset']=CHARSET_HTML;
		$this->_web['meta.language']=CONTENT_LANGUAGE;
		$this->_web['meta.generator']=$_cfg['app']['web.generator'];
		$this->_web['meta.keywords']=$_cfg['app']['web.keywords'];
		$this->_web['meta.description']=$_cfg['app']['web.description'];
		$this->_web['header.append']=$_cfg['app']['header.append'];
		$this->_web['header.xcompat']=$_cfg['app']['header.xcompat'];
		$this->_web['script.public']=$_cfg['app']['script.public'];
	}
	
	protected function doChannelExtend()
	{
		$this->_var['channelbase.path']	= $this->_var['themebase.path'].$this->_var['channel.dir'];
		$this->_var['channelapp.path']	= $this->_var['themeapp.path'].$this->_var['channel.dir'];
		$this->_var['channelv.path']	= rd(appDirPath('common.channel/{$channel}/theme/'),'channel',$this->_var['channel']).$this->_var['channel.dir'];
		$this->_var['channeldef.path']	= $this->_var['themedef.path'].$this->_var['channel.dir'];
		$this->_var['channel.path']	= $this->_var['theme.path'].$this->_var['channel.dir'];
		//debuga($this->_var);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad($sPage='',$sModule='')
	{
		//treeWeb.doAppendTree(cfg.treeWeb)
		if(!$sPage) $sPage=$this->_var['pagehead'].$this->_var['page'].$this->_var['pageend'];
		//if(!$sModule) $sModule=$this->_var['module'];
		//if($sModule) $sPage.='.'.$sModule;
		$sPage.=$this->_var['modules'].$this->_var['moduleis'].$this->_var['actions'].$this->_var['statuss'];
		$sPage=$this->_var['prefixs'].$sPage.$this->_var['extends'].$this->_var['postfixs'];
		$sPage=trim($sPage,'.');
		/*
		if($this->_var['format']){
			$sPage.=self::$FormatSymbol.$this->_var['format'];
			if(inp(self::$FormatXML,$this->_var['format'])>0){
				$this->setContentType('xml');
				pageXML();
			}
			$this->_var['formatkey']=self::$FormatKey;
		}
		*/
		$this->doChannelExtend();				// extend func
		$this->doLoadFilePath($sPage);
		$this->doCacheFileExtend();				// extend func
		$isReadCache=false;
		if(isFile($this->_var['CacheFilePath'])){
			if(@filemtime($this->_var['CacheFilePath'])>@filemtime($this->_var['RealFilePath'])) $isReadCache=true;
		}
		$this->isCacheFile=false;
		if(!$isReadCache){
			$this->doLoadFilePaths($sPage);
			//debugx($this->_var['RealFilePath']);
			if(!@$fp=fopen($this->_var['RealFilePath'],'r')){
				$dirFilePath=$this->toThemePath($this->_var['channel.path'].$this->_var['subdirs'].$sPage);
				//$dirFilePath=$this->toThemePath($this->_var['channel.dir'].$sPage);
				
				$dirFilePath=toPathRel($dirFilePath);
				$MessageMsg='Can\'t found current template file('.$this->_var['subdirs'].$sPage.$this->_var['ext'].').';
				$MessageDesc='File: '.$dirFilePath.'<br/>';
				$MessageDesc.='URI: '.$_SERVER['REQUEST_URI'].'<br/>';
				$MessageDesc.='CLS: '.APP_OBJECTNAME.'<br/>';
				if(defined('APP_OBJECTPA')) $MessageDesc.='PA: '.APP_OBJECTPA.'<br/>';
				$MessageDesc.='Script: '.$_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].' ? '.$_SERVER['QUERY_STRING'];
				if($this->_var['error.show']) dcsMessageError(-1,self::$MessageTitle,$MessageMsg,$MessageDesc,'BaseTheme::doLoad');
				$this->_var['error.is']=true;$this->_var['error.status']='noexist';
			}
			else{
				$fileSize=filesize($this->_var['RealFilePath']);
				if($fileSize>0){
					$this->output=fread($fp,filesize($this->_var['RealFilePath']));
					$this->output=removeBOM($this->output);
				}
			}
			@fclose($fp);
			
			$this->doCacheContentExtend();			// extend func
			
			if(!$this->_var['error.status']){
				//cache update
				if(!$fp=@fopen($this->_var['CacheFilePath'],'w')){
					$this->_var['error.is']=true;$this->_var['error.status']='cache.noopen';
					if($this->_var['error.show']) dcsMessageError(-1,self::$MessageTitle,'Open cache file('.$this->_var['subdirs'].''.$sPage.$this->_var['ext'].') has no access!','File: '.$this->_var['CacheFilePath']);
				}
				@flock($fp,2);
				if(!@fwrite($fp,$this->output)){
					if($this->_var['error.show']) dcsMessageError(-1,self::$MessageTitle,'Write cache file has no access!','File: '.$this->_var['CacheFilePath']);
					$this->_var['error.is']=true;$this->_var['error.status']='cache.nosave';
				}
				@fclose($fp);
			}
			
			$this->isCacheFile=true;
		}
	}
	protected function doLoadFilePath($sPage)
	{
		$this->_var['CacheFilePath']=$this->toCachePath($this->_var['channel.dir'].$this->_var['subdirs'].$sPage);
		$path=$this->toThemePath($this->_var['channel.path'].$this->_var['subdirs'].$sPage);
		if(!isFile($path)) $path=$this->toThemePath($this->_var['channel.path'].$sPage);
		$this->_var['RealFilePath']=$path;
	}
	protected function doLoadFilePaths($sPage)
	{
		if(!isFile($this->_var['RealFilePath'])){
			$path=$this->_var['RealFilePath'];
			if($this->_var['channela.path']){
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channela.path'].$this->_var['subdirs'].$sPage);
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channela.path'].$sPage);
			}
			if($this->_var['channelc.path']){
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelc.path'].$this->_var['subdirs'].$sPage);
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelc.path'].$sPage);
			}
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channeldef.path'].$this->_var['subdirs'].$sPage);
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channeldef.path'].$sPage);
			if($this->_var['channelva.path']){
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelva.path'].$this->_var['subdirs'].$sPage);
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelva.path'].$sPage);
			}
			if($this->_var['channelvc.path']){
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelvc.path'].$this->_var['subdirs'].$sPage);
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelvc.path'].$sPage);
			}
			if($this->_var['channelv.path']){
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelv.path'].$this->_var['subdirs'].$sPage);
				if(!isFile($path)) $path=$this->toThemePath($this->_var['channelv.path'].$sPage);
			}
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channelapp.path'].$this->_var['subdirs'].$sPage);
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channelapp.path'].$sPage);
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channelbase.path'].$this->_var['subdirs'].$sPage);
			if(!isFile($path)) $path=$this->toThemePath($this->_var['channelbase.path'].$sPage);
			$this->_var['RealFilePath']=$path;
		}
	}
	
	public function doParse()
	{
		if($this->isError()) return;
		global $_cfg,$dcs,$cfg,$ctl,$theme,$ua,$cpo;	//,$cp,$cx,$cpa
		//global $uai,$uac,$uad;	$user,$userc,$staff,$union,$client,$agent,$company,$ent,$partner;	$manager
		//$this->pageHeader();
		obStarts();
		include_once $this->_var['CacheFilePath'];
		//$func='doPageTheme';
		//if(function_exists($func)){
			$this->output=obContent();
			obClean();
			$this->pageHeader();
			//cfunc($func);
			$cpo->doThemePre();
			$cpo->doTheme();
			$cpo->doThemePos();
			//call_user_func(array($cpo,'doTheme'),'');
			echo $this->output;
		//}
		//obFlush();
	}
	
	public function doCacheFilterVar($s,$v){$this->output=CommonTheme::toCacheFilterVar($this->output,$s,$v);}
	public function doCacheFilterTree($k,$v,$func=''){$this->output=CommonTheme::toCacheFilterTree($this->output,$k,$v,$func);}
	public function doCacheFilterLoop($k,$v,$b=''){$this->output=CommonTheme::toTransactLoop($this->output,CommonTheme::toPatternLoop($k),$v,$k,$b);}
	
	public function doCacheFilterList($channel,$key,$strTable,$pattern='',$pModule='')
	{
		//$this->output=label.toParseListsCache($this->output,$channel,$key,$strTable,$pattern);
		if(len($pModule)>0){
			if(len($pattern)<1) $pattern='<label:list.{$module}>'.PATTERN_FLAG_CONTENT.'<\/label:end>';
			$pattern=rd($pattern,'module',$pModule);
		}
		if(len($pattern)<1) $pattern='<label:list>'.PATTERN_FLAG_CONTENT.'<\/label:end>';
		$_matches=utilRegex::toMatches($this->output,$pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$this->output=r($this->output,$_matches[0][$m],CommonTheme::HTMLMarkHeads.'$theme->label->toParseListTable('.CommonTheme::toVarParams($channel).','.CommonTheme::toVarParams($key).','.CommonTheme::toVarCache($strTable,0).','.CommonTheme::toDTMLCacheValue($_matches[1][$m],1).')'.CommonTheme::HTMLMarkFoot);
		}
		//####################
		unset($_matches);
		//####################
	}
	
	public function doCacheFilterPaging(&$p,$v)
	{
		if(is_object($p)){
			$this->output=CommonTheme::toCachePaging($this->output,$v);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function pageHeader()
	{
		if($this->_var['content.type.v']){
			//@header('Content-type: '.$this->_var['content.type.v'].'; charset='.$this->_var['content.charset']);
			dcsHeader($this->_var['content.type.v'],$this->_var['content.charset']);
			$this->_var['content.type.v']='';
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doCacheFileExtend()
	{
		if($this->_var['_cache']>2) @unlink($this->_var['CacheFilePath']);
	}
	
	protected function doCacheContentExtend()
	{
		$oc=new BaseThemeCacher();
		$oc->setVars($this->_var);
		$oc->setVar('ThemeDefault',$this->_var['themedef']);
		$oc->setDefs($this->_def);$oc->setPres($this->_pre);$oc->setWebs($this->_web);
		$this->output=$oc->toDTMLCachePre($this->output);
		//####################		当前页面处理
		//cfunc('doPageThemeCache');
		//global $cpo;
		//$cpo->doThemeCachePre();
		//$cpo->doThemeCache();
		//$cpo->doThemeCachePos();
		//call_user_func(array($cpo,'doThemeCache'),'');
		//####################
		$this->output=$oc->toDTMLCache($this->output);
		unset($oc);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doObjectLoad($obj)
	{
		if(len($obj)>0 && inp($this->objectLoad,$obj)<1){
			$this->output='<'.'?global $'.$obj.''.CommonTheme::HTMLMarkFoot.NEWLINE.$this->output;
			$this->objectLoad.=','.$obj;
		}
	}
}
?>