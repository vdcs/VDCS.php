<?
if(dcsNO()){
require_once(VDCS_PATH.VDCS_UTIL.'/utilIO.php');
require_once(VDCS_PATH.VDCS_UTIL.'/utilMimeType.php');
}
defined('XRES_PATH_CACHEX') || 			define('XRES_PATH_CACHEX',		_BASE_PATH_ROOT._BASE_DIR_DATA.'cache/themes/');
//require_once('ChannelXresType.php');

class ChannelXres
{
	static $TYPES=array(
		'css'=>'css',
		'js'=>'js',
		'png'=>'img',
		'gif'=>'img',
		'jpg'=>'img',
		'jpeg'=>'img',
		'bmp'=>'img',
		'other'=>'other'
		);
	static $RESDIR_BASE=array(
		'images'=>'res/',
		'themes'=>'themes/',
		'manage/themes'=>'manage/themes/default/',
		);

	public static function typeParser($type)
	{
		$classname='XresType'.ucfirst(self::$TYPES[$type]?self::$TYPES[$type]:'other');
		return new $classname();
	}

	public static function parser()
	{
		$isdebug=false;
		if(query('debug')) $isdebug=true;
		//$isdebug=true;
		//debugx($_SERVER['REQUEST_URI']);
		//debugx($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING']);
		/*
		/images/css/style.html5.css?d=20140302
		/px.php?px=res&res=images&type=css&file=css/style.html5.css&d=20140302
		/themes/demo/images/style.css?d=20140302
		/px.php?px=res&res=themes&type=css&file=demo/images/style.css&d=20140302
		/manage/themes/login/login.css?d=20140302
		/px.php?px=res&res=manage/themes&type=css&file=login/login.css&d=20140302
		*/
		$res=query('res');
		$file=query('file');
		$ext=queryx('type');
		if(!$ext) $ext=getPathPart($file,'ext');
		if($isdebug) debugx('res='.$res.', file='.$file.', type='.$ext);
		
		if($isdebug){
			$basepath=appPaths('vdcs/web/',true,true);
			$rootpath=appPaths('root/',true,true);			//appDirPath('root');
			debugx('basepath='.$basepath.', rootpath='.$rootpath);
		}

		$isexist=true;
		$rootpath=appPaths('root/',true,true);			//appDirPath('root');
		$path=$rootpath.$res.'/'.$file;
		if($isdebug) debugx('filepath.root='.$path);
		if(!isFile($path)){
			$basepath=appPaths('vdcs/web/',true,true);
			$resdir=self::$RESDIR_BASE[$res];
			$path=$basepath.$resdir.$file;
			if($isdebug) debugx('filepath.base='.$path);
			if(!isFile($path)){
				$isexist=false;
				if(substr($file,0,7)=='themes/'){
					$isexist=true;
					$resdir=self::$RESDIR_BASE['themes'];
					$path=$basepath.$resdir.substr($file,7);
					if($isdebug) debugx('filepath.base='.$path);
					if(!isFile($path)) $isexist=false;
				}
			}
			
		}
		if($isdebug) debugx('filepath='.$path);
		
		// type parser
		//$otype=self::typeParser($ext);

		dcsExpires(30);
		if(inp('css,js',$ext)>0){
			$explain='';
			if(!$isexist){
				echo '/'.'* '.$res.'/'.$file.' no found. *'.'/';
				return;
			}

			$cache_path=XRES_PATH_CACHEX.r($res.'/'.$file,'/','_');
			if($isdebug) debugx('cache_path='.$cache_path);
			
			$path_content=$path;
			$iscache=false;
			if(@filemtime($cache_path)>@filemtime($path)){
				$path_content=$cache_path;
				$iscache=true;
			}
			
			$content=getFile($path_content);
			if(!$iscache){
				if($ext=='css'){
					timerBegin();
					$content=UIAssist::cssCompiler($content);
					$lesscinfo='/'.'* CSS Compiler in: '.timerExec().' *'.'/';
					$content=$content.NEWLINE.$lesscinfo;
					//$content=$lesscinfo.NEWLINE.$content;
				}
				$explain=NEWLINE.'/'.'* '.$trd.'/'.$file.' , '.DCS::now().' *'.'/';
			}
			
			if($isdebug){
				debugx($path);
				if($isexist) debugx(strlen($content).','.filesize($path));
				debugvc($content);
			}
			else{
				//if(!ISLOCAL) 
				doFileWrite($cache_path,$content.$explain);
				utilIO::outputContent($ext,$content.$explain,0,CHARSET);
			}
			unset($content);
		}
		elseif(inp('png,jpg,gif',$ext)>0){
			if($isdebug){
				debugx($path);
				return;
			}
			if($isexist) utilIO::outputImage($path);
			else{
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		else{	//'ttf,eot,woff,svg'
			if($isdebug){
				debugx($path);
				return;
			}
			if($isexist) utilIO::output($path);
			else{
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		//pageFlush();
	}
	
}
/*
		dcsExpires(30);
		//header("Expires: " . gmdate("D, d M Y H:i:s", time() + 15360000) . "GMT");
		//header("Cache-Control: max-age=315360000");
*/
