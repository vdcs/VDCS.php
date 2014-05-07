<?
class ResMessage
{
	
	public static function debugi()
	{
		global $theme;
		debugx('<div class="debugi">',false);
		debugx('TIMES = '.DCS::now().' , '.DCS::timer());
		debugx('REQUEST URI = '.$_SERVER['REQUEST_URI']);
		debugx('Script Info = '.$_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].' ? '.$_SERVER['QUERY_STRING']);
		debugx('BROWSER URL = '.DCS::browseURL(true));
		if(defined('APP_OBJECTNAME')) debugx('OBJECT NAME = '.APP_OBJECTNAME);
		if(defined('APP_OBJECTPATH')) debugx('OBJECT PATH = '.APP_OBJECTPATH);
		if(defined('APP_OBJECTPA')) debugx('OBJECT PA = '.APP_OBJECTPA);
		if($theme) debugx('THEMES FILE = '.$theme->getVar('RealFilePath'));
		debugx('Processed in '.dcsExecTime().' s, '.DB::getTotal().' queries. Gzip '.dcsGzipStatus().', Memory usage '.dcsMemoryUsage().'.');
		debugx(test::toTreeString(DB::getSQLTree(),'db.getSQLTree'));
		debugx('</div>',false);
	}
	
	public static function debugClass($classname)
	{
		debugx('Class no found: '.$classname);
		debugx('<div class="debugi">');
		debugx('REQUEST URI = '.$_SERVER['REQUEST_URI']);
		debugx('Script Info = '.$_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING']);
		debugx('BROWSER URL = '.DCS::browseURL(true));
		debugx('</div>',false);
	}

	public static function debugExit()
	{
		debugx('<p>Found a mistake. <a href="javascript:history.back(-1)">Back</a></p>',false);
	}


	public static function show($tit,$msg='[unknown]',$t=0)
	{
		global $_cfg;
		$re='';
		debugTrace();
		if(!is_array($msg)) $msg=array('message'=>$msg);
		if(DEBUG_TYPE==1){
			$re.=NEWLINE.'<xcml version="1.0" model="data">';
			$re.=NEWLINE.'	<error>';
			$re.=NEWLINE.'		<title>'.$tit.'</title>';
			$re.=NEWLINE.'		<message>'.utilCode::toXMLValue($msg['message']).'</message>';
			$re.=NEWLINE.'		<sql>'.utilCode::toXMLValue($msg['source']).'</sql>';
			$re.=NEWLINE.'	</error>';
			$re.=NEWLINE.'</xcml>';
		}
		else{
			if($t==1){
				@header('Content-Type:'.CONTENT_TYPE_HTML.'; charset='.CHARSET_PAGE);
				$_AppURL=isset($_cfg['app']['url.root'])?$_cfg['app']['url.root']:APP_VERSION_URL;
				//$re.='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';
				//$re.='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
				$re.='<!DOCTYPE HTML>';
				$re.=NEWLINE.'<html>';
				$re.=NEWLINE.'<head>';
				$re.=NEWLINE.'<meta charset="'.CHARSET_HTML.'">';
				$re.=NEWLINE.'<meta http-equiv="Content-Type" content="'.CONTENT_TYPE.'; charset='.CHARSET_HTML.'">';
				$re.=NEWLINE.'<title>'.APP_VERSION_NAME.' Message</title>';
				$re.=NEWLINE.'<link rel="stylesheet" rev="stylesheet" type="text/css" href="'.appURL('images').'css/style.vdcs.css" />';
				$re.=NEWLINE.'<script language="javascript" type="text/javascript" src="'.appURL('images').'script/jquery.min.js"></script>';
				$re.=NEWLINE.'<script language="javascript" type="text/javascript" src="'.appURL('images').'script/VDCS.js"></script>';
				$re.=NEWLINE.'</head>';
				$re.=NEWLINE.'<body class="message">';
				$re.=NEWLINE.'<div class="box_error"><div class="inners">';
				$re.=NEWLINE.'<h2><t><a href="'.$_AppURL.'"><span class="txt">'.APP_VERSION_NAME.'</span></a></t><cite> - <span>'.$tit.'</span></cite></h2>';
				$re.=NEWLINE.'<div class="message"><div class="inner">';
				$re.=NEWLINE.'       <div class="title"><span>Message <a href="#copy">copy</a></span></div>';
				$re.=NEWLINE.'       <div class="content">'.self::toContent('message',$msg['message'],'center').'</div>';
				$re.=NEWLINE.'</div></div>';
				if(isset($msg['description']) || isset($msg['source'])){
					$re.=NEWLINE.'<div class="reference"><div class="inner">';
					$re.=NEWLINE.'       <div class="title"><span>Reference</span></div>';
					//$re.=NEWLINE.'       <div class="content">'.$msg['description'].'</div>';
					if(len($msg['number'])>0) $re.=NEWLINE.'       <div class="content"><span class="error">Error #<em>'.$msg['number'].'</em></span></div>';
					$re.=NEWLINE.'       <div class="content">'.self::toContent('description',$msg['description'],'left').'</div>';
					if(len($msg['source'])>0) $re.=NEWLINE.'       <div class="source">'.$msg['source'].'</div>';
					$re.=NEWLINE.'</div></div>';
				}
				$re.=NEWLINE.'<h4><a href="'.appURL('root').'"><span>Index</span></a> &nbsp; <a href="javascript:history.back();"><span>Back</span></a></h4>';
				$re.=NEWLINE.'<h5><span class="stat">Processed in <em>'.dcsExecTime().'</em> s, <em>'.DB::getTotal().'</em> queries. Memory usage <em>'.dcsMemoryUsage().'</em>.</span> '.appWebVersion().'</h5>';
				$re.=NEWLINE.'</div></div>';
				$re.=NEWLINE.'</body>';
				$re.=NEWLINE.'</html>';
			}
			else{
				$re.=NEWLINE.'<div style="padding-left:30px;">';
				$re.=NEWLINE.'<h3>'.$tit.'</h3>';
				$re.=NEWLINE.'<p>'.$msg['message'].'</p>';
				$re.=NEWLINE.'</div>';
			}
		}
		echo $re;unset($re);
		if($t==1){
			_autoload_::save();
			//dcsClear();
			dcsEnd();
		}
	}
	public static function toContent($k,$s,$align='')
	{
		return '<table cellpadding="0" cellspacing="0" width="100%" class="table-break"><tr><td class="word-break"><div class="'.($align?('align-'.$align):'').' word-break" id="VDCS-content-'.$k.'">'.$s.'</div></td></tr></table>';
	}
}
?>