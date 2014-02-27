<?
class PageResPortal extends ManagePortalBase
{
	use WebServeRefRes;
	
	public function doAuth()
	{
		$this->doAuthe();
		$this->ruler->setMode('ignore');
	}
	
	public function doParse()
	{
		$file=query('file');
		//debugx($file);
		$ext=getPathPart($file,'ext');
		//debugx($ext);
		
		$basepath=appDirPath('vdcs.mthemes/'.MANAGE_THEME);		//manage.themes
		//debugx($basepath);
		$path=appDirPath('manage.themes/'.MANAGE_THEME_APP).$file;
		$isexist=true;
		//debugx($path);
		if(!isFile($path)){
			$path=$basepath.$file;
			//debugx($path);
			if(!isFile($path)){
				$isexist=false;
			}
		}
		
		dcsExpires(30);
		if(inp('png,jpg,gif',$ext)>0){
			if($isexist) utilIO::outputImage($path);
			else{
				header('Content-type: image/gif');
				header('Content-length: 43');
				echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');
			}
		}
		else{
			pageHeader($ext);
			if($isexist){
				$content=getFile($path);
			}
			else{
				$content='/* '.$file.' no found. */';
			}
			echo $content;
			echo NEWLINE.'/* '.$file.' , '.DCS::now().' */';
		}
		pageFlush();
		
		unset($content);
	}
	
}
?>