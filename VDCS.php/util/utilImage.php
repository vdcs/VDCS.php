<?
class utilImage
{
	
	public static function getMimeType($input)
	{
		return image_type_to_mime_type(exif_imagetype($input));
	}
	
	
	public static function getObject($input,&$im=null)
	{
		$_ext=utilFile::getPathPart($input,'ext');
		switch($_ext){
			case 'gif':		$im=imagecreatefromgif($input);break;
			case 'png':		$im=imagecreatefrompng($input);break;
			case 'jpeg':
			case 'jpg':		$im=imagecreatefromjpeg($input);break;
			case 'bmp':		$im=imagecreatefromwbmp($input);break;
		}
		return $im;
	}
	
	
	public static function doFileConver($fmtFrom,$input,$fmtTo,$output)
	{
		$re=false;
		if(!$fmtFrom) $fmtFrom=utilFile::getPathPart($input,'ext');
		$im=self::getObject($input);
		if(!$im) return false;
		$re=true;
		if(!$fmtTo) $fmtTo=utilFile::getPathPart($output,'ext');
		switch($fmtTo){
			case 'jpeg':
			case 'jpg':	imagejpeg($im,$output,100);break;
			case 'png':	imagepng($im,$output);break;
			case 'gif':	imagegif($im,$output);break;
			case 'bmp':	imagewbmp($im,$output);break;
			default:	$re=false;break;
		}
		imagedestroy($im);
		return $re;
	}
	
	
	/*
	图象缩略函数 适用于不同的图象存在不同的目录中。
	参数说明：$srcfile	原图地址；
		  $dir		新图目录
		  $thumbwidth	缩小图宽最大尺寸
		  $thumbheitht	缩小图高最大尺寸
		  $ratio	0为等比例缩放 1为缩小到固定尺寸。
	*/
	public static function toMakeThumb($basepath,$srcfile,$thumbpath,&$thumbname,$thumbwidth,$thumbheight,$ratio=0,$quality=75)
	{
		$filename=$srcfile;
		if(ins($srcfile,PATH_SYMBOL)<1&&left($srcfile,1)!=PATH_PX) $srcfile=$basepath.$srcfile;
		if(!is_file($srcfile)) return '';			//判断文件是否存在
		if(!$thumbpath) $thumbpath=$basepath;
		if(!$thumbname) $thumbname=getPathPart($srcfile,'name').'.thumb';
		$thumbnames=$thumbname;$thumbext='.jpg';
		$thumbpath=$thumbname;					//生成新的同名文件，但目录不同
		if(ins($thumbpath,PATH_SYMBOL)<1 && left($thumbpath,1)!=PATH_PX) $thumbpath=$basepath.$thumbpath;
		//debugx($thumbpath);
		$tow=$thumbwidth;$toh=$thumbheight;			//缩略图大小
		if($tow<40) $tow=40;if($toh<40) $toh=40;
		$im=null;
		if($data=getimagesize($srcfile)){			//获取图片信息
			if($data[2]==2){
				if(function_exists('imagecreatefromjpeg')) $im=imagecreatefromjpeg($srcfile);
			}
			elseif($data[2]==3){
				if(function_exists('imagecreatefrompng')) $im=imagecreatefrompng($srcfile);
			}
			elseif($data[2]==1){
				if(function_exists('imagecreatefromgif')) $im=imagecreatefromgif($srcfile);
			}
		}
		if(!$im) return false;
		$srcw=imagesx($im);$srch=imagesy($im);
		$towh=$tow/$toh;
		$srcwh=$srcw/$srch;
		if($towh<=$srcwh){
			$ftow=$tow;
			$ftoh=$ftow*($srch/$srcw);
		}
		else{
			$ftoh=$toh;
			$ftow=$ftoh*($srcw/$srch);
		}
		if($ratio){
			$ftow=$tow;
			$ftoh=$toh;
		}
		//debugx($ftow.','.$ftoh);
		if($srcw>$tow || $srch>$toh || $ratio){			//缩小图片
			//
		}
		else{							//小于尺寸直接复制
			/*
			//$thumbext='.'.getPathPart($filename,'ext');
			$thumbnames.=$thumbext;
			$thumbpath.=$thumbext;
			//debugx($thumbpath);
			copy($srcfile,$thumbpath);
			*/
			$ftow=$srcw;$ftoh=$srch;
		}
		//##########
		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && @$ni=imagecreatetruecolor($ftow,$ftoh)){
			imagecopyresampled($ni,$im,0,0,0,0,$ftow,$ftoh,$srcw,$srch);
		}
		elseif(function_exists('imagecreate') && function_exists('imagecopyresized') && @$ni=imagecreate($ftow,$ftoh)){
			imagecopyresized($ni,$im,0,0,0,0,$ftow,$ftoh,$srcw,$srch);
		}
		else{
			return false;
		}
		if(!function_exists('imagejpeg')){
			return false;
		}
		$thumbnames.=$thumbext;
		imagejpeg($ni,$thumbpath.$thumbext,$quality);
		//##########
		imagedestroy($im);
		return is_file($thumbpath.$thumbext)?$thumbnames:false;
	}
	
}
