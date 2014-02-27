<?
class WebUpload
{
	//public $mode,$channel,$formname,$filetype,$filename,$fileinput,$thumbname,$thumbinput,$fileid,$thumbid;
	public $channel,$sorts,$types;
	public $_var=array();
	protected $treeConfig=null;
	protected $modUpload=null,$modPic=null;
	
	public function __construct()
	{
		$this->_var['uurc']		= '';
		$this->_var['uuid']		= -1;
		$this->_var['uaname']		= '';
		
		$this->_var['mode']		= '';
		$this->_var['channel']		= '';
		
		$this->_var['file.id']		= -1;
		$this->_var['file.size']	= -1;
		$this->_var['thumb.id']		= -1;
		$this->_var['thumb.size']	= -1;
		
		$this->_var['ischeck']		= false;
		$this->_var['isinit']		= false;
		$this->_var['ismanager']	= false;
		$this->_var['iscover']		= false;		//是否可以覆盖
		$this->_var['total.today']	= -1;
		
		$this->_var['file']		= 'file1';
		
		$this->_var['allowext']		= '';
		$this->_var['maxsize']		= -1;
		$this->_var['maxsize.base']	= 500;
		
		$this->_var['basepath']		= './';
		$this->_var['baseurl']		= '';
		$this->_var['savedir']		= '';
		$this->_var['savepath']		= '';
		
		$this->_var['status']		= 'init';
		
		$this->treeConfig=newTree();
	}
	public function __destruct()
	{
		unset($this->_var,$this->treeConfig);
		unset($this->modUpload,$this->modPic);
	}
	
	
	public function getMode(){return $this->mode;}
	public function setMode($v){$this->_var['mode']=$v;$this->mode=$v;}
	
	public function getResource(){return $this->resource;}
	public function setResource($v){$this->_var['resource']=$v;$this->resource=$v;}
	
	public function getFormat(){return $this->format;}
	public function setFormat($v){$this->_var['format']=$v;$this->format=$v;}
	
	
	public function setVar($k,$v){$this->_var[$k]=$v;}
	public function getVar($k){return $this->_var[$k];}
	public function getVarInt($k){return intval($this->_var[$k]);}
	
	public function setChannel($s){$this->_var['channel']=$s;$this->channel=$s;}
	public function setSorts($s){$this->_var['sorts']=$s;$this->sorts=$s;}
	public function setTypes($s){$this->_var['types']=$s;$this->types=$s;}
	
	public function isCheck()
	{
		if(!$this->_var['ischecked']){
			if($this->_var['isinit']&&$this->isLogin()) $this->_var['ischeck']=true;
		}
		return $this->_var['ischeck'];
	}
	public function isLogin(){return ($this->_var['ismanager']||$this->_var['uuid']>0);}
	public function isInit(){return $this->_var['isinit'];}
	public function isCover(){return $this->_var['iscover'];}
	public function isSucceed(){return $this->_var['status']=='succeed';}
	
	public function isThumb(){return $this->_var['isthumb'];}
	public function isPost(){return $_SERVER['REQUEST_METHOD']=='POST';}
	
	public function setManager($b)
	{
		$this->_var['ismanager']=$b;
		if($this->_var['ismanager']) $this->_var['iscover']=true; 
	}
	public function isManager(){return $this->_var['ismanager'];}
	public function setCover($b){$this->_var['iscover']=$b;}
	
	public function getStatus(){return $this->_var['status'];}
	public function setStatus($v){$this->_var['status']=$v;}
	
