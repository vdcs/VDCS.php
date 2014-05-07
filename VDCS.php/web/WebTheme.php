<?
class WebTheme extends BaseTheme
{
	public $label;
	public function __construct()
	{
		parent::__construct();
		$this->label=new CommonLabel();
	}
	public function __destruct()
	{
		parent::__destruct();
		unset($this->label);
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function getThemesURL() { return appURL('themes'); }
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		if($this->isError()) return;
		global $_cfg,$dcs,$cfg,$ctl,$theme,$ua,$cpo;		//,$cp,$cx,$cpa,$uai,$uac,$uad
		//$this->pageHeader();
		obStarts();
		include_once $this->_var['CacheFilePath'];
		//$func='doPageTheme';
		//if(function_exists($func)){
			$this->output=obContent();
			obClean();
			$this->pageHeader();
			//cfunc($func);
			$cpo->doThemePre();
			$cpo->doTheme();
			$cpo->doThemePos();
			//call_user_func(array($cpo,'doTheme'),'');
			echo $this->output;
		//}
		//obFlush();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doCacheContentExtend()
	{
		$oc=new WebThemeCacher();
		$oc->setVars($this->_var);
		$oc->setVar('ThemeDefault',self::$ThemeDefault);
		$oc->setDefs($this->_def);$oc->setPres($this->_pre);$oc->setWebs($this->_web);
		$this->output=$oc->toDTMLCachePre($this->output);
		//####################		当前页面处理
		global $cpo;
		$cpo->doThemeCacheBasic();
		$cpo->doThemeCachePre();
		$cpo->doThemeCache();
		$cpo->doThemeCachePos();
		//####################
		$this->output=$oc->toDTMLCache($this->output);
		unset($oc);
	}
	
}
