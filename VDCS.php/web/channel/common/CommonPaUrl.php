<?
class CommonPaUrl extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		global $cfg;
		$channel=queryx('channel');
		$module='';
		$id=queryi('id');
		if(len($channel)>0){
			$page='view';
			if(ins($channel,'.')>0){
				utilString::lists($channel,$channel,$module,'.');
			}
			if(len($module)>0){
				$page.='.'.$module;
			}
			$url=$cfg->getLinkURL($channel,$page,'id='.$id);
		}
		if(len($url)<1) $url=appURL('root');
		//debugx($url);
		go($url);
	}
	
	public function doThemeCache(&$that)
	{
		
	}
	
}
?>