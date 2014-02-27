<?
class ManageCommon
{
	const MANAGE_NAME='manage';
	
	public static function initConfigure()
	{
		global $_cfg;
		
		$_cfg['sys.path']['vdcs.manage']		= VDCS_MANAGE_PATH;
		$_cfg['sys.path']['vdcs.mchannela']		= defined('VDCS_CHANNELA_MANAGE_PATH')?VDCS_CHANNELA_MANAGE_PATH:VDCS_CHANNELA_PATH;
		$_cfg['sys.path']['vdcs.mchannelc']		= defined('VDCS_CHANNEL_MANAGE_PATH')?VDCS_CHANNEL_MANAGE_PATH:VDCS_CHANNEL_PATH;
		$_cfg['sys.path']['vdcs.mchannel']		= defined('VDCS_MANAGE_CHANNEL_PATH')?VDCS_MANAGE_CHANNEL_PATH:VDCS_MANAGE_PATH.'channel/';
		$_cfg['sys.path']['vdcs.mconfig']		= defined('VDCS_MANAGE_CONFIG_PATH')?VDCS_MANAGE_CONFIG_PATH:VDCS_MANAGE_PATH.'config/';
		$_cfg['sys.path']['vdcs.mthemes']		= defined('VDCS_MANAGE_THEMES_PATH')?VDCS_MANAGE_THEMES_PATH:VDCS_MANAGE_PATH.'themes/';
		
		$_cfg['sys.path']['manage']			= MANAGE_PATH?MANAGE_PATH:($_cfg['sys.path']['root'].self::MANAGE_NAME.'/');
		$_cfg['sys.path']['manage.common']		= $_cfg['sys.path']['manage'].'common/';
		$_cfg['sys.path']['manage.config']		= $_cfg['sys.path']['manage'].'config/';
		$_cfg['sys.path']['manage.channels']		= $_cfg['sys.path']['manage'].'channel/';
		$_cfg['sys.path']['manage.channel']		= $_cfg['sys.path']['manage.channels'].'{$channel}/';
		$_cfg['sys.path']['manage.channel.config']	= $_cfg['sys.path']['manage.channel'].'config/';
		$_cfg['sys.path']['manage.channelcs']		= CHANNEL_MANAGE_PATH;
		$_cfg['sys.path']['manage.channelc']		= $_cfg['sys.path']['manage.channelcs'].'{$channel}/'.CHANNEL_M.'/';
		$_cfg['sys.path']['manage.channelc.config']	= $_cfg['sys.path']['manage.channelc'].'config/';
		$_cfg['sys.path']['manage.data']		= $_cfg['sys.path']['manage'].'data/';
		$_cfg['sys.path']['manage.images']		= $_cfg['sys.path']['manage'].'images/';
		$_cfg['sys.path']['manage.themes']		= $_cfg['sys.path']['manage'].'themes/';
		
		$_cfg['app']['url.manage']			= defined('MANAGE_URL')?MANAGE_URL:$_cfg['app']['url.root'].MANAGE_DIR.'/';
		$_cfg['app']['url.manage.dir']			= MANAGE_DIR;
		$_cfg['app']['url.manage.channel']		= $_cfg['app']['url.manage'].'channel/';
		$_cfg['app']['url.manage.data']			= $_cfg['app']['url.manage'].'data/';
		$_cfg['app']['url.manage.images']		= $_cfg['app']['url.manage'].'images/';
		$_cfg['app']['url.manage.themes']		= $_cfg['app']['url.manage'].'themes/'.(MANAGE_THEME_APP?MANAGE_THEME_APP.'/':'');
		$_cfg['app']['url.manage.welcome']		= $_cfg['app']['url.manage'].'index.php/welcome';
		$_cfg['app']['url.manage.frame']		= $_cfg['app']['url.manage'].'index.php/frame';
		$_cfg['app']['url.manage.frame.nav']		= $_cfg['app']['url.manage'].'index.php/frame/nav';
		$_cfg['app']['url.manage.frame.menu']		= $_cfg['app']['url.manage'].'index.php/frame/menu';
		$_cfg['app']['url.manage.main']			= $_cfg['app']['url.manage'].'index.php/main';
		$_cfg['app']['url.manage.portal']		= $_cfg['app']['url.manage'].'index.php/';
		$_cfg['app']['url.manage.portals']		= $_cfg['app']['url.manage'].'index.php/{$channel}';
		$_cfg['app']['url.manage.route']		= '/{$route}';
		$_cfg['app']['url.manage.login']		= $_cfg['app']['url.manage'].'index.php/login';
		$_cfg['app']['url.manage.login.x']		= $_cfg['app']['url.manage'].'index.php/loginx';
		$_cfg['app']['url.manage.logout']		= $_cfg['app']['url.manage'].'index.php/login?action=logout';
		
		$_cfg['manage']['dir']				= 'manage';
		$_cfg['manage']['common.dir']			= 'common/';
		$_cfg['manage']['channel.dir']			= 'channel/';
		$_cfg['manage']['channel.default']		= 'default';
		$_cfg['manage']['channel.system']		= 'system';
		$_cfg['manage']['channel.common']		= 'manager';
		//$_cfg['manage']['channel.entry']		= 'entry.php';
		//$_cfg['manage']['channel.entrys']		= 'entry.{$portal}.php';
		
		//$_cfg['page']['manage.portal.mode']		= 'redirect';
	}
	
