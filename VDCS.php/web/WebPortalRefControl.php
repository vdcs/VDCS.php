<?
trait WebPortalRefControl
{
	/*
	use WebPortalRefControl{
		WebPortalRefControl::doMessage as public doMessageBase;
	}
	*/
	
	protected $_box=array();

	/*
	public function __destruct()
	{
		parent::__destruct();
		unset($this->_box);
	}
	*/
	
	
	protected $iscontrol=true;
	public function controlInit()
	{
		$this->initControl();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initPages()
	{
		if($this->isInitPages) return; $this->isInitPages=true;
		$this->ctl->loadUI();
		$this->ctl->initPages();
		$this->ctl->pages->setPathTemplate(appDirPath('common.control'));
		$this->ctl->pages->setPathForm(appDirPath('common.channel/'.$this->_chn_.'/'));
		$this->ctl->pages->addPathForm('form.app',appDirPath('common.channel/config/form/'));
		$this->ctl->pages->addPathForm('form.config',appDirPath('common.config/form/'));
		$this->ctl->pages->addPathForm('manage.channel.config',appDirPath('manage.channel.config/form/'));
		$this->ctl->pages->addPathForm('manage.config',appDirPath('manage.config/form/'));
		$this->ctl->pages->addPathForm('vchannela',appDirPath('vdcs.web/'.VDCS_CHANNELA.'/'.$this->_chn_.'/'));
		$this->ctl->pages->addPathForm('vchannel',appDirPath('vdcs.web/'.VDCS_CHANNEL.'/'.$this->_chn_.'/'));
		$this->ctl->pages->addPathForm('form.vdcs.web',appDirPath('vdcs.web/config/form/'));
		$this->ctl->pages->addPathForm('form.vdcs.manage',appDirPath('vdcs.manage/config/form/'));
		$this->ctl->pages->setFileForm(appDirPath('common.channel/'.$this->_chn_.'/').FILENAME_FORM);
		$this->ctl->pages->setFileForms(appDirPath('common.channel/'.$this->_chn_.'/').FILENAME_FORMS);
		$this->ctl->pages->doFormInit();
		$this->ctl->pages->setFormAction($this->action);
		$this->ctl->pages->addFormPre('channel',$this->_chn_);
		$this->theme->setDP('pages.channel',$this->_chn_);
		$this->theme->setDP('pages.portal',$this->_p_);
		$this->theme->setDP('pages.module',$this->_m_);
		$this->theme->setDP('pages.modulei',$this->_mi_);
		$this->theme->setDP('pages.extend',$this->_x_);
	}
	public function loadPages()
	{
		$this->initPages();
		
	}
	public function loadPagesForm(){$this->ctl->pages->loadForm();}
	public function doPagesParse(){$this->ctl->doPagesParse($this->ctl->treeData);}
	public function doPagesFormParse()
	{
		$this->ctl->doPagesFormParse();
		//$this->ctl->treeDTML->addItem('_pages.debug',$this->ctl->pages->getDebug());
		//$this->ctl->treeDTML->addItem('_pages.form',$this->ctl->pages->getFormParse());
	}
	
	public function loadPaging()
	{
		$this->ctl->loadPaging();
		$this->ctl->p->setPageNum(5);
		$this->ctl->p->setListNum(15);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function listMode($module='',$mode=null,$tit='sub')
	{
		$_module=$module?($module.':'):'';
		$_mode=$mode==null?$this->mode:$mode;
		if(!array_key_exists($_module.'query.'.$_mode,$this->_var)) $_mode=$this->_var[$_module.'mode.default'];
		if($tit!=null){
			$_title=$this->_var[$_module.'title.'.$_mode];
			if(!$_title) $_title=$this->_var[$_module.'title'];
			if(!$_title) $_title=$this->cfg->getTitle($tit);
			$this->cfg->setTitle($tit,$_title);
		}
		$this->_var['mode.query']=$this->_var[$_module.'query.'.$_mode];
		$this->_var['mode.order']=$this->_var[$_module.'order.'.$_mode];
		if(!$this->_var['mode.order']) $this->_var['mode.order']=$this->_var[$_module.'order'];
		if(!$this->_var['mode.order']) $this->_var['mode.order']=$this->_var[$_module.'field.id'].' desc';
	}
	
	public function sqlFilter($sql)
	{
		$sql=preg_replace('/{@'.PATTERN_FLAG_VAR.'}/ies','\$this->_var[\'$1\']',$sql);
		return $sql;
	}
	
	public function handle($module='')
	{
		$this->_var['_handle.status']='do';
		$_module=$module?($module.':'):'';
		$_ids=utilCode::toValues(querys('_select_id'),1,'string');
		$_handle=post('_select_handle');
		if($_handle&&len($_ids)>0){
			$this->_var['_handle.status']='dispose';
			$sql=$this->_var[$_module.'sql.handle.'.$_handle];
			if(len($sql)>0){
				$sql=$this->sqlFilter($sql);
				$sql=rd($sql,'ids',$_ids);
				//debugx(($sql);
				if(!$this->_var['_handle.test']) DB::execBatch($sql);
				$this->theme->setModule('handle');
				$this->_var['_handle.status']='succeed';
				$this->_var['_handle.total']=count(toSplit($_ids,','));
				$this->_var['_handle.message']='#'.$_module.'ok.'.$_handle.'#count='.$this->_var['_handle.total'].';total='.$this->_var['_handle.total'];
				$this->_var['_handle.backurl']=post('_backurl');
				if(!$this->_var['_handle.backurl']) $this->_var['_handle.backurl']=$this->getURL('action=list');
			}
		}
	}
	public function handler($arg='theme,box')
	{
		if($this->_var['_handle.status']!='succeed') return;
		//if(inp($arg,'theme')>0) $this->theme->setModule('handle');
		if(inp($arg,'box')>0) $this->doBoxMessage('succeed',$this->_var['_handle.message'],$this->_var['_handle.backurl']);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doMessage($type,$state,$msg='',$url='')
	{
		$this->doMessageBase($type,$state,$msg,$url);
	}
	public function doMessageBase($type,$state,$msg='',$url='')
	{
		switch($type){
			case 'common':
				$this->theme->setPage('handle');
				$this->theme->setModule('message');
				$this->theme->setAction('');
				$this->theme->setStatus('');
				break;
			case 'none':
				break;
			default:
				$this->theme->setModule('handle');
				break;
		}
		$this->doBoxMessage($state,$msg,$url);
	}
	public function doBoxMessage($state,$msg='',$url='')
	{
		utilString::lists($state,$status,$tit,':');
		//list($status,$tit)=explode(':',$state);
		if(len($tit)<1) $tit=$this->_box['title.'.$status];
		$state=$status.':'.$tit;
		WebHandleMessage::msg($state,1);
		if($msg) WebHandleMessage::att('explain',$msg);
		if($url) WebHandleMessage::btn('back',WebHandleMessage::lang('back'),$url);
	}
	public function boxSet($k,$v){$this->_box[$k]=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function getLang($key){return '['.$key.']';}
	
}
?>