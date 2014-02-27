<?
class BaseThemeCacher
{
	protected $_var=array(),$_def=array(),$_pre=array(),$_web=array();
	protected $mapXCML=array();
	protected $IncludeFloor=20;
	
	protected static $PathEnum		= ['themebase','themedef','theme'];
	protected static $PathChannelEnum	= ['channelbase','channelv','channeldef','channel'];
	protected static $ThemeEnCodeHead	= 'DTML:';
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unset($this->_var,$this->_def,$this->_pre,$this->_web);
		unset($this->mapXCML);
	}
	
	public function setDefs($ar){$this->_def=$ar;}
	public function setDef($k,$v){$this->_def[$k]=$v;}
	public function getDef($k){return $this->_def[$k];}
	
	public function setPres($ar){$this->_pre=$ar;}
	public function getPre($k){return $this->_pre[$k];}
	
	public function setVars($ar){$this->_var=$ar;}
	public function setVar($k,$v){$this->_var[$k]=$v;}
	
	public function setWebs($ar){$this->_web=$ar;}
	public function getWeb($k){return $this->_web[$k];}
	
	public function setIncludeFloor($s){$this->IncludeFloor=$s;}
	
	
	public function toThemePath($s)
	{
		//debugx(appExt($s,$this->_var['ext']));
		return appExt($s,$this->_var['ext']);
	}
	public function toCachePath($s){return appExt($this->_var['cache.path'].r($s,'/','__'),$this->_var['ext']);}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCachePre($re)
	{
		global $_cfg;
		$re=r($re,'<?xml version="1.0" encoding="'.$this->_var['content.charset'].'"?>','<web:xml.string>');
		$re=r($re,'<?','<<<???');
		$re=r($re,'?>','???>>>');
		$re=r($re,'<<<???','<<?=\'?\'?>');
		$re=r($re,'???>>>','<?=\'?\'?>>');
		$re='<'.'?if(!defined(\'VDCS\')) exit(\'V D C S Access Denied\');'.CommonTheme::HTMLMarkFoot.NEWLINE.$re;
		
		//def define
		if(DCS::isLocal()){		//server / local
			$this->setDef('x','l');
			$this->setDef('xx','.l');
		}
		else{
			$this->setDef('x','s');
			$this->setDef('xx','.s');
		}
		$re=preg_replace('/{#'.PATTERN_FLAG_VAR.'}/ies','array_key_exists(\'$1\',\$this->_def)?\$this->_def[\'$1\']:\'{#$1}\'',$re);
		//include floor
		for($i=0;$i<$this->IncludeFloor;$i++){
			$re=preg_replace('/<label:include file=\"(.[^\"]*)\">/ies','\$this->getIncludeContent(\'$1\')',$re);	// File
			$re=preg_replace('/{#'.PATTERN_FLAG_VAR.'}/ies','array_key_exists(\'$1\',\$this->_def)?\$this->_def[\'$1\']:\'{#$1}\'',$re);
		}
		
		//app
		$re=VDCSDTML::toParseApp($re);
		//$re=preg_replace('/<app:'.PATTERN_FLAG_VAR.'\.'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'$1.$2\']',$re);
		$re=preg_replace('/<app:(dir)\.'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'sys.$1\'][\'$2\']',$re);
		$re=preg_replace('/<url:'.PATTERN_FLAG_VAR.'>/ies','\$_cfg[\'app\'][\'url.$1\']',$re);	//<url:*>=<app:url.*>
		
		$re=preg_replace('/<dcs:'.PATTERN_FLAG_VAR.'>/s',CommonTheme::HTMLMarkHeads.'VDCSDTML::getValueDCS(\'$1\')'.CommonTheme::HTMLMarkFoot,$re);
		
		$re=r($re,'<label:include script="!~~@','<label:include script="<url:images>themes/default/{@channel}/{@portal}/');
		$re=r($re,'<label:include script="!~~','<label:include script="<url:images>themes/default/{@channel}/');
		$re=r($re,'<label:include script="!~','<label:include script="<url:images>themes/default/');
		$re=r($re,'<label:include script="#','<label:include script="<web:url.themeb>');
		$re=r($re,'<label:include script="@','<label:include script="<web:url.themed>');
		$re=r($re,'<label:include script="~~@','<label:include script="<web:url.themes>{@channel}/{@portal}');
		$re=r($re,'<label:include script="~~~','<label:include script="<web:url.themes>{@channel}/images/');
		$re=r($re,'<label:include script="~~','<label:include script="<web:url.themes>{@channel}/');
		$re=r($re,'<label:include script="~','<label:include script="<web:url.themes>');
		
		$re=r($re,'<web:meta.type>',$this->_var['content.type']);
		$re=r($re,'<web:meta.contenttype>',$this->_var['content.type']);
		$re=r($re,'<web:meta.charset>',$this->_var['content.charset']);
		$re=r($re,'<web:meta.lang>',$this->_web['meta.language']);
		$re=r($re,'<web:meta.language>',$this->_web['meta.language']);
		$re=r($re,'<web:header.append>',$this->_web['header.append']);
		$re=r($re,'<web:header.xcompat>',$this->_web['header.xcompat']);
		$re=r($re,'<web:script.public>',$this->_web['script.public']);
		/*$re=r($re,'<web:xml.string>','<<?=\'?\'?>xml version="1.0" encoding="'.$this->_var['content.charset'].'"<?=\'?\'?>>');*/
		$re=r($re,'<web:xml.string>',CommonTheme::HTMLMarkHeads.'VDCSXCML::XMLHeader()'.CommonTheme::HTMLMarkFoot);
		$re=r($re,'<web:url.themeb>',$this->getWeb('url.themeb'));
		$re=r($re,'<web:url.themed>',$this->getWeb('url.themed'));
		$re=r($re,'<web:url.themes>',$this->getWeb('url.themes'));
		$re=r($re,'<web:url.theme>',$this->getWeb('url.theme'));
		//VDCSDTML::toParseAppStat
		$re=r($re,'<web:stat.exectime>',CommonTheme::HTMLMarkHeads.'dcsExecTime()'.CommonTheme::HTMLMarkFoot);
		$re=r($re,'<web:stat.query>',CommonTheme::HTMLMarkHeads.'DB::getTotal()'.CommonTheme::HTMLMarkFoot);
		$re=r($re,'<web:stat.memory>',CommonTheme::HTMLMarkHeads.'dcsMemoryUsage()'.CommonTheme::HTMLMarkFoot);
		$re=r($re,'<web:stat.gzip>',CommonTheme::HTMLMarkHeads.'appv(\'app.gzip.status\')'.CommonTheme::HTMLMarkFoot);
		$re=preg_replace('/<web:(meta|url|var)\.([^<>]*)>/s',CommonTheme::HTMLMarkHeads.'$this->getWeb(\'$1.$2\')'.CommonTheme::HTMLMarkFoot,$re);
		//$re=r($re,'<web:nav.title>',CommonTheme::HTMLMarkHeads.'$cfg->getNavTitles()'.HTMLMarkFoot);
		//$re=preg_replace('/<web:([^<>]*)>/s',CommonTheme::HTMLMarkHeads.'$this->getWeb(\'$1\')'.CommonTheme::HTMLMarkFoot,$re);
		$re=CommonTheme::toCacheFilterTree($re,'web','theme.','getWeb');
		
		//<label:include script="*" type="js">
		$re=preg_replace('/<label:include script=\"(.[^\"]*)\"( type=\"(.[^\"]*)\")?( id=\"(.[^\"]*)\")?>/ies','\$this->toIncludeScript(\'$1\',\'$3\',\'$5\')',$re);
		
		//pre label
		//$re=r($re,'{@channel}',$this->_var['channel']);		//CommonTheme::HTMLMarkHeads.'$cfg->getChannel()'.CommonTheme::HTMLMarkFoot
		//PATTERN_FLAG_LABEL
		$re=preg_replace('/{@'.PATTERN_FLAG_VAR.'}/ies','array_key_exists(\'$1\',\$this->_pre)?\$this->_pre[\'$1\']:\'{@$1}\'',$re);
		//####################
		$_node='(<@part:([\w]*)>'.PATTERN_FLAG_CONTENT.')?';
		$_pattern='<themes:menu\(\"'.PATTERN_FLAG_LABEL.'\"\)>'.PATTERN_FLAG_CONTENT.$_node.$_node.$_node.$_node.$_node.'<\/themes>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($i=0;$i<count($_matches[0]);$i++){
			$aryNode=array();
			$aryNode['item']=$_matches[2][$i];
			for($n=0;$n<((count($_matches)-3)/3);$n++){
				$k=$_matches[($n)*3+3+1][$i];
				if($k) $aryNode[$k]=NEWLINE.trim($_matches[($n)*3+3+2][$i]);
			}
			$tableMenu=getFile2Table(appExt($this->_var['themes.path'].'menu.'.$_matches[1][$i]));
			$_values='';
			$tableMenu->doBegin();
			while($tableMenu->isNext()){
				if($tableMenu->getItemValue('status')!='0'){
					$menuPart=$tableMenu->getItemValue('part');
					if(!$menuPart) $menuPart='item';
					$_values.=VDCSDTML::toReplaceEncodeFilter($aryNode[$menuPart],$tableMenu->getItemTree(),rd(VDCSDTML::PATTERN_DTML_ITEMS_KEY,'key','menu'));
				}
			}
			$re=r($re,$_matches[0][$i],$_values);
		}
		//####################
		unset($_matches);
		//####################
		$re=preg_replace('/<(vart|themes:var|xcml:themes)\("'.PATTERN_FLAG_LABEL.'"'.PATTERN_FLAG_PARAMQ.'\)>/ies','\$this->getVarValue(\'$2\',\'$4\')',$re);
		$re=preg_replace('/<xcml:value\("'.PATTERN_FLAG_LABEL.'","'.PATTERN_FLAG_LABEL.'"'.PATTERN_FLAG_PARAMQ.'\)>/ies','\$this->getXCMLValue(\'$1\',\'$2\',\'$4\')',$re);
		$re=preg_replace('/<xcml:item\("'.PATTERN_FLAG_LABEL.'","'.PATTERN_FLAG_LABEL.'","'.PATTERN_FLAG_LABEL.'"'.PATTERN_FLAG_PARAMQ.'\)>/ies','\$this->getXCMLItem(\'$1\',\'$2\',\'$3\',\'$5\')',$re);
		$re=preg_replace('/<langs:'.PATTERN_FLAG_LABEL.PATTERN_FLAG_OPTION.'>/ies','\$this->getLangs(\'$1\',\'$3\')',$re);
		$re=preg_replace('/<lang:'.PATTERN_FLAG_LABEL.PATTERN_FLAG_OPTION.'>/ies','\$this->getLang(\'$1\',\'$3\')',$re);
		//####################
		// once again
		$re=preg_replace('/<label:include script=\"(.[^\"]*)\"( type=\"(.[^\"]*)\")?( id=\"(.[^\"]*)\")?>/ies','\$this->toIncludeScript(\'$1\',\'$3\',\'$5\')',$re);
		$re=preg_replace('/<(vart|themes:var|xcml:themes)\("'.PATTERN_FLAG_LABEL.'"'.PATTERN_FLAG_PARAMQ.'\)>/ies','\$this->getVarValue(\'$2\',\'$4\')',$re);
		//####################
		$re=VDCSDTML::toParseApp($re);
		//####################
		
		//####################
		$re=CommonConfigCache::toDTMLCachePre($re);	//cfg
		//####################
		
		return $re;
	}
	
	
	public function toDTMLCache($re)
	{
		//####################
		$re=$this->toCacheDPV($re);
		//####################
		
		//####################
		//element
		$re=CommonConfigCache::toDTMLCache($re);	//cfg
		$re=PagesControlCache::toDTMLCache($re);	//ctl
		$re=PagesElementCache::toDTMLCache($re);	//element
		//####################
		$re=CommonLabelCache::toDTMLCache($re);		//label
		$re=$this->toCacheAccountExtend($re);		//account
		$re=CommonTheme::toTransactor($re);		//expression
		//####################
		
		//####################
		$re=$this->toCacheDPV($re);
		//####################
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function toCacheDPV($re)
	{
		//def
		$re=preg_replace('/{#'.PATTERN_FLAG_VAR.'}/ies','\$this->_def[\'$1\']',$re);
		//pre
		$re=preg_replace('/{@'.PATTERN_FLAG_VAR.'}/ies','array_key_exists(\'$1\',\$this->_pre)?\$this->_pre[\'$1\']:\'{@$1}\'',$re);
		//$re=preg_replace('/{@'.PATTERN_FLAG_VAR.'}/ies','\$this->_pre[\'$1\']',$re);
		return $re;
	}
	
	protected function toCacheAccountExtend($re)
	{
		//$user,$userc,$staff,$union,$client;	//$agent,$company,$ent,$partner;	$manager
		$re=CommonTheme::toCacheFilterTree($re,'account','ua.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'user','ua.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'userd','ua.','getDatad');
		$re=CommonTheme::toCacheFilterTree($re,'userc','userc.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'staff','staff.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'union','union.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'client','client.','getData');
		
		$re=CommonTheme::toCacheFilterTree($re,'ua','cpo.ua.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uad','cpo.uad.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uac','cpo.uac.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uagent','cpo.ua.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uagentd','cpo.uad.','getData');
		$re=CommonTheme::toCacheFilterTree($re,'uagentc','cpo.uac.','getData');
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getIncludeContent($file)
	{
		$prifile=$file;
		if(left($file,1)==DIR_SEPARATOR){
			$file=toSubstr($file,2);
			$filepath=$this->toThemePath($this->_var['theme.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['theme.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themedef.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themedef.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themeapp.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themeapp.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themebase.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['themebase.path'].$file);
		}
		else{
			$filepath=$this->toThemePath($this->_var['channel.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channel.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelc.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelc.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channeldef.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channeldef.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelvc.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelvc.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelv.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelv.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelapp.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelapp.path'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelbase.path'].$this->_var['subdirs'].$file);
			if(!isFile($filepath)) $filepath=$this->toThemePath($this->_var['channelbase.path'].$file);
		}
		if(isFile($filepath)){
			return utilFile::getContent($filepath);
		}
		else{
			return '<!--Include File Error: no exist('.$prifile.$this->_var['ext'].')-->';
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toIncludeScript($script,$type,$id=null)
	{
		$vid=$id?(' id="'.$id.'"'):'';
		switch($type){
			case '':
			case 'js':
				if(ins($script,':')<1 && substr($script,0,1)!='/') $script='<app:url.script>'.$script;
				$re='<script type="text/javascript" src="'.$script.'?d=<app:var.script.d>"'.$vid.'></script>';				// charset="'.CHARSET_PAGE.'"
				break;
			case 'css':
				if(ins($script,':')<1 && substr($script,0,1)!='/') $script='<app:url.images>css/'.$script;
				$re='<link rel="stylesheet" rev="stylesheet" type="text/css" href="'.$script.'?d=<app:var.script.d>"'.$vid.' />';	// charset="'.CHARSET_PAGE.'"
				break;
			default : 	$re='[script='.$script.' type='.$type.']'; break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getConfigValue($chn,$key,$term)
	{
		global $cfg;
		$re=$cfg->getConfigValue($key);
		if($term){
			$treeTerm=utilString::toTree($term,';','==');
			$treeTerm->doBegin();
			for($i=0;$i<$treeTerm->getCount();$i++){
				$re=r($re,$treeTerm->getItemKey(),$treeTerm->getItemValue());
				$treeTerm->doMove();
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getVarValue($key,$items=''){return $this->getXCMLValue('var',$key,$items);}
	
	public function getXCMLValue($file,$k,$items='')
	{
		$mapKey='tree='.$file;
		if(array_key_exists($mapKey,$this->mapXCML)){
			$treeValue=newTree();
			$treeValue->setArray($this->mapXCML[$mapKey]);
		}
		else{
			$treeValue=newTree();
			$path=$file;
			if($file=='var'){
				$path=appExt($this->_var['themes.path'].$file);
				//appFilePath('vdcs.web/themes/'.$file));
				$treeValue->doAppend(VDCSDTML::getConfigTree('vdcs.web/themes/'.$file));
			}
			$treeValue->doAppend(VDCSDTML::getConfigTree($path));
			//debugTree($treeValue);
			$this->mapXCML[$mapKey]=$treeValue->getArray();
		}
		$re=$treeValue->getItem($k);
		$re=VDCSDTML::toParseItems($re,$items);
		return $re;
	}
	
	public function getXCMLItem($file,$k,$terms,$items='')
	{
		$mapKey='table='.$file;
		if(array_key_exists($mapKey,$this->mapXCML)){
			$tableData=newTable();
			$tableData->setArray($this->mapXCML[$mapKey]);
		}
		else{
			$tableData=VDCSDTML::getConfigTable($file);
			$this->mapXCML[$mapKey]=$tableData->getArray();
		}
		$re=$tableData->getTermsValue($term,$k);
		$re=VDCSDTML::toParseItems($re,$items);
		return $re;
	}
	
	
	
	
	public function getLangs($k,$items='')
	{
		$re='';
		if(!isTree($this->treeLangs)){
			//debuga($this->_var);
			$filename='language';
			$this->treeLangs=newTree();
			foreach(self::$PathEnum as $pathkey){
				$path=$this->_var[$pathkey.'.path'].$filename.EXT_CONFIG;
				//debugx($path);
				if(isFile($path)){
					$this->treeLangs->doAppendTree(VDCSDTML::getConfigTree($path));
				}
			}
			//debugTree($this->treeLangs);
		}
		$re=$this->treeLangs->getItem($k);
		$re=VDCSDTML::toParseItems($re,$items);
		return $re;
	}
	
	public function getLang($k,$items='')
	{
		$re='';
		if(!isTree($this->treeLang)){
			$filename='lang';
			$this->treeLang=newTree();
			foreach(self::$PathChannelEnum as $pathkey){
				$path=$this->_var[$pathkey.'.path'].$filename.EXT_CONFIG;
				//debugx($path);
				if(isFile($path)){
					$this->treeLang->doAppendTree(VDCSDTML::getConfigTree($path));
				}
			}
			//debugTree($this->treeLang);
		}
		$re=$this->treeLang->getItem($k);
		$re=VDCSDTML::toParseItems($re,$items);
		return $re;
	}
	
}


class CommonChannelCache
{
	public static function toDTMLCache($re)
	{
		global $_cfg,$cfg;
		
		
		return $re;
	}
}

class ModelClassCache0
{
	public static function toDTMLCache($re)
	{
		global $_cfg,$cfg;
		
		
		return $re;
	}
}
