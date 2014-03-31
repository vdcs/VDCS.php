<?
if(dcsNO()){
require_once(VDCS_PATH.VDCS_UTIL.'/utilIO.php');
require_once(VDCS_PATH.VDCS_UTIL.'/utilMimeType.php');
}
defined('XRES_PATH_CACHEX') || 			define('XRES_PATH_CACHEX',		_BASE_PATH_ROOT._BASE_DIR_DATA.'cache/themes/');
//require_once('ChannelXresType.php');

class ChannelXres
{
	
	public static function parser()
	{
		$debug=query('debug');$isdebug=!!$debug;
		//$isdebug=true;
		//debugx($_SERVER['REQUEST_URI']);
		//debugx($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING']);
		
		$res=query('res');
		$file=query('file');
		$ext=queryx('type');
		$paths=array(null,$res,$file,$ext);
		$filepath=WebRes::pathReal($paths,$patha,$isdebug);
		$isexist=!!$filepath;
		
		dcsExpires(30);
		if(inp('css,js',$ext)>0){
			$explain='';
			if(!$isexist){
				echo '/'.'* '.$res.'/'.$file.' no found. *'.'/';
				return;
			}
			
			$cache_path=XRES_PATH_CACHEX.r($res.'/'.$file,'/','_');
			if($isdebug) debugx('cache_path='.$cache_path);
			
			$path_content=$filepath;
			$iscache=false;
			if(@filemtime($cache_path)>@filemtime($filepath)){
				$path_content=$cache_path;
				$iscache=true;
			}
			
			$content=getFile($path_content);
			if(!$iscache){
				if($ext=='css'){
					timerBegin();
					$content=UIAssist::cssCompiler($content,$path_content,$debug);
					$lesscinfo='/'.'* CSS Compiler in: '.timerExec().' *'.'/';
					$content=$content.NEWLINE.$lesscinfo;
					//$content=$lesscinfo.NEWLINE.$content;
				}
				$explain=NEWLINE.'/'.'* '.$trd.'/'.$file.' , '.DCS::now().' *'.'/';
			}
			
			if($isdebug){
				debugx($filepath);
				if($isexist) debugx(strlen($content).','.filesize($filepath));
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
				debugx($filepath);
				return;
			}
			if($isexist) utilIO::outputImage($filepath);
			else{
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		else{	//'ttf,eot,woff,svg'
			if($isdebug){
				debugx($filepath);
				return;
			}
			if($isexist) utilIO::output($filepath);
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
