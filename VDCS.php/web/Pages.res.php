<?
//defined('APP_LADDER')		|| define('APP_LADDER',			'../');
defined('APP_INC')		|| define('APP_INC',			'');		//inc/
defined('APP_PORTALNAME')	|| define('APP_PORTALNAME',		'PagePortal');
defined('APP_PORTAL_DEFAULT')	|| define('APP_PORTAL_DEFAULT',		'index');
defined('APP_UCHANNEL')		|| define('APP_UCHANNEL',		'account');
defined('APP_UA')		|| define('APP_UA',			'user');
/*
$n=10000;
timerBegin();
for($i=0;$i<$n;$i++){
DCS::isLocal();
}
debugx(timerExec());
*/

function initWeb()
{
	$GLOBALS['cfg']=new CommonConfig();
	$GLOBALS['theme']=new WebTheme();
	$GLOBALS['ua']=&Ua::instance(APP_UA);
}
function initServeXCML(){define('DEBUG_TYPE',1);$GLOBALS['serve']['xcml']=new WebServeXCML();return $GLOBALS['serve']['xcml'];}
function initServeJSON(){define('DEBUG_TYPE',2);$GLOBALS['serve']['json']=new WebServeJSON();return $GLOBALS['serve']['json'];}


//##############################
//##############################
function resObject()
{
	global $routes;
	$pathinfo=isset($_GET['router'])?$_GET['router']:ltrim($_SERVER['PATH_INFO'],'/');
	//debugx($_SERVER['PATH_INFO']);
	if(!$pathinfo) $pathinfo=query('cp').'/'.query('p').'/'.query('m').'/'.query('mi').'.'.query('x');
	//debugx($pathinfo);
	$routes=explode('.',$pathinfo);
	define('PAGE_X',$routes[1]);
	$routes=explode('/',$routes[0]);
	!$routes[0] && $routes[0]='common';
	!$routes[1] && $routes[1]=APP_PORTAL_DEFAULT;
	define('PAGE_IS',true);define('PAGE_CHN',$routes[0]);define('PAGE_P',$routes[1]);define('PAGE_M',$routes[2]);define('PAGE_MI',$routes[3]);
	//debuga($routes);
	//debugx('PAGE_CHN='.PAGE_CHN.', PAGE_P='.PAGE_P.', PAGE_M='.PAGE_M.', PAGE_MI='.PAGE_MI.', PAGE_X='.PAGE_X);
	//##########
	//WebCommon::initChannel(PAGE_CHN);
	$portal=$GLOBALS['_cfg']['channel'][PAGE_CHN.'.portal'];
	if(!$portal) $portal=PAGE_CHN;
	define('APP_CHANNEL',PAGE_CHN);define('APP_PORTAL',$portal);		//define('APP_THEME',$theme?$theme:APP_CHANNEL);
	//##########
	$extendv='';
	if(PAGE_X){
		$extendv=PAGE_X;
		if(inp('x,j,xml,json',PAGE_X)>0) $extendv='x';
	}
	//##########
	$objectpx=ucfirst(PAGE_P).ucfirst(PAGE_M).ucfirst(PAGE_MI).ucfirst($extendv);
	$objectName='Channel'.ucfirst(PAGE_CHN).$objectpx;
	//debugx(PAGE_P.','.PAGE_M.',','.PAGE_MI.','.$objectName);
	if(APP_PORTAL && !_autoload_::getPath($objectName.EXT_EXECUTE)){	//!_autoload_::path($objectName.EXT_EXECUTE)
		$objectNameV=_autoload_::getVar($objectName);
		//debugx($objectNameV);
		$objectNameV='';
		if(!$objectNameV){
			$objectNameV=$objectName;
			//debugx($objectNameV);
			if(!_autoload_::path($objectNameV.EXT_EXECUTE)){
				$objectNameOg='Channel'.ucfirst(APP_PORTAL).$objectpx;
				//debugx($objectNameOg);
				if(_autoload_::path($objectNameOg.EXT_EXECUTE)){
					$objectNameV=$objectNameOg;
				}
				_autoload_::setVar($objectName,$objectNameV);
			}
		}
		$objectName=$objectNameV;
		//debugx($objectName.'='.$objectNameOg);
		// && !_autoload_::path($objectName.EXT_EXECUTE)
		if(PAGE_X && !_autoload_::path($objectName.EXT_EXECUTE)){
			WebCommon::supportExtend($objectName);
			dcsEnd();
			return false;
		}
	}
	define('APP_OBJECTNAME',$objectName);
	//debugx('objectName='.$objectName);
	//debugx($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);		//SCRIPT_NAME
	eval('class '.APP_PORTALNAME.' extends '.$objectName.'{}');
	return true;
}
//##############################


