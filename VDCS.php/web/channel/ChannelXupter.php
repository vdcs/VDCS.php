<?
if(defined('NOVDCS')){
require_once(VDCS_PATH.VDCS_UTIL.'/utilIO.php');
require_once(VDCS_PATH.VDCS_UTIL.'/utilMimeType.php');
require_once(VDCS_PATH.VDCS_UTIL.'/utilCoder.php');
require_once(VDCS_PATH.VDCS_UTIL.'/VDCSHTTP.php');
}

defined('XUPTER_DOMAINS') || 				define('XUPTER_DOMAINS',		APP_DOMAIN);
defined('XUPTER_PATH_UPLOAD') || 			define('XUPTER_PATH_UPLOAD',		_BASE_PATH_ROOT._BASE_DIR_UPLOAD);

defined('XUPTER_THUMB_WIDTH') || 			define('XUPTER_THUMB_WIDTH',		150);
defined('XUPTER_THUMB_HEIGHT') || 			define('XUPTER_THUMB_HEIGHT',		300);
defined('XUPTER_THUMBM_WIDTH') || 			define('XUPTER_THUMBM_WIDTH',		450);
defined('XUPTER_THUMBM_HEIGHT') || 			define('XUPTER_THUMBM_HEIGHT',		900);
//$xupter_types=['def'=>[260,170]];

class ChannelXupter
{
	
	public static function parser()
	{
		///common/upter.php?file=t/123.jpg&type=thumb
		///common/upter.php?file=t/123.jpg&type=thumbm
		$type=query('type');
		$file=query('file');
		//debugx($file);
		if(left($file,6)=='http:/' && left($file,7)!='http://') $file='http://'.substr($file,6);	//nginx
		//debugx($file);
		//dcsEnd();
		if(!$file){
			echo 'no file';
			return;
		}
		//dcsNoCache();
		dcsExpires(30);
		$basepath=XUPTER_PATH_UPLOAD;
		switch($type){
			case 'small':
			case 'thumb':
				self::outputThumb($basepath,$file,'small','',XUPTER_THUMB_WIDTH,XUPTER_THUMB_HEIGHT);
				break;
			case 'middle':
			case 'thumbm':
				self::outputThumb($basepath,$file,'middle','',XUPTER_THUMBM_WIDTH,XUPTER_THUMBM_HEIGHT);
				break;
			default:
				global $xupter_types;
				if(isset($xupter_types) && isset($xupter_types[$type])){
					$types=$xupter_types[$type];
					//debugx($types[0].','.$types[1]);
					self::outputThumb($basepath,$file,$type,'',$types[0],$types[1]);
				}
				else{
					self::output($basepath,$file);
				}
				break;
		}
	}
	
	
	public static function output($basepath,$file)
	{
		if(!$basepath) $basepath=XUPTER_PATH_UPLOAD;
		if(self::isURL($file)){
			$url=$file;
			if(self::isURL($url,1)) go($url);
			$savefile='';
			self::saveFile($url,$savefile,$basepath);
			//debugx($savefile);
			$file=$savefile;
			//return;
		}
		$filepath=$basepath.$file;
		if(!is_file($filepath)){
			echo 'file no found';
			return;
		}
		//debugx($filepath);
		obClean();
		utilIO::outputImage($filepath);
		obFlush();
	}
	
	public static function outputThumb($basepath,$file,$type,$thumbname,$thumbwidth=0,$thumbheight=0)
	{
		if(!$basepath) $basepath=XUPTER_PATH_UPLOAD;
		//debugx($file);
		if(self::isURL($file)){
			//debugx($file);
			$url=$file;
			if(self::isURL($url,1)) go($url);
			$savefile='';
			self::saveFile($url,$savefile,$basepath);
			//debugx($savefile);
			$file=$savefile;
			//return;
		}
		//dcsEnd();
		$filepath=$basepath.$file;
		//debugx($filepath);
		$paths=pathinfo($filepath);
		//debuga($paths);
		$filepathbase=$paths['dirname'].'/';
		$filename=$paths['filename'];
		$ext=$paths['extension'];
		$thumbext='jpg';
		
		switch($type){
			case 'middle':
				if(!$thumbname) $thumbname=$filename.'.thumbm';
				if(!$thumbwidth) $thumbwidth=XUPTER_THUMBM_WIDTH;
				if(!$thumbheight) $thumbheight=XUPTER_THUMBM_HEIGHT;
				break;
			case 'small':
				if(!$thumbname) $thumbname=$filename.'.thumb';
				if(!$thumbwidth) $thumbwidth=XUPTER_THUMB_WIDTH;
				if(!$thumbheight) $thumbheight=XUPTER_THUMB_HEIGHT;
				break;
			default:
				if(!$thumbname) $thumbname=$filename.'.'.$type;
				if(!$thumbwidth) $thumbwidth=XUPTER_THUMB_WIDTH;
				if(!$thumbheight) $thumbheight=XUPTER_THUMB_HEIGHT;
				break;
		}
		
		$thumbpath=$filepathbase.$thumbname.'.'.$thumbext;
		$showpath=$thumbpath;
		//debugx($showpath);
		if(!is_file($showpath)){
			$thumbpath=self::makeThumb($filepath,$thumbname,$thumbwidth,$thumbheight);
			$showpath=$thumbpath;
			//debugx($showpath);
			if(!is_file($showpath)) $showpath=$basepath.'no_pic.gif';
		}
		//debugx($showpath);
		self::output($showpath);
	}
	
	public static function makeThumb($filepath,$thumbname,$thumbwidth,$thumbheight)
	{
		$basepath=pathinfo($filepath)['dirname'].'/';
		$thumbpath=$basepath;
		$thumbname=utilPic::toMakeThumb($basepath,$filepath,$thumbpath,$thumbname,$thumbwidth,$thumbheight,0,75);
		//debugx($thumbname);
		return $basepath.$thumbname;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function isURL($file,$ismy=false)
	{
		$re=false;
		if(ins($file,'://')>0){
			$re=true;
			if($ismy){
				$re=false;
				if(ins($file,'.'.XUPTER_DOMAINS.'/')>0) $re=true;
			}
		}
		return $re;
	}
	
	
	public static function saveFile($url,&$savefile,$basepath=null)
	{
		if(!$basepath) $basepath=XUPTER_PATH_UPLOAD;
		utilIO::dirBuildHash($basepath.'web/');
		$urlmd5=utilCoder::toMD5($url);
		$filetype='jpg';
		$savefile='web/'.strtolower(substr($urlmd5,0,2)).'/'.$urlmd5.'.'.$filetype;
		$savepath=$basepath.$savefile;
		//debugx($savefile.' -- '.$savepath);
		if(!isFile($savepath)) return VDCSHTTP::curlSave($url,[],$savepath);
		return true;
	}
	
	
}
