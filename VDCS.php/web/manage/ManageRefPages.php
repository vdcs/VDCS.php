<?
trait ManageRefPages
{
	
	public function getPagesFileAt($type_='')
	{
		switch($type_.''){
			case '2':
				$re=appv('form.at.defined');
				if(!$re) $re=$this->getConfig('form.at.defined');
				break;
			case '1':
				$re=appv('form.at.defined');
				if(!$re) $re=$this->setting('form.at.defined');
				break;
			case '0':
				$re=appv('form.at.defaulted');
				if(!$re) $re=$this->setting('form.at.defaulted');
				break;
		}
		return $re;
	}
	
	public function loadPages()
	{
		if($this->isLoadPages) return;$this->isLoadPages=true;
		if($this->_p_ && !$this->FormFile){
			//$this->FormFile=$this->_p_;		//表单文件
		}
		$this->ctl->initPages();
		$this->pages->setPathTemplate(appDirPath('common.config/control'));
		//debugx(ManageCommon::getPath('channel.config'));
		//debugx(appDirPath('vdcs.mchannel/{$channel}/config/'));
		//debugx(ManageCommon::getPath('config/form/'));
		//debugx(appDirPath('vdcs.mconfig/form/'));
		$this->pages->setPathForm(ManageCommon::getPath('channel.config'),ManageCommon::getPath('channela.config'),ManageCommon::getPath('channelc.config'));
		$this->pages->addPathForm('form.vchannel',appDirPath('vdcs.mchannel/{$channel}/config/'));
		$this->pages->addPathForm('form.vchannela',appDirPath('vdcs.mchannela/{$channel}/m/config/'));
		$this->pages->addPathForm('form.vchannelc',appDirPath('vdcs.mchannelc/{$channel}/m/config/'));
		$this->pages->addPathForm('form.config',appDirPath('common.config/form/'));
		$this->pages->addPathForm('form.app',ManageCommon::getPath('config/form/'));
		$this->pages->addPathForm('form.vdcs',appDirPath('vdcs.mconfig/form/'));
		$this->pages->setFileForm(FILENAME_FORM);
		$this->pages->setFileForms(FILENAME_FORMS);
		$this->pages->setFileAt($this->getPagesFileAt(0),$this->getPagesFileAt(1),$this->getPagesFileAt(2));
		$this->pages->doFormInit();
		if($this->chn->is()){
			//echo $this->chn->get();
			//debugx($this->chn->getTitle());
			if(!$this->action) $this->action='list';
			$this->addVar('titles',$this->chn->getTitle(null,''));
			$this->addVar('title',$this->chn->getTitle());
			$value=PagesCommon::getTemplatForm('submit.title.'.$this->action);
			$this->addVar('action.name',$value);
			$value=$this->chn->getLang('name');
			if(!$value) $value=$this->chn->getPre('name');
			$this->addVar('name',$value);
			$value=$this->chn->getLang('names');
			if(!$value) $value=$this->chn->getPre('names');
			$this->addVar('names',$value);
			$value=$this->chn->getLang('unit');
			if(!$value) $value=$this->chn->getPre('unit');
			$this->addVar('unit',$value);
			
			$this->pages->setFormChannel($this->chn->get());
			$this->pages->setFormModule($this->modules);
			if($this->FormFile) $this->pages->setFormFile($this->FormFile);
			$this->pages->setFormError($this->e);
			$this->pages->setFormTitle($this->chn->getTitle());
			$this->pages->setFormAction($this->action);
			$this->pages->addFormPre('channel',$this->chn->get());
			$this->pages->addFormPre('title',$this->chn->getTitle());
			//#########
			$this->chnPre->doBegin();
			for($t=0;$t<$this->chnPre->getCount();$t++){
				$this->pages->addFormPre($this->chnPre->getItemKey(),$this->chnPre->getItemValue());
				$this->chnPre->doMove();
			}
			//##########
		}
		$this->loadPagesAppend();
		
		$this->loadUI();
	}
	public function loadPagesAppend()
	{
		//#########
		$treeCForm=$this->configTree('setting.form.');
		$treeCForm->doBegin();
		for($t=0;$t<$treeCForm->getCount();$t++){
			$this->pages->addFormPre('@'.$treeCForm->getItemKey(),$treeCForm->getItemValue());
			$treeCForm->doMove();
		}
		//#########
		$treeCForm=$this->chn->getConfigureTree()->getFilterTree('config.form.');
		$codetype=$treeCForm->getItem('code.type');
		switch($codetype){
			case 'ubb':
				$treeCForm->addItem('sp.code','1');
				$treeCForm->addItem('editor','editor_ubb');
				break;
			case 'html':
				$treeCForm->addItem('sp.code','2');
				$treeCForm->addItem('editor','editor_html');
				break;
		}
		//debugTree($treeCForm);
		$treeCForm->doBegin();
		for($t=0;$t<$treeCForm->getCount();$t++){
			$this->pages->addFormPre('@'.$treeCForm->getItemKey(),$treeCForm->getItemValue());
			$treeCForm->doMove();
		}
 		//$this->getConfig('table.px')
 		//#########
		unset($treeCForm);
	}
	
