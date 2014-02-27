<?
class VisitBrowserParser
{
	//浏览器解析
	public static function getAgentBrowser($agent)
	{
		$browser='';
		$bver='';
		$Browsers=array('Lynx','MOSAIC','AOL','Opera','JAVA','MacWeb','WebExplorer','OmniWeb');
		for($i=0; $i <= 7; $i ++){
			if(strpos($agent,$Browsers[$i])){
				$browser=$Browsers[$i];
				$bver='';
			}
		}
		if(ereg('Mozilla',$agent)){
			if(ereg('MSIE',$agent)){
				$_matches=utilRegex::toMatches($agent,'MSIE ([\d\.]*);');
				//debugAry($_matches);
				if(count($_matches)==2){
					$bver=$_matches[1][0];
				}
				$bw='ie';
				$bwc='ie';
				$browser='Internet Explorer';
				/*if(ereg('360SE',$agent)){
					$bw='360se';
					$browser='360se';
				}*/
			}
			else if(ereg('Firefox',$agent)){
				$_matches=utilRegex::toMatches($agent,'Firefox\/([\d\.]*)');
				//debugAry($_matches);
				if(count($_matches)==2){
					$bver=$_matches[1][0];
				}
				$bw='firefox';
				$bwc='firefox';
				$browser='Firefox';
			}
			else if(ereg('Chrome',$agent)){
				$_matches=utilRegex::toMatches($agent,'Chrome\/([\d\.]*)');
				//debugAry($_matches);
				if(count($_matches)==2){
					$bver=$_matches[1][0];
				}
				$bw='chrome';
				$bwc='chrome';
				$browser='Chrome';
			}
			else if(ereg('Safari',$agent)){
				$_matches=utilRegex::toMatches($agent,'Version\/([\d\.]*)');
				//debugAry($_matches);
				if(count($_matches)==2){
					$bver=$_matches[1][0];
				}
				$bw='safari';
				$bwc='safari';
				$browser='Safari';
			}
			else if(ereg('Googlebot',$agent)){
				$_matches=utilRegex::toMatches($agent,'Googlebot\/([\d\.]*)');
				//debugAry($_matches);
				if(count($_matches)==2){
					$bver=$_matches[1][0];
				}
				$bw='googlebot';
				$bwc='googlebot';
				$browser='Googlebot';
			}
			
			else if(!ereg('MSIE',$agent)){
				$bw='ns';
				$bwc='ns';
				$browser='Netscape';
			}
		}
		else if(ereg('Opera',$agent)){
			$_matches=utilRegex::toMatches($agent,'Version\/([\d\.]*)');
			//debugAry($_matches);
			if(count($_matches)==2){
				$bver=$_matches[1][0];
			}
			$bw='opera';
			$bwc='opera';
			$browser='Opera';
		}
		unsetr($_matches);
		if($browser!=''&&$bver) $browseinfo=$browser.' '.$bver;
		if($bw!=''&&$bver) $bwinfo=$bw.'-'.$bver;
		if($bwc!=''&&$bver) $bwcinfo=$bwc.'-'.$bver;
		$re=array();
		$re['browser']=$bwinfo;
		$re['browserc']=$bwcinfo;
		$re['browser.name']=$browseinfo;
		return $re;
	}
	
	//操作系统解析
	public static function getAgentOS($agent)
	{
		$os='';
		$osv='';
		if (eregi('win',$agent) && eregi('nt 5.1',$agent)){
			$os='win-xp';
			$osv='Windows XP';
		}
		else if (eregi('win',$agent) && eregi('nt 5.2',$agent)){
			$os='win-2003';
			$osv='Windows Server 2003';
		}
		else if (eregi('win',$agent) && eregi('nt 5',$agent)){
			$os='win-2000';
			$osv='Windows 2000';
		}
		else if (eregi('win',$agent) && eregi('nt 6.1',$agent)){
			$os='win-7';
			$osv='Windows 7';
		}
		else if (eregi('win',$agent) && eregi('nt 6.1',$agent)){
			$os='win-2008';
			$osv='Windows Server 2008';
		}
		else if (eregi('win',$agent) && eregi('nt 6',$agent)){
			$os='win-vista';
			$osv='Windows Vista';
		}
		else if (eregi('win',$agent) && eregi('nt',$agent)){
			$os='win-nt';
			$osv='Windows NT';
		}
		else if (eregi('linux',$agent)){
			$os='linux';
			$osv='Linux';
		}
		else if (eregi('unix',$agent)){
			$os='unix';
			$osv='Unix';
		}
		else if (eregi('sun',$agent) && eregi('os',$agent)){
			$os='sunos';
			$osv='SunOS';
		}
		else if (eregi('ibm',$agent) && eregi('os',$agent)){
			$os='os2';
			$osv='IBM OS/2';
		}
		else if (eregi('Mac',$agent) && eregi('PC',$agent)){
			$os='mac';
			$osv='Macintosh';
		}
		else if (eregi('FreeBSD',$agent)){
			$os='freebsd';
			$osv='FreeBSD';
		}
		else if (eregi('NetBSD',$agent)){
			$os='netbsd';
			$osv='NetBSD';
		}
		else if (eregi('BSD',$agent)){
			$os='bsd';
			$osv='BSD';
		}
		else if (eregi('PowerPC',$agent)){
			$os='powerpc';
			$osv='PowerPC';
		}
		else if (eregi('AIX',$agent)){
			$os='aix';
			$osv='AIX';
		}
		else if (eregi('HPUX',$agent)){
			$os='hpux';
			$osv='HPUX';
		}
		else if (ereg('OSF1',$agent)){
			$os='osf1';
			$osv='OSF1';
		}
		else if (ereg('IRIX',$agent)){
			$os='irix';
			$osv='IRIX';
		}
		else if (eregi('teleport',$agent)){
			$os='teleport';
			$osv='teleport';
		}
		else if (eregi('flashget',$agent)){
			$os='flashget';
			$osv='flashget';
		}
		else if (eregi('webzip',$agent)){
			$os='webzip';
			$osv='webzip';
		}
		else if (eregi('offline',$agent)){
			$os='offline';
			$osv='offline';
		}
		else if (eregi('win',$agent) && ereg('98',$agent)){
			$os='win-98';
			$osv='Windows 98';
		}
		else if (eregi('win 9x',$agent) && strpos($agent,'4.90')){
			$os='win-me';
			$osv='Windows ME';
		}
		else if (eregi('win',$agent) && strpos($agent,'95')){
			$os='win-95';
			$osv='Windows 95';
		}
		else if (eregi('win',$agent) && ereg('32',$agent)){
			$os='';
			$osv='Windows 32';
		}
		else {
			$os='';
			$osv='';
		}
		$re=array();
		$re['os']=$os;
		$re['os.name']=$osv;
		return $re;
	}
}
?>