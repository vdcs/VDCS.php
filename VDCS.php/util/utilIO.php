<?
class utilIO
{
	
	public static function outputContent($ext,$contents,$filesize,$charset=null,$expires=30)
	{
		if(!$filesize) $filesize=strlen($contents);
		ob_end_clean();
		ob_start();
		//dcsLog('io.output','expires='.$expires);
		if($expires){
			dcsExpires($expires);		//,true
		}
		else{
			//dcsLog('io.output','noCache');
			dcsNoCache();
		}
		//debugx($filesize.','.$expires.', Content-Type: '.self::toContentType($ext).($charset?'; charset='.$charset:''));
		header('Content-Length: '.$filesize);
		header('Content-Type: '.self::toContentType($ext).($charset?'; charset='.$charset:''));
		echo $contents;
		ob_flush();
	}
	
	public static function output($filepath,$expires=30)
	{
		$ext=pathinfo($filepath)['extension'];
		$filesize=filesize($filepath);
		$handle = fopen($filepath, 'rb');
		$contents = fread($handle, $filesize);
		fclose($handle);
		self::outputContent($ext,$contents,$filesize,null,$expires);
	}
	public static function outputImage($filepath,$expires=30)
	{
		self::output($filepath,$expires);
	}
	
	public static function download($file,$expires=30)
	{
		//echo $file;
		if(!isFile($file)) return;
		//$file=_FILE_DIR.$file;
		$fileext=self::toExt($file);
		$filetype=self::toContentType($fileext);
		$filesize=filesize($file);
		$filenames=getPathPart($file,'names');
		
		ob_end_clean();
		ob_start();
		if($expires){
			dcsExpires($expires);
		}
		else{
			dcsNoCache();
		}
		header('Content-Length: '.$filesize);
		if(stristr($filetype,'image')){
			$imagesize=@getimagesize('$file');
			if($imagesize){
				header('Content-Disposition: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename='.$filenames);
				header('Content-Type: '.$filetype);
				readfile($file);
			}
		}
		else{
			//if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
			//header('Content-Disposition: attachment;filename='.$filenames.'%20'); // For IE
			//}else{
			header('Content-Disposition: attachment; filename='.$filenames);
			//header('Content-Description: PHP3 Generated Data');
			//header('Content-Transfer-Encoding: binary');
			//}
			header('Content-Type: '.$filetype);
			readfile($file);
		}
		ob_flush();
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toExt($s)
	{
		return strtolower(substr(strrchr($s,'.'),1));
	}
	
	public static function toContentType($ext){return utilMimeType::get($ext);}
	public static function toMimeType($ext){return utilMimeType::get($ext);}
	
	
	/*
	########################################
	########################################
	*/
	public static function toDirVar($sdir,$sn='')
	{
		if(!$sdir) $sdir='{$y}{$m}/';
		if(right($sdir,1)!=DIR_SEPARATOR) $sdir.=DIR_SEPARATOR;
		utilString::lists3(DCS::today(),$_year,$_month,$_day,'-');
		$sdir=rd($sdir,'y',$_year);
		$sdir=rd($sdir,'m',$_month);
		$sdir=rd($sdir,'d',$_day);
		$sn=strval($sn);
		for($n=0;$n<5;$n++){
			$_snn=$sn{$n};if(!isset($_snn{0})) $_snn='0';
			$sdir=rd($sdir,'sn'.($n+1),$_snn);
		}
		return $sdir;
	}
	
	public static function dirBuildHash($dirbase,$mode='0777')
	{
		if(is_dir($dirbase.'00/')) return;
		if(!is_dir($dirbase)) mkdir($dirbase,$mode);
		$hexDigits=['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];
		foreach($hexDigits as $s1){
			$dir_=$s1.'/';
			//mkdir($dirbase.$dir_,$mode);
			foreach($hexDigits as $s2){
				$dir_=$s1.'/'.$s1.$s2.'/';
				$dir_=$s1.$s2.'/';
				mkdir($dirbase.$dir_,$mode);
			}
		}
	}
	
}