	public function loadPagesForm($stype=1)
	{
		if($this->isLoadPagesForm) return;$this->isLoadPagesForm=true;
		$this->theme->setPage('formx');
		$this->theme->setModule('');
		$this->theme->setModulei('');
		$this->theme->setAction('');
		$this->theme->setChild(trim($this->_chn_.'.'.$this->_p_.'.'.$this->_m_.'.'.$this->_mi_,'.'));
		if($this->chn->is()){
			$tmpAction=$this->v('action.value');
			if(!$tmpAction) $tmpAction=$this->action;
			$tmpFiledPic=$this->getConfig($this->_m_,'table.field.pic');
			$tmpFiledsAffix=$this->getConfig($this->_m_,'table.field.affix');
			if($tmpFiledPic || $tmpFiledsAffix){
				$tmpFiletype=$this->getConfig($this->_m_,'pic.filetype');
				$tmpFiledsPic=$this->getConfig($this->_m_,'table.field.spic');
				$tmpAryAffix=toSplit($tmpFiledsAffix,',');
				switch($tmpAction){
					case 'add':
						if(!$tmpFiletype) $tmpFiletype='pic';
						$tmpfilename=CommonUpload::getFilenameMake();
						$this->pages->addFormPre('up.filetype',$tmpFiletype);
						$this->pages->addFormPre('up.filename',$tmpfilename);
						$this->pages->addFormPre('up.fileinput',$tmpFiledPic);
						if($tmpFiledsPic){
							$tmpThumbName=CommonUpload::getFilenameMake().'_thumb';
							$this->pages->addFormPre('up.thumbtype',$tmpFiletype);
							$this->pages->addFormPre('up.thumbname',$tmpThumbName);
							$this->pages->addFormPre('up.thumbinput',$tmpFiledsPic);
						}
						$tmpFiletype='affix';
						for($a=0;$a<count($tmpAryAffix);$a++){
							$tmpfilename=CommonUpload::getFilenameMake();
							$this->pages->addFormPre('up.filetype.'.$tmpAryAffix[$a],$tmpFiletype);
							$this->pages->addFormPre('up.filename.'.$tmpAryAffix[$a],$tmpfilename);
						}
						break;
					case 'edit':
						$tmpFiletype=utilFile::getFilePart($this->pages->getFormTreeItem($tmpFiledPic),'ext');
						if(!$tmpFiletype) $tmpFiletype='pic';
						$tmpfilename=utilFile::getFilePart($this->pages->getFormTreeItem($tmpFiledPic),'name');
						if(!$tmpfilename) $tmpfilename=CommonUpload::getFilenameMake();
						$this->pages->addFormPre('up.filetype',$tmpFiletype);
						$this->pages->addFormPre('up.filename',$tmpfilename);
						$this->pages->addFormPre('up.fileinput',$tmpFiledPic);
						if($tmpFiledsPic){
							$tmpFiletype=utilFile::getFilePart($this->pages->getFormTreeItem($tmpFiledsPic),'ext');
							if(!$tmpFiletype) $tmpFiletype='pic';
							$tmpThumbName=utilFile::getFilePart($this->pages->getFormTreeItem($tmpFiledsPic),'name');
							if(!$tmpThumbName) $tmpThumbName=$tmpfilename.'s';
							$this->pages->addFormPre('up.thumbtype',$tmpFiletype);
							$this->pages->addFormPre('up.thumbname',$tmpThumbName);
							$this->pages->addFormPre('up.thumbinput',$tmpFiledsPic);
						}
						for($a=0;$a<count($tmpAryAffix);$a++){
							$tmpFiletype=utilFile::getFilePart($this->pages->getFormTreeItem($tmpAryAffix[$a]),'ext');
							if(!$tmpFiletype) $tmpFiletype='affix';
							$tmpfilename=utilFile::getFilePart($this->pages->getFormTreeItem($tmpAryAffix[$a]),'name');
							if(!$tmpfilename) $tmpfilename=CommonUpload::getFilenameMake();
							$this->pages->addFormPre('up.filetype.'.$tmpAryAffix[$a],$tmpFiletype);
							$this->pages->addFormPre('up.filename.'.$tmpAryAffix[$a],$tmpfilename);
						}
						break;
				}
			}
		}
		if($stype==1) $this->pages->loadForm();
		
	}
	public function doPagesParse()
	{
		$this->ctl->doPagesParse($this->treeData);
	}
	public function doPagesFormParse($tit='')
	{
		if(!$this->isLoadPagesForm) return;
		if(!$tit) $tit=$this->pages->getFormTitle();
		//debugx($this->pages->getFormTitle());
		//$this->pages->setFormTitle('');
		if($this->v('block.id')) $divAppend=' id="'.$this->v('block.id').'"';
		if($this->v('block.hidden')=='yes') $divAppend.=' style="display:none;"';
		$re='';
		$re.=NEWLINE.$this->pages->getDebug();
		$re.=NEWLINE.$this->pages->getFormParse();
		//$re=$this->ui->getBlock($tit,$re,'',$divAppend);
		$this->addDTML('_pages.form',$re);
		unset($re);
	}
	
	public function loadUI()
	{
		$this->ctl->initUI();
		$this->ctl->ui->setPath(ManageCommon::getPath('themes/config/'));
		$this->ctl->ui->setFileInterface(appFilePath('vdcs.mconfig/interface'),appFilePath('manage.common/config/interface'),appFilePath('manage.themes/config/interface'));
		$this->ctl->ui->setFileLangs(appFilePath('vdcs.mconfig/language'));
		$this->ctl->ui->addPath('langs','manage.common',appFilePath('manage.common/config/language'));
		$this->ctl->ui->addPath('langs','manage.themes',appFilePath('manage.themes/config/language'));
		$this->ctl->loadUI(0);
	}
	
	/*
	########################################
	########################################
	*/
	public function getLangs($strKey){return $this->ui->getLangs($strKey);}
	public function getTitles($strKey,$svar){return $this->ui->getTitles($strKey,$svar);}
	
	public function toReplaceLangs($strer){return $this->ui->toReplaceLangs($strer);}
	
}
?>