<?
class ChannelXvcodei
{
	
	public static function parser($typed='si',$typea='')
	{
		ob_end_clean();
		if(defined('NOVDCS')){
			dcsInit(true,false);
		}
		$vcp=new VerifyCodeI();
		//debugs($_SERVER['SCRIPT_NAME'].' ? '.$_SERVER['QUERY_STRING']);
		$ext=query('ext');
		switch($ext){
			case 'xml':
			case 'xcml':
				$vcp->setMode('data');
				$vcp->doInit();
				initXMLS();
				pageXML();
				global $xmls;
				$xmls->putHead();
				$xmls->addVar('module',$vcp->getModule());
				$xmls->addVar('code',$vcp->getCode());
				$xmls->putMaps();
				break;
			case 'audio':
			case 'wav':
			case 'mp3':
				if(!$typea) $typea=$typed;
				$vcp->setMode('data');
				$vcp->doInit();
				$vcp->doAudio($typea);
				break;
			case 'pic':
			case 'png':
			default:
				$vcp->setMode('ui');
				$vcp->doInit();
				$vcp->doDraw($typed);
				break;
		}
		
		unset($vcp);
	}
	
}
?>