	public function toStatusMessage($status)
	{
		$re='';
		switch($status){
			case 'close':			$re='系统关闭了上传服务！';break;
			case 'nologin':			$re='您还没有登录认证！';break;
			case 'noinit':			$re='上传文件的参数不正确！';break;
			case 'noparam':			$re='错误的上传参数';break;
			case 'noparam1':		$re='错误频道['.$this->up->channel.']';break;
			case 'noparam2':		$re='错误的参数[sorts='.$this->up->sorts.']';break;
			case 'nodir':			$re='上传目录的参数不正确！';break;
			case 'nomax':			$re='禁止上传！';break;
			case 'maxtotal':		$re='上传数量限制！';break;
			default:			$re='['.$status.']';break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->_var['channel']=toLower(queryx('channel'));
		$this->_var['sorts']=toLower(queryx('sorts'));
		$this->_var['types']=toLower(queryx('types'));
		$this->_var['filetype']=toLower(queryx('filetype'));
		$this->_var['filename']=queryx('filename');
		$this->_var['fileinput']=queryx('fileinput');
		$this->_var['thumbtype']='pic';		//queryx('thumbtype');
		$this->_var['thumbname']=queryx('thumbname');
		$this->_var['thumbinput']=queryx('thumbinput');
		$this->_var['formname']=queryx('formname');
		$this->_var['inputtype']=queryx('inputtype');
		$this->_var['valuemode']=queryx('valuemode');
		
		$this->_var['channels']=$this->_var['channel'];
		if(ins($this->_var['channel'],'.')>0){
			utilString::lists($this->_var['channel'],$this->_var['channel.base'],$this->_var['channel.type'],'.');
			$this->_var['channel']=$this->_var['channel.base'];
			if(utilFile::isExtDenied($this->_var['channel.type'])) $this->_var['channel']='';
		}
		
		if(ins($this->_var['filetype'],'.')>0 || utilFile::isExtDenied($this->_var['filetype'])) $this->_var['filetype']='';
		if(len($this->_var['inputtype'])<1) $this->_var['inputtype']=$this->_var['filetype'];
		if(len($this->_var['savedir'])<1) $this->_var['savedir']=$this->_var['channel'].DIR_SEPARATOR;
		
		$this->channel=$this->_var['channel'];
		$this->sorts=$this->_var['sorts'];
		$this->types=$this->_var['types'];
		
		if(!$this->_var['ismanager']){
			$this->_var['filename']='';
			$this->_var['thumbname']='';
		}
		
		if($this->treeConfig->getCount()<1){
			$this->treeConfig=VDCSDTML::getConfigTree('common.config/data/upload');
			//debugTree($this->treeConfig,'treeConfig');
		}
		
		$this->doInitCheck();
	}
	public function doInitCheck()
	{
		$this->_var['isinit']=true;
		if(!$this->_var['channel'] || !$this->_var['savedir'] || $this->treeConfig->getCount()<1) $this->_var['isinit']=false;
	}
	
	public function doLoad()
	{
		if(!$this->isCheck()) return;
		
		$this->_var['total.max']=-1;
		if($this->_var['uuid']>0){
			$this->_var['total.max']=1000;		//$this->ua->getGroupConfigNum(56);
			$this->_var['maxtotal']&&$this->_var['total.max']=$this->_var['maxtotal'];
			if(!$this->_var['maxsize']) $this->_var['maxsize']=1000;		//$this->ua->getGroupConfigNum(57);
		}
		if($this->_var['ismanager']){
			$this->_var['total.max']=10000;
		}
		else{
			if($this->_var['total.max']<0){
				$this->setStatus('nomax');			//禁止上传
				return;
			}
			if($this->_var['total.today']>$this->_var['total.max']){
				$this->setStatus('maxtotal');			//上传数量限制
				return;
			}
		}
		
		$this->_var['filename'];
		if(!$this->_var['filename']) $this->_var['filename']=CommonUpload::getFilenameMake();
		
		if($this->_var['thumbinput']) $this->_var['isthumb']=true;
		if($this->_var['isthumb'] && len($this->_var['thumbname'])<1) $this->_var['thumbname']=CommonUpload::getFilenameMake();
		
		if($this->_var['filetype'] && inp('affix',$this->_var['filetype'])<1){
			$this->_var['allowext']=$this->getConfigValue('ext',$this->_var['filetype']);
			if(len($this->_var['allowext'])<1) $this->_var['allowext']=$this->_var['filetype'];
		}
		else{
			$this->_var['allowext']=$this->getConfigValue('allowext');
		}
		//debugx($this->_var['filetype'].'='.$this->_var['allowext']);
		
		if($this->_var['maxsize']<1){
			$this->_var['maxsize']=$this->getConfigInt('base.maxsize');
			if($this->_var['maxsize']<1) $this->_var['maxsize']=$this->_var['maxsize.base'];
		}
		$this->_var['maxsizes']=$this->_var['maxsize']*1024;
		
		$this->_var['basepath']=toDirPath($this->_var['basepath']);
		if(!$this->_var['savedir']) $this->_var['savedir']=DIR_SEPARATOR;
		if(!$this->_var['savepath']) $this->_var['savepath']=$this->_var['basepath'].$this->_var['savedir'];
		
		$this->_isLoad=true;
	}
	public function isLoad(){return $this->_isLoad;}
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		if(!$this->isCheck()) return;
		
		$this->modUpload=new utilUpload();
		$this->modUpload->doInit();
		$this->modUpload->setMode($this->mode);
		$this->modUpload->setResource($this->resource);
		$this->modUpload->setFormat($this->format);
		$this->modUpload->setVar('file',$this->_var['file']);
		$this->modUpload->setVar('allowext',$this->_var['allowext']);
		$this->modUpload->setVar('maxsize',$this->_var['maxsize']);
		$this->modUpload->setVar('maxsizes',$this->_var['maxsizes']);
		$this->modUpload->setVar('savepath',$this->_var['savepath']);
		$this->modUpload->setVar('filename',$this->_var['filename']);
		$this->modUpload->setVar('real.names',$this->_var['real.names']);
		$this->modUpload->setVar('save.quality',$this->_var['save.quality']);
		$this->modUpload->doLoad();
		$this->modUpload->doParse();
		
		$this->setStatus($this->modUpload->getStatus());
		//debugx($this->getStatus().'--'.$this->modUpload->getStatus());
		$this->setVar('file.names',$this->modUpload->getVar('file.names'));
		$this->setVar('file.name',$this->modUpload->getVar('file.name'));
		$this->setVar('file.size',$this->modUpload->getVar('file.size'));
		$this->setVar('file.type',$this->modUpload->getVar('file.type'));
		$this->setVar('file.ext',$this->modUpload->getVar('file.ext'));
		if($this->isSucceed()){
			$this->_var['file.url']=$this->_var['savedir'].$this->_var['file.name'];
			$this->_var['file.urls']=$this->_var['baseurl'].$this->_var['savedir'].$this->_var['file.name'];
		}
		//debuga($this->_var);
	}
	
	
	protected function initModulePic()
	{
		if(!$this->modPic){
			$this->modPic=new utilPic();
			$this->modPic->setVarArray($this->_var);
			$this->modPic->setConfigArray($this->treeConfig->getArray());
		}
	}
	public function doParseThumb()
	{
		if(!$this->_var['isthumb']) return;
		$this->initModulePic();
		$this->modPic->doParseThumb();
		$this->setVar('thumb.is',$this->modPic->getVar('thumb.is'));
		if($this->_var['thumb.is']){
			$this->setVar('thumb.name',$this->modPic->getVar('thumb.name'));
			$this->setVar('thumb.ext',$this->modPic->getVar('thumb.ext'));
			$this->setVar('thumb.size',$this->modPic->getVar('thumb.size'));
			$this->_var['thumb.url']=$this->_var['savedir'].$this->_var['thumb.name'];
			$this->_var['thumb.urls']=$this->_var['savedir'].$this->_var['thumb.name'];
		}
	}
	
