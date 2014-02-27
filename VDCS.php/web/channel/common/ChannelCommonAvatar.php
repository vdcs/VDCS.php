<?
class ChannelCommonAvatar extends WebPortalBase
{
	use WebServeRefRes;
	/*
	public function doAuth()
	{
		$this->doAuther();
	}
	*/
	public function doParse()
	{
		//id=10001&res=x48
		$uid=queryi('uid');
		$res=query('res');
		$resType='small';
		switch($res){
			case 's':
			case 'x24':case 'x20':
			case 'small':		$resType='small';break;
			case 'm':
			case 'x48':case 'x40':
			case 'middle':		$resType='middle';break;
			case 'b':
			case 'x96':case 'x80':
			case 'big':
			//case 'face':		
			//case 'photo':
			default:		$resType='big';break;
		}
		//$filename=$uid.'_avatar_'.$resType;
		$filename=$uid.'_avatar_{type}';
		$dirbase=$this->toVarDirParser('{$sn1}/{$sn2}{$sn3}/',$uid);
		$filepaths=appFilePath('upload/'.APP_UA.'/'.$dirbase.$filename.'.jpg');
		$filepath=rv($filepaths,'type',$resType);
		//debugx($filepath);
		if(!isFile($filepath) && $resType){
			$filepath=rv($filepaths,'type','big');
		}
		if(!isFile($filepath)) $filepath=appFilePath('vdcs.web/res/ua/avatar.gif');
		//debugx($filepath);
		//debugx(DCS::browseURL(true));
		//$this->doPutPic($filepath);
		utilIO::outputImage($filepath);
	}
	
	public static function toVarDirParser($sdir,$sn='')
	{
		if(!$sdir) $sdir='{$y}{$m}/';
		if(right($sdir,1)!=DIR_SEPARATOR) $sdir.=DIR_SEPARATOR;
		utilString::lists3(DCS::today(),$_year,$_month,$_day,'-');
		$sdir=rd($sdir,'y',$_year);
		$sdir=rd($sdir,'m',$_month);
		$sdir=rd($sdir,'d',$_day);
		$sn=strval($sn);
		for($n=0;$n<5;$n++){
			$_snn=$sn{$n};if(!isset($_snn{0})) $_snn='0';
			$sdir=rd($sdir,'sn'.($n+1),$_snn);
		}
		return $sdir;
	}
	
}
?>