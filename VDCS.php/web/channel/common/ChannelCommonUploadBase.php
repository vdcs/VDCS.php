<?
class ChannelCommonUploadBase extends WebPortalBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	
	const ChannelCommon		= 'support,common';
	const ChannelCommons		= 'index,page,ads,links';
	
	public $up=null;
	
	
	public function setChannelSort($v){$this->_var['cfg.channel.sort']=$v;}
	public function cfgChannel($k){return $this->cfg->getChannelConfigure($this->up->channel,$this->_var['cfg.channel.sort'].$k);}
	
	public function inited()
	{
		$this->up=new WebUpload();
		$this->up->doInit();
		
		$this->ischannel=false;
		$this->ischannelsorts=true;
		$this->ischannelcommon=false;
		//debugx($this->up->channel);
		if($this->cfg->getChannelTree($this->up->channel,'configure')->isItem('pre.channel')) $this->ischannel=true;
		// common channel
		if(inp(self::ChannelCommon,$this->up->channel)>0) $this->ischannelcommon=true;
		if(!$this->ischannel){
			if(inp(self::ChannelCommons,$this->up->channel)>0) $this->ischannelcommon=true;
			if(!$this->ischannelcommon) return;
		}
		if(!$this->ischannelcommon){
			$this->setChannelSort('config.'.($this->up->sorts?($this->up->sorts.'.'):'').'up.');
			//debugx($this->_var['cfg.channel.sort']);
			//debugx($this->up->sorts);
			if($this->up->sorts && !$this->cfg->getChannelTree($this->up->channel,'configure')->isItem($this->_var['cfg.channel.sort'].'mode')){
				$this->ischannelsorts=false;
				return;
			}
		}
	}
	
	public function doAuth()
	{
		global $manager;
		$manager=new UaManager();
		$manager->doInit();
		if($this->ischannelsorts){
			$_urc=$this->cfgChannel('ua');
			//debugx($_urc);
			if($_urc) $this->UARC=$_urc;
		}
		//$this->doAuther();
		$this->ua=&Ua::instance($this->UARC);
		$this->ua->doInit();
		if($this->ua->isLogin()){
			$this->up->setVar('uurc',$this->ua->rc);
			$this->up->setVar('uuid',$this->ua->id);
			$this->up->setVar('unames',$this->ua->name);
			if($manager->isLogin()) $this->up->setManager(true);
			$this->up->setVar('total.today',$this->up->getTotalToday($this->ua->rc));
		}
		else{
			if($manager->isLogin()){
				$this->up->setManager(true);
				$uurc=$manager->getData('uurc');
				$uuid=$manager->getData('uuid');
				$unames='';
				$this->up->setVar('uurc',$this->ua->rc);
				if($uurc==$this->ua->rc){
					$this->up->setVar('uuid',$this->ua->id);
					$this->up->setVar('uaname',$unames);
				}
			}
		}
		//debuga($this->up->_var);
		//debugx('sid='.$_REQUEST['PHPSESSID']);
		//debugx('sid='.session_id());
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		//dcsLog('sid',$_REQUEST['PHPSESSID']);
		//dcsLog('sid',session_id());
		//dcsLog('ua',$this->up->getVar('uurc').','.$this->up->getVar('uuid'));
	}
	
	
	public function isLoaded()
	{
		return $this->_isLoaded&&$this->_isChannelLoaded;
	}
	public function isChecked()
	{
		return $this->_isLoaded&&$this->_isChannelLoaded&&$this->up->isCheck();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInitPre()
	{
		$this->loadVcode('channel');
		if(!$this->up->isLogin()) return;
	}
	
	public function doLoadPre()
	{
		$this->cfg->setTitle('Upload');
		$this->theme->setDir('common/upload');
		$this->theme->setPage('upload');
		$this->doChannelLoad();
		if(!$this->_isChannelLoaded) return;
		$this->_isLoaded=true;
	}
	
	public function doChannelLoad()
	{
		$this->theme->setModule('form');
		
		if(!$this->up->isManager() && !$this->up->isTurnOn()){
			$this->doParseMessage('close');
			return;
		}
		if(!$this->up->isLogin()){
			$this->doParseMessage('nologin');
			return;
		}
		if(!$this->up->isInit()){
			$this->doParseMessage('noinit');
			return;
		}
		if(!$this->ischannel && !$this->ischannelcommon){
			$this->doParseMessage('noparam1');
			return;
		}
		if(!$this->ischannelsorts){
			$this->doParseMessage('noparam2');
			return;
		}
		if(!$this->ischannelcommon){
			$this->up->setMode($this->cfgChannel('mode'));
			$this->up->setFormat($this->cfgChannel('format'));
			$this->doChannelSet();
			if(!$this->_isChannelSet){
				// configure savedir
				$_dirbase=$this->up->getVar('savedir');
				$_dirbaseCfg=$this->cfgChannel('dirbase');
				if($_dirbaseCfg){
					if($_dirbaseCfg=='null'){
						$_dirbase='';
					}
					else{
						$_dirbase=$_dirbaseCfg;
						if(right($_dirbase,1)!=DIR_SEPARATOR) $_dirbase.=DIR_SEPARATOR;
						$_dirbase=rd($_dirbase,'channel',$this->up->channel);
					}
				}
				$_dirsn=$this->up->getVar('_dir.sn');
				if(!$_dirsn) $_dirsn=$this->cfgChannel('dirsn');
				if(!$_dirsn) $_dirsn=$this->up->getVarInt('uuid');
				$_dirsn=$this->toVarSnParser($_dirsn);
				$this->up->setVar('savedir',$_dirbase.utilIO::toDirVar($this->cfgChannel('dir'),$_dirsn));
				//$this->up->setVar('savedir',$this->up->getVar('savedir').VDCSTIME::toConvert('','Ym').DIR_SEPARATOR);
				// configure filename
				$_filename=$this->cfgChannel('filename');
				$_filename=$this->toVarFilenameParser($_filename);
				if($_filename) $this->up->setVar('filename',$_filename);
				// configure filetype
				$_filetype=$this->cfgChannel('filetype');
				if($_filetype) $this->up->setVar('filetype',$_filetype);
				if(ins($this->up->getVar('fileinput'),'url')>0){
					if(len($this->up->getVar('filetype'))<1) $this->up->setVar('filetype','affix');
				}
				// configure maxsize
				$_maxsize=toInt($this->cfgChannel('maxsize'));
				if($_maxsize) $this->up->setVar('maxsize',$_maxsize);
				// configure maxtotal
				$_maxtotal=toInt($this->cfgChannel('maxtotal'));
				if($_maxtotal) $this->up->setVar('maxtotal',$_maxtotal);
				// configure cover
				$_cover=$this->cfgChannel('cover');
				if(inp('yes,true,1',$_cover)>0) $this->up->setCover(true);
			}
			$this->doChannelSets();
		}
		//debugx($this->up->getVar('savedir'));
		if(len($this->up->getVar('savedir'))<3){
			$this->doParseMessage('nodir');
			return;
		}
		
		//debugx(CommonUpload::getFilenameMake());
		//debugx(appDirPath('upload'));
		$this->up->setVar('basepath',appDirPath('upload'));
		$this->up->setVar('baseurl',appURL('upload'));
		$this->up->doLoad();
		//debuga($this->up->_var);
		if(!$this->up->isLoad()){
			$this->doParseMessage($this->up->getStatus());
			return;
		}
		/*
		$baseurl=appDirPath('upload');
		$opic=new UploadPic();
		debugx('thumbnames='.$opic->toMakeThumb($baseurl,'big.jpg','',120,90,1));
		*/
		$this->_isChannelLoaded=true;
	}
	protected function doChannelSet(){}
	protected function doChannelSets(){}
	
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		if(!$this->isChecked())return;
		//if(!$this->isChecked())return;
		//if(!$this->up->isCheck()) return;
		if($this->up->isPost()){
			//vcp check
			$this->up->doParse();
			if($this->up->isSucceed()){
				$this->up->doParseThumb();
				$this->up->doParseWatermark();
				$this->doParseNote();
			}
		}
		$this->doParseMessage($this->up->getStatus());
	}
	
	
	//channel	sorts		types		rootid		dataid		uurc	uuid
	//article					123		0		
	//account	user.avatar			10001		0		user	10001
	//account	user.avatar	big		10001		0		user	10001
	//account	user.avatar	small		10001		0		user	10001
	//club		logo				100001		0		company	100001
	protected function doParseNote()
	{
		$FileURL=$this->up->getVar('file.urls');
		$FilePath=$this->up->getVar('file.url');
		$FileExt=$this->up->getVar('file.ext');
		$FileSize=toInt($this->up->getVar('file.size'));
		$FileName=$this->up->getVar('file.name');
		$RealName=$this->up->getVar('file.names');
		$img_is=utilFile::isExtPic($FileExt)?1:0;
		$uurc=$this->up->getVar('uurc');
		$uuid=$this->up->getVarInt('uuid');
		$channel=$this->up->channel;$rootid=0;$dataid=0;
		$sorts=$this->up->sorts;
		$types=$this->up->types;
		$status=toInt($this->cfgChannel('data.status'));
		
		$sqlQuery='';
		switch($this->cfgChannel('data.index')){
			case 'key':
				$rootid=$uuid;
				if($rootid<1){
					//dcsLog('upload.note','(no rootid) '.$_SERVER['REQUEST_URI']);
					return;
				}
				$sqlQuery='channel='.DB::q($channel,1).' and sorts='.DB::q($sorts,1).' and types='.DB::q($types,1).' and rootid='.$rootid.'';
				break;
			case 'ua':
				if($uuid<1){
					//dcsLog('upload.note','(no ua) '.$_SERVER['REQUEST_URI']);
					return;
				}
				$rootid=$uuid;
				$sqlQuery='channel='.DB::q($channel,1).' and sorts='.DB::q($sorts,1).' and types='.DB::q($types,1).' and uurc='.DB::q($uurc,1).' and uuid='.$uuid.'';
				break;
			default:
				$sqlQuery='path='.DB::q($FilePath,1).'';
				break;
		}
		$sql=DB::sqlSelect(CommonUpload::TableName,'',CommonUpload::FieldID.',path',$sqlQuery,'',1);
		//debugx($sql);
		//dcsLog('upload.note.query',$sql);
		$treeRS=DB::queryTree($sql);
		$upid=$treeRS->getItemInt(CommonUpload::FieldID);
		//debugx($upid);
		
		if($this->up->isThumb()){
			$ThumbPath=$this->up->getVar('thumb.url');
		}
		$treeUp=newTree();
		$treeUp->addItem('channel',$channel);
		$treeUp->addItem('rootid',$rootid);
		$treeUp->addItem('dataid',$dataid);
		$treeUp->addItem('uurc',$uurc);
		$treeUp->addItem('uuid',$uuid);
		$treeUp->addItem('sorts',$sorts);
		$treeUp->addItem('types',$types);
		$treeUp->addItem('remote','');
		$treeUp->addItem('url',$FileURL);
		$treeUp->addItem('path',$FilePath);
		$treeUp->addItem('filename',$FileName);
		$treeUp->addItem('realname',$RealName);
		$treeUp->addItem('ext',$FileExt);
		$treeUp->addItem('size',$FileSize);
		$treeUp->addItem('img',$img_is);
		$treeUp->addItem('img_width',$imgwidth);
		$treeUp->addItem('img_height',$imgheight);
		$treeUp->addItem('thumb',$ThumbPath);
		$treeUp->addItem('sp_ip',DCS::ip());
		$treeUp->addItem('sp_agent',DCS::agent());
		$treeUp->addItem('status',$status);
		$treeUp->addItem('tim',DCS::timer());
		$treeUp->addItem('tim_up',0);
		//debugTree($treeUp);
		if($upid>0){
			$_oldpath=$treeRS->getItem('path');
			if($_oldpath!=$FilePath){
				utilFile::doFileDel($this->up->getVar('basepath').$_oldpath);
			}
			$treeUp->addItem('tim_up',DCS::timer());
			$FieldEdit='uurc,uuid,url,path,filename,realname,ext,size,img,img_width,img_height,thumb,sp_ip,sp_agent,status,tim_up';
			$sql=DB::sqlUpdate(CommonUpload::TableName,$FieldEdit,$treeUp,CommonUpload::FieldID.'='.$upid);
			//debugx($sql);
			DB::exec($sql);
		}
		else{
			$FieldAdd=$treeUp->getFields();
			$sql=DB::sqlInsert(CommonUpload::TableName,$FieldAdd,$treeUp);
			//debugx($sql);
			DB::exec($sql);
			$upid=DB::insertid();
		}
		if($upid>0) $this->up->setVar('file.id',$upid);
		$this->up->setVar('upid',$upid);
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function doParseMessage($status,$msg=null)
	{
		if(len($status)<1 || $status=='init') return;
		global $theme;
		$theme->setModule('message');
		$LinkMode='back';
		if($msg==null) $msg=$this->up->toStatusMessage($status);
		$this->doParseMessageExtend($status,$LinkMode);
		$this->up->setVar('linkmode',$LinkMode);
		$this->up->setVar('status',$status);
		$this->up->setVar('message',$msg);
		
	}
	protected function doParseMessageExtend($status,&$LinkMode){}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCachePre()
	{
		global $theme;
		$theme->doObjectLoad('up');
		$theme->output=CommonUpload::toDTMLCache($theme->output);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toVarSnParser($re)
	{
		$re=rd($re,'uuid',$this->up->getVarInt('uuid'));
		return $re;
	}
	public function toVarFilenameParser($re)
	{
		$re=rd($re,'uuid',$this->up->getVarInt('uuid'));
		$re=rd($re,'channel',$this->up->getVar('channel'));
		$re=rd($re,'sorts',$this->up->getVar('sorts'));
		$re=rd($re,'types',$this->up->getVar('types'));
		$re=rd($re,'rnd',utilCode::getRand(3,6));
		$re=rd($re,'rnds',utilCode::getRand(6,2));
		$re=rd($re,'times',CommonUpload::getFilenameMake());
		return $re;
	}
	
}
?>