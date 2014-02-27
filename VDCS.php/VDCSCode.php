<?
class VDCSCode
{
	
	public static function toEncodeFilterValue($s,$fmt,$params='',&$isv=true)
	{
		if(!$fmt) return $s;
		global $cfg;
		switch($fmt){
			case 'url.uid':
				$re=urlLink($cfg->getLinkURL('account',APP_UA),'id='.$s);
				break;
			case 'url.uname':
				$re=urlLink($cfg->getLinkURL('account',APP_UA),'name='.DCS::urlEncode($s));
				break;
			case 'user.link':
				$url=urlLink($cfg->getLinkURL('account',APP_UA),'name='.DCS::urlEncode($s));
				$re='<a href="'.$url.'" target="_blank">'.$s.'</a>';
				break;
			//case 'num':		$re=toNum($s); break;
			default :		$re=$s; $isv=false; break;
		}
		return $re;
	}
	
}
?>