<?
class DemoPaTest extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		
		$sql='select * from dbu_pivotal';
		//$that->tableList=DB::queryTable($sql);
		
		$message=<<<EOF
哈哈，测试内容来了http://www.abc.com/demo.html?action=list&id=12121$550d
EOF;
		$re=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|mms|rtsp):\/\/)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i", '<a href="\\1\\3" target="_blank">\\1\\3</a>', $message);
		debugx($re);

	}
	
	public function doThemeCache(&$that)
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		//$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