	public function doParseWatermark()
	{
		$this->initModulePic();
		$this->modPic->doParseWatermark();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getConfigTree(){return $this->treeConfig->getArray();}
	public function getConfig($strKey){return $this->treeConfig->getItem($strKey);}
	public function getConfigInt($strKey){return $this->treeConfig->getItemInt($strKey);}
	public function getConfigNum($strKey){return $this->treeConfig->getItemNum($strKey);}
	public function setConfig($strKey,$strValue){$this->treeConfig->addItem($strKey,$strValue);}
	
	public function getConfigValue($key,$p1='')
	{
		$re='';
		switch(toLower($key)){
			case 'ext':
				$re=$this->getConfig('ext.'.$p1);
				if(len($re)<1) $re=$p1;
				break;
			case 'allowext':
				$re=$this->getConfig('ext.'.$p1);
				if(len($re)<1){
					if(len($re)<1 && len($p1)>0) $re=','.$p1;
					$type_=$this->getConfig('ext.pic');
					if(len($type_)>0) $re.=','.$type_;
					$type_=$this->getConfig('ext.affix');
					if(len($type_)>0) $re.=','.$type_;
					$type_=$this->getConfig('ext.mm');
					if(len($type_)>0) $re.=','.$type_;
					$type_=$this->getConfig('ext.other');
					if(len($type_)>0) $re.=','.$type_;
					if(len($re)>0) $re=substr($re,1);
				}
				break;
			default:
				$re=$this->getConfig($key);
				break;
		}
		return $re;
	}
	
	public function isTurnOn($model='base')
	{
		if(!$model) $model='base';
		$_status=$this->getConfig($model.'.status');
		return inp('1,yes,true',$_status)>0 ? true : false;
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public function getTotalToday($uurc='',$uuid=0)
	{
		if(!$uurc) $uurc=$this->getVar('uurc');
		if($uuid<1) $uuid=$this->getVarInt('uuid');
		return CommonUpload::getTotalToday($uurc,$uuid);
	}
	
}
?>