	public static function getPaths($type='config')
	{
		if($type=='config'){
			$re[$type.'.manage.c']=appDirPath('manage.channelc.config/');
			$re[$type.'.manage']=appDirPath('manage.channel.config/');
			$re[$type.'.vdcs.a']=appDirPath('vdcs.mchannela/{$channel}/m/config/');
			$re[$type.'.vdcs.c']=appDirPath('vdcs.mchannelc/{$channel}/m/config/');
			$re[$type.'.vdcs']=appDirPath('vdcs.mchannel/{$channel}/config/');
		}
		return $re;
	}
	
	
	public static function toURL($re,$apd='')
	{
		if($apd){
			if(ins($re,'?')<1) $re.='?';
			else if(right($re,1)!='&') $re.='&';
			$re.=$apd;
		}
		return $re;
	}
	
	public static function cfg($k){global $_cfg; return $_cfg['manage'][$k];}
	
	public static function NAME() { global $_cfg; return $_cfg['manage']['dir']; }
	public static function getURL($s='')
	{
		global $_cfg;
		if(ins($s,'/')>0){
			$sa=substr($s,ins($s,'/'));
			$s=substr($s,0,ins($s,'/')-1);
		}
		return ($s?$_cfg['app']['url.manage.'.$s]:$_cfg['app']['url.manage']).$sa;
	}
	public static function getPath($s='')
	{
		global $_cfg;
		if(ins($s,'/')>0){
			$sa=substr($s,instr($s,'/'));
			$s=substr($s,0,instr($s,'/')-1);
		}
		return ($s?$_cfg['sys.path']['manage.'.$s]:$_cfg['sys.path']['manage']).$sa;
	}
	
	public static function getEntry($channel='',$entry=null,$mpath=false,$cpath=false)
	{
		global $_cfg;
		//if($entry===null){$entry=$channel;$channel='';}
		$basePath=$mpath?$_cfg['sys.path']['vdcs.manage']:$_cfg['sys.path']['manage'];
		switch($channel){
			case '_commons':
				$basePath=VDCS_MANAGE_PATH;
				$channels='common/';
				break;
			case '_channels':
				$basePath=VDCS_MANAGE_PATH;
				$channels='channel/';
				break;
			case '_channel':
				$channels=$_cfg['manage']['common.dir'].'channel/';
				break;
			case '':
				$channels=$_cfg['manage']['channel.dir'].$_cfg['manage']['channel.default'].'/';
				break;
			default:
				$channels=$_cfg['manage']['channel.dir'].$channel.'/';
				break;
		}
		$entry=$entry?('.'.$entry):'';
		return $basePath.$channels.'entry'.$entry.'.php';
	}
	
	public static function loadEntry($channel='',$entry=null)
	{
		$path=self::getEntry($channel,$entry);
		if(isFile($path)){
			//define('APP_OBJECTPATH',$path);
			include_once($path);
		}
		else{
			debugx('Manage Entry no found: '.$channel.' '.$entry);
		}
	}
	
	
	public static function entryFile($channel='',$portal=null,$module=null,$modulei=null,$extend=null)
	{
		$file='entry';
		if($portal) $file.='.'.$portal;
		if($module) $file.='.'.$module;
		if($modulei) $file.='.'.$modulei;
		if($extend) $file.='.'.$extend;
		$file.='.php';
		//debugx($file);
		return $file;
	}
	public static function entryPath($channel='',$portal=null,$module=null,$modulei=null,$extend=null)
	{
		$file=self::entryFile($channel,$portal,$module,$modulei,$extend);
		$path=_autoload_path($file);
		return $path;
	}
	public static function entryURL($channel='',$portal=null,$module=null,$modulei=null,$extend=null,$params=null)
	{
		global $_cfg;
		$re=$_cfg['app']['url.manage.portals'];
		$re=rd($re,'channel',$channel);
		if($portal) $re.=rd($_cfg['app']['url.manage.route'],'route',$portal);
		if($module) $re.=rd($_cfg['app']['url.manage.route'],'route',$module);
		if($extend) $re.='.'.$extend;
		if($params) $re=self::toURL($re,$params);
		return $re;
	}
	
}
?>