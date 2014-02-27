<?
defined('DEBUG_THEMES') || define('DEBUG_THEMES',1);
defined('THEME_DEFAULT') || define('THEME_DEFAULT',MANAGE_THEME);
class ManageTheme extends BaseTheme
{
	
	//public $label;
	public function __construct()
	{
		parent::__construct();
		//$this->label=new CommonLabel();
		$this->_var['_mode']		= 'channel';		// common
		$this->_var['_module']		= 1;
		$this->_var['_cache']		= 1;
	}
	
	
	protected function doChannelExtend()
	{
		switch($this->_var['_mode']){
			case 'channel':
				$this->_var['channelbase.path']	= $this->_var['themebase.path'].'channel/';
				$this->_var['channelapp.path']	= $this->_var['themeapp.path'].'channel/';
				$this->_var['channelva.path']	= rd(appDirPath('vdcs.mchannela/{$channel}/m/config/'),'channel',$this->_var['channel']);
				$this->_var['channelvc.path']	= rd(appDirPath('vdcs.mchannelc/{$channel}/m/config/'),'channel',$this->_var['channel']);
				$this->_var['channelv.path']	= rd(appDirPath('vdcs.mchannel/{$channel}/config/'),'channel',$this->_var['channel']);
				$this->_var['channeldef.path']	= $this->_var['themedef.path'].'channel/';
				$this->_var['channelc.path']	= rd(ManageCommon::getPath('channelc.config'),'channel',$this->_var['channel']);
				$this->_var['channel.path']	= rd(ManageCommon::getPath('channel.config'),'channel',$this->_var['channel']);
				break;
			default:
				$this->_var['channelbase.path']	= $this->_var['themebase.path'].$this->_var['channel.dir'];
				$this->_var['channelapp.path']	= $this->_var['themeapp.path'].$this->_var['channel.dir'];
				//$channeldir=MANAGE_THEME_APP?MANAGE_THEME_APP.'/':'';
				$this->_var['channeldef.path']	= $this->_var['themedef.path'].$this->_var['channel.dir'];
				//$this->_var['channelv.path']	= $this->_var['theme.path'].$this->_var['channel.dir'];
				$this->_var['channel.path']	= $this->_var['theme.path'].$this->_var['channel.dir'];
				break;
		}
		//debuga($this->_var);
		//debuga($this->_web);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		parent::doInit();
		global $_cfg;
		$this->_var['themebase.path']	= appDirPath('vdcs.mthemes/'.$this->_var['themedef.dir']);
		$this->_var['themeapp.path']	= appDirPath('vdcs.mthemes/'.$this->_var['themeapp.dir']);
		
		$this->_var['themes.url']	= appURL('manage.themes');
		$this->_var['themes.path']	= appDirPath('manage.themes/');
		$this->_var['cache.path']	= appDirPath('data.cache/manage/');
		//$this->_var['cache.path']	= appDirPath('manage.data/cache/');
		
		$this->_var['themedef.path']	= appDirPath('manage.themes/');
		
		//$this->setTheme($_cfg['app']['manage.theme']?$_cfg['app']['manage.theme']:self::$ThemeDefault);
		$this->setTheme(MANAGE_THEME_APP);
	}
	
	public function doParse()
	{
		if($this->isError()) return;
		global $_cfg,$dcs,$cfg,$ctl,$theme,$ma,$mpo,$cpo;
		global $mpMod;
		//$this->pageHeader();
		obStarts();
		$cpo=&$mpo;
		include_once $this->_var['CacheFilePath'];
		//$func='doPageTheme';
		//if(function_exists($func)){
			$this->output=obContent();
			obClean();
			$this->pageHeader();
			//cfunc($func);
			$mpo->doThemePrePortal();
			$mpo->doThemePre();
			$mpo->doTheme();
			$mpo->doThemePos();
			$mpo->doThemePosPortal();
			echo $this->output;
		//}
		obFlush();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toVarValue($v)
	{
		$v=r($v,'this.','mpo.');
		return $v;
	}
	public function doCacheFilterVar($s,$v) { parent::doCacheFilterVar($s,$this->toVarValue($v)); }
	public function doCacheFilterTree($k,$v,$func='') { parent::doCacheFilterTree($k,$this->toVarValue($v),$func); }
	public function doCacheFilterLoop($k,$v,$b='') { parent::doCacheFilterLoop($k,$this->toVarValue($v),$b); }
	
	
	/*
	########################################
	########################################
	*/
	protected function doCacheContentExtend()
	{
		$oc=new ManageThemeCacher();
		$oc->setVars($this->_var);
		$oc->setVar('ThemeDefault',self::$ThemeDefault);
		$oc->setDefs($this->_def);$oc->setPres($this->_pre);$oc->setWebs($this->_web);
		$this->output=$oc->toDTMLCachePre($this->output);
		//####################		当前页面处理
		global $mpo;
		$mpo->doThemeCachePrePortal();
		$mpo->doThemeCachePre();
		$mpo->doThemeCache();
		$mpo->doThemeCachePos();
		$mpo->doThemeCachePosPortal();
		//####################
		$this->output=$oc->toDTMLCache($this->output);
		unsetr($oc);
	}
	
}
?>