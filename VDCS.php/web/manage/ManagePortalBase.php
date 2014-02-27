<?
class ManagePortalBase extends WebPortalBase
{
	use ManageRefPortal;
	use ManageRefConfig,ManageRefChannel,ManageRefPages,ManageRefSearch,ManageRefMessage,ManageRefEvent,ManageRefTheme,ManageRefBox,ManageRefAction;
	use WebPortalRefVerify;
	
	public $chn=null,$s=null;
	public $treeRS,$treeList,$treeView,$tableList;
	//public $channel='',$portal='',$module='',$action='',$page='';
	protected $isInit=true,$isAuth=true,$isPage=true,$isDebug=true;
	protected $_trees=array();
	
	public function __construct()
	{
		$this->_var['list.num']=10;
		$this->_var['SearchMode']=1;
		$this->_var['PageMode']='list';
		$this->_var['PageAction']='list';
		$this->_var['PageID']=-1;
		$this->_var['paging.listnum']=0;
		$this->_var['paging.show']=true;

		$this->_var['action.relate']='relate';

		$this->_var['AppendURL']='';
		$this->_var['AppendQuery']='';
		$this->_var['FieldMode']='';
		$this->_var['FormFile']='';
	}
	
	public function __destruct()
	{
		unset($this->_trees,$this->_var);
		unset($this->treeRS,$this->treeList,$this->treeView,$this->tableList);
		unset($this->chn,$this->s);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setVar($k,$v){$this->_var[$k]=$v;}
	
	public function setInit($b){$this->isInit=$b;}		//public function isInit(){return $this->isInit;}
	public function setAuth($b){$this->isAuth=$b;}		//public function isAuth(){return $this->isAuth;}
	public function setPage($b){$this->isPage=$b;}		public function isPage(){return $this->isPage;}
	public function setDebug($b){$this->isDebug=$b;}	public function isDebug(){return $this->isDebug;}
	
	
	public function setFieldMode($s){$this->FieldMode=$s;}
	public function isFieldMode($k){return ins($this->FieldMode,$k)>0?true:false;}
	
	public function setFormFile($s){$this->FormFile=$s;}
	
	protected function getPortalx()
	{
		if(!$this->pagex) $this->pagex=$this->_p_?$this->_p_:$this->_chn_;
		if(!$this->portalx) $this->portalx=trim($this->pagex.'.'.$this->_m_.'.'.$this->_mi_,'.');
		return $this->portalx;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function inite()					//初始化0
	{
		$this->_chn_=PAGE_CHN;$this->_p_=PAGE_P;$this->_m_=PAGE_M;$this->_mi_=PAGE_MI;$this->_x_=PAGE_X;
		//debugx('_chn_='.$this->_chn_.', _p_='.$this->_p_.', _m_='.$this->_m_.', _mi_='.$this->_mi_.', _x_='.$this->_x_);
		$this->action=query('action');
		
		$this->cfg=&$GLOBALS['cfg'];
		$this->ctl=&$GLOBALS['ctl'];
		$this->theme=&$GLOBALS['theme'];
		$this->ua=&$GLOBALS['ua'];
		$this->ma=&$GLOBALS['ma'];
		
		$this->initControl();				//控制器预初始化
		$this->initControlParams();
		if($this->iserve) $this->serveInit();		//服务预初始化

		$this->ruler=&$GLOBALS['mr'];
		$this->ruler->ma=&$GLOBALS['ma'];
		$this->ruler->channel=&$this->_chn_;
		$this->ruler->portal=&$this->_p_;
		$this->ruler->module=&$this->_m_;
		$this->ruler->modulei=&$this->_mi_;
		$this->ruler->extend=&$this->_x_;
		$this->ruler->action=&$this->action;
	}
	public function initer(){}				//初始化0
	
	protected function initControlParams()
	{
		$this->ctl->channel=query('channel');
		$this->ctl->module=query('module');
		$this->ctl->subchannel=queryx('subchannel');
		$this->ctl->taxis=queryx('taxis');
		$this->ctl->id=queryi('id');
		$this->ctl->dataid=queryi('dataid');
		$this->ctl->classid=query('classid');
		if(strlen($this->ctl->classid)>0) $this->ctl->classid=toInt($this->ctl->classid);

		$this->channel=&$this->ctl->channel;
		$this->module=&$this->ctl->module;
		$this->subchannel=&$this->ctl->subchannel;
		$this->taxis=&$this->ctl->taxis;
		$this->id=&$this->ctl->id;
		$this->dataid=&$this->ctl->dataid;
		$this->classid=&$this->ctl->classid;
	}
	
	public function inited()				//初始化1
	{
		$this->loadChannel();
		$this->loadSearch();
		$this->loadPages();
		$this->theme->setPage($this->_chn_);
		if($this->_p_) $this->theme->setPage($this->_p_);		//.($this->_m_?'.'.$this->_m_:'')
		if($this->_m_) $this->theme->setModule($this->_m_);
		if($this->_mi_) $this->theme->setModulei($this->_mi_);
	}
	public function init(){}				//初始化1
	
	
	/*
	########################################
	########################################
	*/
	public function doAuth()				//身份检测
	{
		$this->doAuthed();
	}
	public function doAuthe()
	{
		//$this->ma
	}
	public function doAuther()
	{
		$this->doAuthe();
		$this->ma->doInit();
	}
	public function doAuthed($mode=2)
	{
		$this->doAuthe();
		if(!$this->isAuth) return;$this->isAuth=true;
		$this->ma->setAuth(1);
		$this->ma->doInit();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doIniter()
	{

	}

	public function doParse()				//页面处理
	{
		$this->parserAction();
	}
	
	public function parse()
	{
		$this->parserAction('list');
	}
	public function parserAction($action=null,$prefix='parse')
	{
		if(!$action) $action=$this->action;
		$this->setStatus('init');
		$funcname=$prefix.ucfirst($action);
		//method_exists,is_callable
		if(!method_exists($this,$funcname)){
			$this->setStatus('noaction');
			return;
		}
		$this->theme->setAction($action);
		$this->$funcname();
	}
	
}
?>