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
	
}