function webAgent()
{
	//$channel=queryx('cp');
	//if(!$channel) $channel='common';
	//WebCommon::initChannel($channel);
	if(!resObject()) return;
	initWeb();
	doWebPageBase();
}

function doWebPageBase($classname=APP_PORTALNAME)		//,$isDebug=false
{
	global $uu,$ua,$theme,$cpo;		//$_cfg,$cp,
	$cpo=new $classname;
	$cpo->initer();						//初始化
	$cpo->themeInit();					//模板初始化
	$cpo->initBasic();					//频道初始化
	$cpo->inited();
	$cpo->doAuth();						//身份检测
	$cpo->doInitPre();$cpo->doInit();$cpo->doInitPos();	//页面初始化
	$cpo->doIniter();
	$cpo->doLoadPre();$cpo->doLoad();$cpo->doLoadPos();	//页面载入
	$cpo->doParsed();					//页面解析
	foreach($uu as $urc=>$uao){		//debugxx('uu.'.$urc);
		$uu[$urc]->save();		//$uu[$urc]=null;
	}
	$cpo->themeParse();					//模板解析
	//$cpo->doDestroy();					//页面对像消毁
	//$cpo->doClear();					//页面清理
	if(DEBUG_OUT && (DCS::isLocal() || queryx('debug')=='info')){	//DEBUG_WEB_SCRIPT>0 &&
		ResMessage::debugi();
	}
	//debugx('full='.WebCommon::getScript().',channel='.WebCommon::getScript('channel').',file='.WebCommon::getScript('file').',filename='.WebCommon::getScript('filename'));
	_autoload_::save();
	$theme=null;$cpo=null;
	dcsEnd();
}

class WebPortalBase
{
	protected $UARC=APP_UA,$UAO='ua',$UAC='ua:';
	protected $_var=array();
	public $treeVar=null;
	
	public function __construct()
	{
		$this->treeVar=new utilTree();
	}
	public function __destruct()
	{
		//debugx(__METHOD__);
		unset($this->_var);
	}
	
	
	public function v($k,$v=null)
	{
		if(!is_null($v)){
			$this->_var[$k]=$v;
			if($this->isInitControl) $this->treeVar->addItem($k,$v);
		}
		return $this->_var[$k];
	}
	public function vi($k,$v=null){return toInt($this->v($k,$v));}
	
	
	/*
	########################################
	########################################
	*/
	protected function initCore($channel)
	{
		$this->_chn_=$channel;
		$this->cfg->setChannel($this->_chn_);
		$this->cfg->setWeb('channel',$this->_chn_);
		$this->cfg->doChannelInit();
		
		if(!$this->UCHANNEL){
			$this->UCHANNEL=$this->cfg->vp('ua:channel');
			if(!$this->UCHANNEL)$this->UCHANNEL=APP_UCHANNEL;
		}
		$this->UARC=$this->cfg->vp('ua');
		if(!$this->UARC) $this->UARC=APP_UA;
		//debugx($this->UCHANNEL.'-'.$this->UARC);
	}
	
	public function themeInit()				//模板初始化
	{
		$this->theme->doInit();
		$this->theme->setChannel($this->_chn_);
	}
	protected function themeSet()
	{
		$this->theme->setSubdir($this->_p_);
		$this->theme->setChild($this->_p_);
		$this->theme->setPage($this->_p_);
		$this->theme->setModule($this->_m_);
		$this->theme->setModulei($this->_mi_);
	}
	public function themeParse()				//模板解析
	{
		$this->theme->setDP('channel',$this->_chn_);
		//debugx($this->_p_.','.$this->getPortalx());
		$this->theme->setDP('portalx',$this->getPortalx());
		$this->theme->setDP('portal',$this->_p_);
		$this->theme->setDP('module',$this->_m_);
		$this->theme->setDP('modulei',$this->_mi_);
		$this->theme->setDP('extend',$this->_x_);
		$this->theme->setDP('action',$this->action);
		$this->theme->setDP('mode',$this->mode);
		//##########
		$this->theme->setDP('uchannel',$this->UCHANNEL);
		$this->theme->setDP('uarc',$this->UARC);
		$this->theme->setDP('uao',$this->UAO);
		$this->theme->setDP('ido',$this->UAO);
		//##########
		$this->theme->setWebs($this->cfg->getWebs());
		$this->doThemer();
		$this->theme->doLoad();				//模板载入
		$this->theme->doParse();			//模板解析
	}
	
