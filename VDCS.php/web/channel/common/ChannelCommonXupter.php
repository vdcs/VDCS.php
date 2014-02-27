<?
class ChannelCommonXupter extends WebPortalBase
{
	use WebServeRefRes;
	
	public function doParse()
	{
		global $xupter_types;
		$config_define=_BASE_PATH_ROOT.'common/config/xupter.php';
		if(isFile($config_define)) include($config_define);
		ChannelXupter::parser();
	}
	
}
