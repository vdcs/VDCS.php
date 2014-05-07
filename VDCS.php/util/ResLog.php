<?
class ResLog
{
	//__invoke
	//__callStatic

	public static function save($filename,$sort,$msg){
		$datei=datei('Y-m-j');
		if($filename=='today') $filename='{date}';
		$filename=rv($filename,'today',$datei);
		$filename=rv($filename,'date',$datei);
		$filename=rv($filename,'datei',r($datei,'-',''));
		$fp=fopen(appDirPath('data.log/').$filename.EXT_LOG,'a');
		if($fp){
			flock($fp,3);
			$content='';
			if($sort){
				$content=datei('Y-m-d H:i:s').TABS.'['.$sort.']'.TABS.$msg.TABS.''.$_SERVER['REQUEST_URI'].NEWLINE;
			}
			else{
				$content=NEWLINE.datei('Y-m-d H:i:s').TABS.$_SERVER['REQUEST_URI'].NEWLINE.$msg.NEWLINE;
			}
			fwrite($fp,$content);
			fclose($fp);
		}
	}
	
	public static function action($name='action',$value='')
	{
		if(!$name) $name='action';
		$content='';
		$content.=NEWLINE.DCS::now();
		$content.=NEWLINE.'Request URI: '.$_SERVER['REQUEST_URI'];
		$content.=NEWLINE.'Script Info: '.$_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING'];
		$content.=NEWLINE.'BROWSER URL: '.DCS::browseURL(true);
		$content.=NEWLINE.'sid: '.session_id().' , '.$_REQUEST['PHPSESSID'];
		$content.=NEWLINE.'ip: '.DCS::ip().' , '.DCS::agent();
		$content.=NEWLINE.NEWLINE.'_GET'.NEWLINE.ResTest::pr($_GET,true).NEWLINE.'_POST'.NEWLINE.ResTest::pr($_POST).$GLOBALS["HTTP_RAW_POST_DATA"].NEWLINE;
		if($value) $content.=NEWLINE.NEWLINE.$value;
		doFileWrite(appFilePath('data/log/'.$name.'.log'),NEWLINE.$content.NEWLINE,FILE_APPEND);
	}
	
}
