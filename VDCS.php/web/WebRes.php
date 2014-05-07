<?
class WebRes
{
	
	public function pathReal($path,&$patha=null,$isdebug=false)
	{
		/*
		/images/css/style.html5.css?d=20140302
		/px.php?px=res&res=images&type=css&file=css/style.html5.css&d=20140302
		/themes/demo/images/style.css?d=20140302
		/px.php?px=res&res=themes&type=css&file=demo/images/style.css&d=20140302
		/manage/themes/login/login.css?d=20140302
		/px.php?px=res&res=manage/themes&type=css&file=login/login.css&d=20140302
		*/
		if($isdebug) debugx('WebRes::pathReal');
		$patha=$path;
		if(!is_array($path)){
			if($isdebug) debugx('path='.$path);
			//RewriteRule ^/(images|themes|manage/themes)/(.[^\?]*)\.(\w+)(\?(.*))?$			/px.php\?px=res&res=$1&type=$3&file=$2.$3&$5 [N,I]
			$pattern='/\/(images|themes|manage\/themes)\/(.[^\?]*)\.(\w+)(\?(.*))?/ies';
			$_m=preg_match_all($pattern,$path,$_matches);
			//if($isdebug) print_r($_matches);
			if($_m!=1) return '';
			$patha=array($_matches[0][0],$_matches[1][0],$_matches[2][0].'.'.$_matches[3][0],$_matches[3][0]);
		}
		if($isdebug) debuga($patha);
		$res=$patha[1];		//query('res');
		$file=$patha[2];	//query('file');
		$ext=$patha[3];		//queryx('type');
		if(!$ext) $ext=getPathPart($file,'ext');
		if($isdebug) debugx('res='.$res.', file='.$file.', type='.$ext);
		
		if($isdebug){
			$basepath=appPaths('vdcs/web/',true,true);
			$rootpath=appPaths('root/',true,true);			//appDirPath('root');
			debugx('basepath='.$basepath.', rootpath='.$rootpath);
		}

		$isexist=true;
		$filepath=appPaths(self::$RESDIR_ROOT[$res],true,true).$file;
		if($isdebug) debugx('filepath.root='.$filepath);
		if(!isFile($filepath)){
			$basepath=appPaths('vdcs/web/',true,true);
			$resdir=self::$RESDIR_BASE[$res];
			$filepath=$basepath.$resdir.$file;
			if($isdebug) debugx('filepath.base='.$filepath);
			if(!isFile($filepath)){
				$isexist=false;
				if(substr($file,0,7)=='themes/'){
					$isexist=true;
					$resdir=self::$RESDIR_BASE['themes'];
					$filepath=$basepath.$resdir.substr($file,7);
					if($isdebug) debugx('filepath.base='.$filepath);
					if(!isFile($filepath)) $isexist=false;
				}
			}
		}
		if($isdebug){
			debugx('filepath='.$filepath);
			debugx('isexist='.($isexist?'true':'false'));
		}
		
		// type parser
		//$otype=self::typeParser($ext);

		return $isexist?$filepath:null;
	}


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
	static $RESDIR_ROOT=array(
		'images'=>'images/',
		'themes'=>'themes/',
		'manage/themes'=>'manage/themes/',
		);

	public static function typeParser($type)
	{
		$classname='XresType'.ucfirst(self::$TYPES[$type]?self::$TYPES[$type]:'other');
		return new $classname();
	}

}
