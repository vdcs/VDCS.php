<?
if(defined('NOVDCS')){
require_once(VDCS_PATH.VDCS_UTIL.'/utilIO.php');
require_once(VDCS_PATH.VDCS_UTIL.'/utilMimeType.php');
}

class ChannelXres
{
	
	public static function parser()
	{
		$isdebug=false;
		//$isdebug=true;
		//debugx($_SERVER["REQUEST_URI"]);
		//debugx($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING']);
		//$res=query('res');
		//debugx('res='.$res.', dir='.$dir);
		$file=query('file');
		//debugx($file);
		$ext=getPathPart($file,'ext');
		//debugx($ext);
		$dir1=toSplit($file,'/')[0];
		//debugx($dir1);
		
		$basepath=appPaths('vdcs/web/',true,true);
		$respath=appDirPath('vdcs.web/res');
		$rootpath=appDirPath('root/images');
		if(defined('NOVDCS')){
			$respath=VDCS_WEB_PATH.'res/';
			$rootpath=_BASE_PATH_ROOT.'images/';
		}
		if($isdebug) debugx($basepath.' , '.$respath.' , '.$rootpath);
		
		$dirpath='';
		//if($res) $dirpath.=$res.'/';
		
		$respath.=$dirpath;
		$rootpath.=$dirpath;
		
		$path=$rootpath.$file;
		if($isdebug) debugx('root: '.$path);
		$isexist=true;
		$place='app';
		if($dir1=='themes' && !isFile($path)){
			$place='themes';
			$path=$basepath.$file;
			if($isdebug) debugx('themes: '.$path);
		}
		if(!isFile($path)){
			$place='res';
			$path=$respath.$file;
			if($isdebug) debugx('res: '.$path);
		}
		if(!isFile($path)){
			$isexist=false;
		}
		//debugx($path);
		//return;
		
		/*
		$respath=appDirPath('vdcs.mthemes/'.MANAGE_THEME);		//manage.themes
		debugx($respath);
		$path=$respath.$file;
		$isexist=true;
		if(!isFile($path)){
			$path=appDirPath('manage.themes/'.MANAGE_THEME_APP).$file;
			//debugx($path);
			if(!isFile($path)){
				$isexist=false;
			}
		}
		*/
		
		//dcsExpires(30);
		//header("Expires: " . gmdate("D, d M Y H:i:s", time() + 15360000) . "GMT");
		//header("Cache-Control: max-age=315360000");
		if(inp('css,js',$ext)>0){
			$explain='';
			if($isexist){
				$content=getFile($path);
				$explain=NEWLINE.'/'.'* '.$place.': '.$file.' , '.(defined('NOVDCS')?gmdate("D, d M Y H:i:s", time() + 15360000):DCS::now()).' *'.'/';
			}
			else{
				$content='/'.'* '.$file.' no found. *'.'/';
			}
			if($isdebug){
				debugx($path);
				if($isexist) debugx(strlen($content).','.filesize($path));
				debugvc($content);
			}
			else{
				utilIO::outputContent($ext,$content.$explain,0,CHARSET);
			}
		}
		elseif(inp('png,jpg,gif',$ext)>0){
			if($isexist) utilIO::outputImage($path);
			else{
				dcsExpires(30);
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		else{	//'ttf,eot,woff,svg'
			if($isexist) utilIO::output($path);
			else{
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		//pageFlush();
		
		unset($content);
	}
	
}
