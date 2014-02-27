<?
class ChannelApiBasic extends WebPortalBase
{
	protected $API_ROUTER_SEP		= '.';
	protected $API_CLASS_BASE		= 'apiBase';
	protected $API_CLASS_PREFIX		= 'api';
	protected $API_CLASS_ENTRY		= 'apiEntry';
	protected $API_DIR			= 'entry';


	public function initBasic()
	{
		$this->apipaths=appDirPath('vdcs.web/channel/'.APP_CHANNEL.'/');
		//debugxx('apipaths='.$this->apipaths);
		$this->apipath=defined('PATH_API')?PATH_API:appDirPath('common/channel/'.APP_CHANNEL.'/');
		//debugxx('apipath='.$this->apipath);
		$this->basepath=$this->apipath;
		
		$this->api_router=query('api');
		//debugx($this->api_router);
		list($this->api,$this->apip,$this->apim,$this->apimi)=explode($this->API_ROUTER_SEP,$this->api_router,4);
		if(!$this->api){
			global $routes;
			$this->api=PAGE_P;
			$this->apip=$routes[2];
			$this->apim=$routes[3];
		}
		$this->action=queryx('action');
		debugxx('api='.$this->api.', apip='.$this->apip.', apim='.$this->apim.', apimi='.$this->apimi.', action='.$this->action);
	}


	/*
	########################################
	########################################
	*/
	public function apiDir($value){$this->API_DIR=$value;}
	
	public function apiBuilder(&$classname,&$classfile,&$classpath,&$baseclasspath=null)
	{
		//$classname='api'.ucfirst($this->apip).ucfirst($this->apim);
		//$classfile=ucfirst($this->apip).ucfirst($this->apim).EXT_SCRIPT;
		$classname=$this->API_CLASS_PREFIX.'_'.$this->api.'_'.$this->apip;
		$classfilename=$this->apip;
		if($this->apim){
			$classname.='_'.$this->apim;
			$classfilename.='.'.$this->apim;
			if($this->apimi){
				$classname.='_'.$this->apimi;
				$classfilename.='.'.$this->apimi;
			}
		}
		$classfilename.=EXT_SCRIPT;
		$apidir=$this->api.'/';
		$classdir=$this->API_DIR.'/';
		//debugxx('classdir='.$classdir.', apidir='.$apidir);
		$classfile=$apidir.$classfilename;
		//path
		$patha=array();
		$patha['path']=$this->apipath;
		$patha['path_dir']=$this->apipath.$classdir;
		$patha['paths']=$this->apipaths;
		$patha['paths_dir']=$this->apipaths.$classdir;
		DCS::pathal(array_values($patha));
		//classpath
		if(!isFile($classpath=$patha['path_dir'].$classfile)
			&& !isFile($classpath=$patha['paths_dir'].$classfile)) $classpath='';
		//debugxx('classpath='.$classpath);
		//baseclasspath
		$baseclassfile=$this->API_CLASS_BASE.'.php';
		if(!isFile($baseclasspath=$patha['path_dir'].$apidir.$baseclassfile)
			&& !isFile($baseclasspath=$patha['paths_dir'].$apidir.$baseclassfile)
			&& !isFile($baseclasspath=$patha['path_dir'].$baseclassfile)
			&& !isFile($baseclasspath=$patha['paths_dir'].$baseclassfile)) $baseclasspath='';
		//debugxx('baseclasspaths='.$baseclasspath);
	}
	
	public function apiParser()
	{
		$this->apiBuilder($classname,$classfile,$classpath,$baseclasspath);
		debugxx('class='.$classname.', file='.$classfile.', path='.$classpath.', basepath='.$baseclasspath);
		$this->addVar('api',$this->api_router);
		if(!$classpath){
			$this->setStatus('noapi');
			return;
		}
		if($baseclasspath) include($baseclasspath);
		include($classpath);
		
		if(!class_exists($classnamei=$this->API_CLASS_ENTRY,false) && !class_exists($classnamei=$classname,false)){
			$this->addVar('apiclass',$classname);
			$this->setStatus('noclass');
			return;
		}
		
		$oapi=new $classnamei();
		$this->apiIniter($oapi);
		$oapi->initer($this->_p_,$this->apip,$this->apim,$this->apimi,$this->action);
		$this->apiInit($oapi);
		$oapi->init();
		$oapi->authed();
		$oapi->load();
		if($oapi->isauth) $oapi->parser();
		unset($oapi);
	}
	
	public function apiIniter(&$cls)
	{
		$cls->ua=&Ua::instance(APP_UA);
		//$cls->ua=&$this->ua;
		$cls->serve=&$this->serve;
	}
	public function apiInit(&$oapi)
	{

	}

}
