<?
if(dcsNO()){
require_once(VDCS_PATH.VDCS_UTIL.'/utilRegex.php');
require_once(VDCS_PATH.VDCS_LIB.'/UICSS.php');
require_once(VDCS_PATH.VDCS_LIB.'/UIPacker.php');
}

defined('XMERGER_PATH_CACHEX') || 			define('XMERGER_PATH_CACHEX',		_BASE_PATH_ROOT._BASE_DIR_DATA.'cache/themes/');

/*
/xmerger.app.js?
	/themes/demo/app.t-.js&
	/themes/demo/app.t.base.js&
	/themes/demo/app.t.compt.js&
	/themes/demo/app.t.mlog.js&
	/themes/demo/app.t.tags.js&
	/themes/demo/app.t.ucard.js&
	/themes/demo/app.t.talk.js&
	/themes/demo/app.t.editor.js&
	/themes/demo/app.t.put.js&
	/images/script/external/picsview.js&
/xmerger.app.js?/themes/demo/app.t-.js&/themes/demo/app.t.base.js&/themes/demo/app.t.compt.js&/themes/demo/app.t.mlog.js&/themes/demo/app.t.tags.js&/themes/demo/app.t.ucard.js&/themes/demo/app.t.talk.js&/themes/demo/app.t.editor.js&/themes/demo/app.t.put.js&/images/script/external/picsview.js
/xmerger.app.pak.js?/themes/demo/app.t-.js&/themes/demo/app.t.base.js&/themes/demo/app.t.compt.js&/themes/demo/app.t.mlog.js&/themes/demo/app.t.tags.js&/themes/demo/app.t.ucard.js&/themes/demo/app.t.talk.js&/themes/demo/app.t.editor.js&/themes/demo/app.t.put.js&/images/script/external/picsview.js
/xmerger.app.pak.js?/themes/test/app.js&/themes/test/base.js&/themes/test/compt.js
*/
class ChannelXmerger
{
	
	public static function parser()
	{
		//debugx($_SERVER["REQUEST_URI"]);
		//debugx($_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING']);
		//debugx(_BASE_PATH_ROOT);
		
		$querys=queryString();
		/*
		//debugx($querys);
		if(substr($querys,0,5)=='file='){		//nginx rewrite
			$n=ins($querys,'&');
			//debugx($n);
			$file=substr($querys,5,$n-6);
			$res=substr($querys,$n+4);
			$querys=$res.'&'.$file;
			//debugx($querys);
		}
		if(strlen($querys)<10) return;
		$querya=explode('&',$querys);
		//debuga($querya);
		
		$cache_file=end($querya);
		$cache_type=end(explode('.',$cache_file));
		unset($querya[count($querya)-1]);
		//debugx($cache_file.', type='.$cache_type);
		//debuga($querya);
		*/
		$file=query('file');
		$cache_file=$file;
		$cache_type=end(explode('.',$cache_file));
		//debugx($cache_file.', type='.$cache_type);
		//$res=query('res');
		$res=toSplit($querys,'&file='.$file.'&res=')[1];
		//debugs($res);
		$resa=explode('&',$res);
		//debuga($resa);
		
		$cache_mtime=0;
		foreach($resa as $value){
			$path=_BASE_PATH_ROOT.substr($value,1);
			if($cache_mtime<@filemtime($path)) $cache_mtime=@filemtime($path);
		}
		//debugx($cache_mtime);
		
		$cache_path=XMERGER_PATH_CACHEX.'xmerger.'.$cache_file;
		//debugx($cache_path);
		//debugx(@filemtime($cache_path));
		if(!strpos($cache_file,'.debug.')===false){
			doFileDel($cache_path);
		}
		if(isFile($cache_path) && @filemtime($cache_path)>$cache_mtime){
			self::output(getFileContent($cache_path),$cache_type,false);
			return;
		}
		
		$contents='';
		UICSS::v('PATH_ROOT',_BASE_PATH_ROOT);
		UICSS::v('cache_file',$cache_file);
		UICSS::v('url_compile','/xmerger.{$filev}{$px}.cssx?{$file}');
		switch($cache_type){
			case 'cssx':
				//debuga($resa);
				$contents=UICSS::toRealInclude($resa,'compile');
				break;
			case 'css':
				$contents=UICSS::toRealContents($resa);
				break;
			default:
				foreach($resa as $file){
					$path=_BASE_PATH_ROOT.substr($file,1);
					if(isFile($path)) $contents.=getFileContent($path);
				}
				break;
		}
		
		//debugx($cache_file);
		if(!strpos($cache_file,'.pak.')===false){
			//debugx($cache_file);
			switch($cache_type){
				case 'js':
					$contents=UIPacker::toPackerJS($contents,'');
					break;
				case 'css':
					
					break;
			}
		}
		
		doFileWrite($cache_path,$contents);
		self::output($contents,$cache_type,false);
		unset($content,$contents);
	}
	
	
	public static function output($content,$cache_type,$cache=false)
	{
		if(!$cache){
			//header('Etag:GPS',true,304);
			dcsExpires(30);
		}
		pageHeader($cache_type);
		echo $content;
		pageFlush();
	}
	
}