	protected function getPortalx()
	{
		if(!$this->portalx) $this->portalx=trim($this->_p_.'.'.$this->_m_.'.'.$this->_mi_,'.');
		return $this->portalx;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initer()				//初始化
	{
		$this->_chn_=PAGE_CHN;$this->_p_=PAGE_P;$this->_m_=PAGE_M;$this->_mi_=PAGE_MI;$this->_x_=PAGE_X;
		//$this->channel=&$this->_chn_;$this->portal=&$this->_p_;$this->module=&$this->_m_;$this->modulei=&$this->_mi_;$this->extend=&$this->_x_;

		$this->cfg=&$GLOBALS['cfg'];
		$this->ctl=&$GLOBALS['ctl'];
		$this->theme=&$GLOBALS['theme'];
		$this->initUA();
		$this->action=queryx('action');

		$this->initCore(APP_CHANNEL);			//频道初始化
		if($this->iscontrol) $this->controlInit();	//控制器初始化
		if($this->iserve) $this->serveInit();		//服务预初始化
	}
	
	public function initUA()				//UA初始化
	{
		$this->ua=&Ua::instance($this->UARC);
	}
	
	public function initBasic(){}				//页面预初始化
	
	public function inited()				//初始化
	{
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doAuth()				//身份检测
	{
		$this->doAuther();
	}
	public function doAuthe()
	{
		//$this->ua=&Ua::instance($this->UARC);
	}
	public function doAuther()
	{
		$this->doAuthe();
		$this->ua->doInit();
	}
	public function doAuthed($mode=2)
	{
		$this->doAuthe();
		$this->ua->setAuth(1);
		$this->ua->setAuthMode($mode);
		$this->ua->doInit();
		//$this->ua->doAuth();
	}
	
	public function doInitPre(){}				//页面预初始化
	public function doInit(){}				//页面初始化
	public function doInitPos(){}				//页面后置初始化
	public function doIniter()
	{
		$this->setPortals__();
	}

	public function doLoadPre(){}				//页面预载入
	public function doLoad(){}				//页面载入
	public function doLoadPos(){}				//页面后置载入
	
	public function doParsed()				//页面处理
	{
		if(!$this->parserCan()) return;
		$this->doParsePre();
		$this->doParse();
		$this->doParsePos();
	}
	public function parserCan(){return true;}
	public function doParsePre(){}				//页面预处理
	public function doParse()				//页面处理
	{
		$this->parserAction('parse');
	}
	public function doParsePos(){}				//页面后置处理
	
	public function doThemePre(){}				//模板前置输出处理
	public function doTheme(){}				//模板输出处理
	public function doThemePos(){}				//模板后置输出处理
	public function doThemeCacheBasic(){}			//模板基础缓存处理
	public function doThemeCachePre(){}			//模板前置缓存处理
	public function doThemeCache(){}			//模板缓存处理
	public function doThemeCachePos(){}			//模板后置缓存处理
	public function doThemer(){}				//模板输出处理
	
	public function doDestroy(){}				//页面对像消毁
	public function doClear(){}				//页面清理
	
	
	/*
	########################################
	########################################
	*/
	protected function addError($msg,$field=null,$code=null){$GLOBALS['ctl']->e->addItem($msg,$field,$code);}
	protected function isErrorCheck(){return $GLOBALS['ctl']->e->isCheck();}
	protected function isRaiseError($raise=true){return $this->isErrorCheck();}
	protected function doRaiseError(){return $this->isErrorCheck();}
	
	/*
	########################################
	########################################
	*/
	protected function addVar($k,$v){$this->treeVar->addItem($k,$v);}
	protected function addDat($k,$v){$this->treeDat->addItem($k,$v);}
	protected function addData($k,$v){$this->treeData->addItem($k,$v);}
	protected function addDTML($k,$v){$this->treeDTML->addItem($k,$v);}
	
	protected function getVar($k){return $this->treeVar->getItem($k);}
	protected function getDat($k){return $this->treeDat->getItem($k);}
	protected function getData($k){return $this->treeData->getItem($k);}
	protected function getDTML($k){return $this->treeDTML->getItem($k);}
	
	/*
	########################################
	########################################
	*/
	public function setSucceed(){$this->setStatus('succeed');}
	public function getStatus($k='status'){return $this->getVar($k);}
	public function setStatus($v,$msg=''){$this->addVar('status',$v);if($msg)$this->setMessage($msg);}
	public function getMessage($k='message'){return $this->getVar($k);}
	public function setMessage($v,$k='message'){$this->addVar($k,$v);}
	public function setMessages($tit,$msg,$url='')
	{
		if($tit) $this->addVar('title',$tit);
		$this->setMessage($msg);
		if($url) $this->addVar('backurl',$url);
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function ready($ispost=false)
	{
		$this->setStatus('ready');
		if($ispost && !isPost()) return false;
		$this->setStatus('parser');
		return true;
	}
	
	public function parserAction($prefix='parse')
	{
		$action=$this->action;
		$this->setStatus('init');
		$funcname=$prefix.ucfirst($action);
		//debugx($funcname);
		//method_exists,is_callable
		if(!method_exists($this,$funcname)){
			$this->setStatus('noaction');
			return;
		}
		$this->$funcname();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function setPortals__()
	{
		$this->themeSet();
		//debugx('_chn_='.$this->_chn_.', _p_='.$this->_p_.', _m_='.$this->_m_.', _mi_='.$this->_mi_.', _x_='.$this->_x_.', action='.$this->action);
		$this->setTitles__();
	}
	protected function setTitles__()
	{
		$title=$this->cfg->v('title');if($title=='null') $title='';
		$this->setTitle('chn',$title);
		$titlek=$this->_p_;
		$titlep=$this->getTitle($titlek);$title=$titlep;$this->setTitle('p',$titlep);
		if($this->_m_){
			$titlek.='.'.$this->_m_;
			$titlem=$this->getTitle($titlek);$title=$titlem;$this->setTitle('m',$titlem);$this->setTitle('portal',$titlep);
			if($this->_mi_){
				$titlek.='.'.$this->_mi_;
				$titlemi=$this->getTitle($titlek);$title=$titlemi;$this->setTitle('mi',$titlemi);$this->setTitle('module',$titlem);
			}
		}
		$this->setTitle($title);
		if(!$this->action) $this->action=$this->_var['_action.def'];
		if($this->action){
			$title=$this->getTitle($titlek.'.'.$this->action,false);
			if(!$title) $title='['.$this->action.']';
			$this->setTitle('action',$title);
		}
		//debugx('Titles: '.$this->cfg->getTitles('test'));
	}

	protected function setActions($enum=null,$def=null){if(!is_null($enum)) $this->_var['_action.enum']=$enum;if(!is_null($def)) $this->_var['_action.def']=$def;}
	protected function setTitle($k,$v=null){$this->cfg->setTitle($k,$v);}
	protected function getTitle($key,$value=true)
	{
		$title=$this->cfg->v('title.'.$key);
		if(!$title){
			if($this->_p_=='index') $title=$this->cfg->v('title');
			else if($value && !$title) $title='['.$key.']';
		}
		if($title=='null') $title='';
		return $title;
	}

	
	/*
	########################################
	########################################
	*/
	protected function initControl(){self::initControls($this);}
	public static function initControls(&$that)
	{
		static $_is;if($_is)return;$_is=true;
		global $ctl;
		$ctl=new PagesControl();
		$ctl->loadParam();
		
		$that->e=&$ctl->e;
		$that->p=&$ctl->p;
		$that->pages=&$ctl->pages;
		$that->ui=&$ctl->ui;
		
		$that->treeVar=&$ctl->treeVar;
		$that->treeDat=&$ctl->treeDat;
		$that->treeData=&$ctl->treeData;
		$that->treeDTML=&$ctl->treeDTML;
		
		$ctl->_chn_=&$that->_chn_;$ctl->_p_=&$that->_p_;$ctl->_m_=&$that->_m_;$ctl->_mi_=&$that->_mi_;$ctl->_x_=&$that->_x_;
		$ctl->action=&$that->action;
		$that->sort=&$ctl->sort;$that->type=&$ctl->type;$that->mode=&$ctl->mode;
		//debugx('_chn_='.$that->_chn_.', _p_='.$that->_p_.', _m_='.$that->_m_.', _mi_='.$that->_mi_.', _x_='.$that->_x_.', action='.$that->action);
	}
	

	/*
	########################################
	########################################
	*/
	protected function logAction($value=null)
	{
		if($this->_m_) $moduleX='.'.$this->_m_;
		if(DEBUG_LOG){
			ResTest::logAction('action!'.$this->_p_.$moduleX,$value);
		}
	}
	
}


trait WebRefQuery
{
	
	public function isQuery(){return $this->_var['query.is'];}
	public function getQuery($sort='query'){return $this->_var[$sort];}
	public function setQuery($sql,$url=null)
	{
		$this->_var['query.is']=false;
		$this->_var['query']=$sql;
		$this->_var['url']=$url;
	}
	
	public function queryAppend($sql,$url=null,$type=null)
	{
		if($sql){
			$this->_var['query']=DB::sqla($this->_var['query'],$sql);
			$this->_var['query.is']=true;
		}
		if(!isn($url)) $this->_var['url']=urlLink($this->_var['url'],$url);
	}
	
}
