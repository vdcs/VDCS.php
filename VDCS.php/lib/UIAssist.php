<?
defined('UIA_PATH_CACHEX') || 			define('UIA_PATH_CACHEX',		_BASE_PATH_ROOT._BASE_DIR_DATA.'cache/themes/');

class UIAssist
{
	const NEWLINE			= "\n";
	const CHARSET_HEAD		= '@charset "utf-8";';
	
	public static $_var		= [];
	
	
	public static function v($k,$v=null){if($v)self::$_var[$k]=$v;return self::$_var[$k];}
	
	public static function getCache($file){return getFileContent(UIA_PATH_CACHEX.$file.'.css');}
	public static function setCache($file,$content){doFileWrite(UIA_PATH_CACHEX.$file.'.css',$content);}
	

	public static function toRealDir($filei,$pathdir)
	{
		$file=$filei;
		if(substr($filei,0,1)!='/' && strpos($filei,'://')===false){
			if(left($filei,3)=='../'){
				$n=0;
				while(left($filei,3)=='../'){
					$filei=substr($filei,3);
					if(right($pathdir,1)=='/') $pathdir=substr($pathdir,0,-1);
					$pathdir=substr($pathdir,0,strrpos($pathdir,'/')+1);
					if($n>50) break;
					$n++;
				}
				if(!$pathdir) $pathdir='/';
			}
			$file=$pathdir.$filei;
		}
		return $file;
	}
	public static function getRealContent($file)
	{
		$filepath=WebRes::pathReal($file,$patha);//,true
		if(!$filepath){
			return '/'.'* file no found: '.$file.' *'.'/';
		}
		//##########
		static $paths=array();
		if(in_array($filepath,$paths)) return '';
		array_push($paths,$filepath);
		//##########
		$content=getFileContent($filepath);
		$content=self::toParseInclude($content,$filepath);
		return $content;
	}
	public static function toParseInclude($content,$path)
	{
		//debugvc($content);
		$pathdir=dirname($path).'/';
		//debugx('pathdir='.$pathdir);
		$pattern='/@include url\(\"([^\)\'\"]*)\"\);/ies';
		$_m=preg_match_all($pattern,$content,$_matches);
		//print_r($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$file=self::toRealDir($_matches[1][$m],$pathdir);
			//debugx('include.file='.$file);
			$rFlagValue=self::getRealContent($file,$pathdir);
			$content=r($content,$_matches[0][$m],$rFlagValue);
		}
		return $content;
	}


	public static function cssCompiler($css,$pathx=null,$debug=null)
	{
		$NO_FILTER = '/'.'*'.'!filter'.'*'.'/';
		if(ins($css,$NO_FILTER)>0) return r($css,$NO_FILTER,'');

		$css=self::toParseInclude($css,$pathx);
		if($debug=='include') return $css;

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
		$re.=getFile($respath.'css/less/lib.css').NEWLINE;
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
