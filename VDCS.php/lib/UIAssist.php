<?
defined('UIA_PATH_CACHEX') || 			define('UIA_PATH_CACHEX',		_BASE_PATH_ROOT._BASE_DIR_DATA.'cache/themes/');

class UIAssist
{
	const NEWLINE			= "\n";
	const CHARSET_HEAD		= '@charset "utf-8";';
	
	public static $_var		= [];
	
	
	public static function v($k,$v=null){if($v)self::$_var[$k]=$v;return self::$_var[$k];}
	
	public static function getContent($file){return getFileContent(UIA_PATH_CACHEX.$file.'.css');}
	public static function setContent($file,$content){doFileWrite(UIA_PATH_CACHEX.$file.'.css',$content);}
	
	public static function toFilePath($file)
	{
		$re=self::v('PATH_ROOT').substr($file,1);
		return $re;
	}
	public static function isFile($file)
	{
		return isFile(self::toFilePath($file));
	}
	
	
	public static function toRealInclude($filea,$mode)
	{
		$cache_file=self::v('cache_file');
		$iscompile=!strpos($cache_file,'.compile-')===false;
		!$iscompile&&$iscompile=!strpos($cache_file,'.compile.')===false;
		$file_px=!strpos($cache_file,'.debug.')===false?'.debug':'';
		$content=self::CHARSET_HEAD.self::NEWLINE;
		if($iscompile){
			//debuga($filea);
			$contents='';
			//##########
			//debugs($cache_file);
			$pattern='/compile-([\w\d]*)/ies';
			$_m=preg_match_all($pattern,$cache_file,$_matches);
			//print_r($_matches);
			if(count($_matches[0])>0) $compile_hash=$_matches[1][0];
			//debugs($compile_hash);
			if($compile_hash){
				$contents=self::getContent('compile-'.$compile_hash);
			}
			//##########
			foreach($filea as $file){
				$content.=self::toRealContent($file,$contents,false);
				$file_=substr(strrchr($file,PATH_SEPARATORS),1);
				$dir_=substr($file,0,strlen($file)-strlen($file_));
				$pattern='/@import url\(\"([^\)\'\"]*)\"\);/ies';
				$_m=preg_match_all($pattern,$content,$_matches);
				//print_r($_matches);
				for($m=0;$m<count($_matches[0]);$m++){
					$filei=self::toRealDir($dir_,$_matches[1][$m]);
					//debugx('parserCss='.$filei);
					$rFlagValue=self::toRealPathC($filei,$mode,['file_px'=>$file_px,'import'=>true]);
					$content=r($content,$_matches[0][$m],$rFlagValue);
				}
			}
			unset($_matches);
		}
		else{
			//##########
			$compile_hash=utilCoder::toMD5($cache_file);
			//debugs($cache_file.' hash: '.$compile_hash);
			self::v('compile_hash',$compile_hash);
			$contents=self::toRealContents($filea,null,true,false);
			self::setContent('compile-'.$compile_hash,$contents);
			//##########
			foreach($filea as $file){
				$content.=self::toRealPathC($file,$mode,['file_px'=>$file_px,'import'=>true]).self::NEWLINE;
			}
		}
		unset($contents);
		$content=r($content,self::NEWLINE.self::CHARSET_HEAD,'');
		return $content;
	}
	public static function toRealPathC($file,$mode,$opt)
	{
		if(!self::isFile($file)) return '';
		$path=$file;
		if($mode=='compile'){
			$px_compile='.compile';
			$compile_hash=self::v('compile_hash');
			//debugs($compile_hash);
			if($compile_hash) $px_compile.='-'.$compile_hash;
			//self::v('url_compile','/xmerger.{$filev}{$px}.cssx?{$file}');
			$path=self::v('url_compile');
			$path=rd($path,'file',$file);
			$path=rd($path,'filev',str_replace(['/','..'],['_','--'],$file));
			$path=rd($path,'px',$opt['file_px'].$px_compile);
		}
		if($opt['import']) $path='@import url("'.$path.'");';
		return $path;
	}
	
	public static function toRealContents($filea,$contents=null,$include=true,$compile=true)
	{
		$contents='';
		foreach($filea as $file){
			$contents.=self::toRealContent($file,$contents,$include,$compile);
		}
		return $contents;
	}
	public static function toRealContent($file,$contents=null,$include=true,$compile=true)
	{
		$path=self::toFilePath($file);
		if(!isFile($path)) return '';
		//##########
		static $paths=array();
		if(in_array($path,$paths)) return '';
		array_push($paths,$path);
		//##########
		$content=getFileContent($path);
		$file_=substr(strrchr($file,PATH_SEPARATORS),1);
		$dir_=substr($file,0,strlen($file)-strlen($file_));
		//debugx($file.' = '.$dir_.' , '.$file_);
		//##########
		if($include){
			$pattern='/@import url\(\"([^\)\'\"]*)\"\);/ies';
			$_m=preg_match_all($pattern,$content,$_matches);
			//print_r($_matches);
			for($m=0;$m<count($_matches[0]);$m++){
				$filei=self::toRealDir($dir_,$_matches[1][$m]);
				//debugx('parserCss='.$filei);
				$rFlagValue=self::toRealContent($filei,$contents);
				$content=r($content,$_matches[0][$m],$rFlagValue);
			}
		}
		//##########
		$pattern='/url\((\'|")?([^\)\'"]*)(\'|")?\)/ies';
		$_m=preg_match_all($pattern,$content,$_matches);
		//print_r($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$filei=self::toRealDir($dir_,$_matches[2][$m]);
			$rFlagValue='url("'.$filei.'")';
			$content=r($content,$_matches[0][$m],$rFlagValue);
		}
		//##########
		$content=r($content,self::CHARSET_HEAD,'');
		if($compile) $content=self::filterCompile($content,$contents);
		$content=self::cssOptimizer($content);
		//##########
		unset($_matches);
		//$content=r($content,CHARSET_HEAD,CHARSET_HEAD.NEWLINE);
		$content=self::CHARSET_HEAD.self::NEWLINE.$content;
		return $content;
	}
	public static function toRealDir($dir_,$filei)
	{
		$file=$filei;
		if(substr($filei,0,1)!='/' && strpos($filei,'://')===false){
			if(left($filei,3)=='../'){
				$n=0;
				while(left($filei,3)=='../'){
					$filei=substr($filei,3);
					if(right($dir_,1)=='/') $dir_=substr($dir_,0,-1);
					$dir_=substr($dir_,0,strrpos($dir_,'/')+1);
					if($n>50) break;
					$n++;
				}
				if(!$dir_) $dir_='/';
			}
			$file=$dir_.$filei;
		}
		return $file;
	}
	

	//public static 
	public static function cssCompiler($css)
	{
		$NO_FILTER = '/'.'*'.'!filter'.'*'.'/';
		if(ins($css,$NO_FILTER)>0) return r($css,$NO_FILTER,'');
		$less=new UILessc();

		$lessfmt=new lessc_formatter_classic();
		$lessfmt->disableSingle=true;
		//$lessfmt->breakSelectors=true;
		//$lessfmt="classic";
		
		//$less->setFormatter($lessfmt);
		$re=$less->compile($css.NEWLINE.self::getLessDefined());
		$less=null;
		$re=self::cssOptimizer($re);
		return $re;
	}
	public static function cssOptimizer($re,$pak=true)		//Optimize
	{
		$afind=[' {','  ',': ',' :'];
		$areplace=['{',TABS,':',':'];
		$re=str_replace($afind,$areplace,$re);
		
		$afind=['calc-('];
		$areplace=['calc(100% - '];
		$re=str_replace($afind,$areplace,$re);
		
		$afind=["\r\n","\r"];
		$areplace=["\n","\n"];
		$re=str_replace($afind,$areplace,$re);
		
		if($pak){
			$afind=[";\n\t",";\n}","{\n",TABS];
			$areplace=[";\t",";}",'{',''];
			$re=str_replace($afind,$areplace,$re);
		}
		return $re;
	}
	
	public static function getLessDefined()
	{
		$re='';
		//$basepath=appPaths('vdcs/web/',true,true);		// /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/
		$respath=appDirPath('vdcs.web/res');			// /Volumes/HDD/wwwroot/VDCS/VDCS.php/VDCS.php/web/res/
		$rootpath=appDirPath('root/images');			// /Volumes/HDD/wwwroot/VDCS/VDCS.php/www/images/
		if(defined('NOVDCS')){
			$respath=VDCS_WEB_PATH.'res/';
			$rootpath=_BASE_PATH_ROOT.'images/';
		}
		//debugx($respath);
		//debugx($rootpath);
		$re.=getFile($respath.'css/less/defined.css').NEWLINE;
		$re.=getFile($respath.'css/less/style.css').NEWLINE;
		$re.=getFile($rootpath.'css/define.less').NEWLINE;
		//die($re);
		return $re;
	}
	
	
	public static function jsPacker($s,$head='',$mode='Normal')
	{
		//include_once('JavaScriptPacker.php');
		$packer=new JavaScriptPacker($s, 'Normal', true, false);
		$prefix='';$postfix='';
		//$prefix=NEWLINE;$postfix=NEWLINE;
		if($head) $prefix=getFileContent(_BASE_PATH.'r/head'.(($head!='def')?('.'.$head):'').'.txt');
		return $prefix.trim($packer->pack()).$postfix;
	}
	
}
