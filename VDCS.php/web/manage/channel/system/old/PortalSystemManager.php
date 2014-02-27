<?
class PortalSystemManager extends PortalSystemBase
{
	
	//####################
	protected function doAdd()
	{
		if(!$this->isChecked('lock')) return;
		global $dcs,$ctl;
		$this->id=0;
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				$this->treeData->addItem($this->TablePX.'password',utilCoder::toMD5($this->treeData->getItem($this->TablePX.'password')));
				DB::executeInsert($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doEdit()
	{
		if(!$this->isChecked('lock')) return;
		global $dcs,$ctl;
		$this->id=$this->id;
		$sqlQuery=$this->getConfig('table.field.id').'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$this->loadPages();
		$this->pages->setFormFile($this->module);
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			$this->doFormCheck($this->action);
			
			if($this->isRaiseError()) return;
			else{
				if($this->treeData->getItem($this->TablePX.'password')!=$this->treeRS->getItem($this->TablePX.'password')) $this->treeData->addItem($this->TablePX.'password',utilCoder::toMD5($this->treeData->getItem($this->TablePX.'password')));
				DB::executeUpdate($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$sqlQuery,$this->treeRS);
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	//####################
	protected function doFormCheck($action='')
	{
		global $dcs,$ctl;
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_no=$this->treeData->getItem($this->TablePX.'no');
			if(len($_no)>0){
				if(!utilCheck::isName($_no)){ $this->addError($this->getLang('error.norule.no')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'no=\''.$_no.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.no'));
				}
			}
			$_name=$this->treeData->getItem($this->TablePX.'name');
			if(len($_name)>0){
				if(!utilCheck::isName($_name)){ $this->addError($this->getLang('error.norule.name')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'name=\''.$_name.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.name'));
				}
			}
			$_password=$this->treeData->getItem($this->TablePX.'password');
			if(len($_password)>0){
				if(!utilCheck::isPassword($_password)) $this->addError($this->getLang('error.norule.password'));
			}
		}
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_username=$this->treeData->getItem($this->TablePX.'username');
			if(len($_username)>0){
				if(!utilCheck::isName($_username)){ $this->addError($this->getLang('error.norule.username')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'username=\''.$_username.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.username'));
				}
			}
		}
	}
	
	//####################
	public function doPopedoms()
	{
		if(!$this->isChecked('lock')) return;
		global $dcs,$ctl;
		
		$this->id=$this->id;
		$sqlQuery=$this->getConfig('table.field.id').'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->doMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		//##########
		$this->treeRS->addItem('gradename',PagesCommon::getDict('file:sort=manage.config/data.manager.grade',$this->treeRS->getItem($this->TablePX.'grade')));
		$_popedoms=$this->treeRS->getItem($this->TablePX.'popedoms');
		$popedomAry=toSplit($_popedoms,',');
		$tableChannel=VDCSDTML::getConfigTable('common.config/channel');
		$tableChannel->doBegin();
		while($tableChannel->isNext()){
			$_channel=$tableChannel->getItemValue('channel');
			$_popedom='';
			foreach($popedomAry as $_val){
				if(left($_val,len($_channel)+1)==$_channel.':'){
					$_popedom.=','.toSubstr($_val,len($_channel)+2);
				}
			}
			if(len($_popedom)>0) $_popedom=toSubstr($_popedom,2);
			$this->treeRS->addItem('popedom_'.$_channel,$_popedom);
		}
		$formXCML=$this->getPopedomFormXCML();
		//debugvc($formXCML);
		//##########
		$this->loadPages();
		$this->pages->setFormXML($formXCML);
		unset($formXCML);
		$this->pages->setFormTree($this->treeRS);
		$this->loadPagesForm();
		if(if(!$this->ready(true)) return;){
			$this->doPagesParse();
			
			if($this->isRaiseError()) return;
			else{
				$_popedoms='';
				//debugTree($this->treeData);
				$this->treeData->doBegin();
				for($t=1;$t<=$this->treeData->getLength();$t++){
					$_key=$this->treeData->getItemKey();
					if(left($_key,8)=='popedom_'){
						//debugx($this->treeData->getItemValue());
						$_channel=toSubstr($_key,9);
						$_value=$this->treeData->getItemValue();
						if($_value){
							$popedomAry=toSplit($this->treeData->getItemValue(),',');
							foreach($popedomAry as $_val){
								$_popedoms.=','.$_channel.':'.$_val;
							}
						}
					}
					$this->treeData->doMove();
				}
				if(len($_popedoms)>0) $_popedoms=toSubstr($_popedoms,2);
				$this->treeData->addItem($this->TablePX.'popedoms',$_popedoms);
				DB::executeUpdate($this->TableName,$this->TablePX.'popedoms',$this->treeData,$sqlQuery,$this->treeRS);
				$this->doMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
				return;
			}
		}
		$this->doPagesFormParse();
	}
	
	public function getPopedomFormXCML()
	{
		$tableChannel=VDCSDTML::getConfigTable('common.config/channel');
		$tableForm=VDCSXCML::newFormTable();
		//----------
		$treeForm=newTree();
		$treeForm->addItem('type','put.m_name');
		$treeForm->addItem('property','item=;action=;type=string;max=;min=');
		$treeForm->addItem('style','');
		$treeForm->addItem('caption','管理员名称');
		$treeForm->addItem('att',$_atts);
		$treeForm->addItem('value','');
		$treeForm->addItem('explain','');
		$tableForm->addItem($treeForm);
		//----------
		$treeForm=newTree();
		$treeForm->addItem('type','put.gradename');
		$treeForm->addItem('property','item=;action=;type=string;max=;min=');
		$treeForm->addItem('style','');
		$treeForm->addItem('caption','管理员等级');
		$treeForm->addItem('att',$_atts);
		$treeForm->addItem('value','');
		$treeForm->addItem('explain','');
		$tableForm->addItem($treeForm);
		//----------
		$tableChannel->doBegin();
		while($tableChannel->isNext()){
			$_atts='';
			$_type=$tableChannel->getItemValueInt('type');
			$_channel=$tableChannel->getItemValue('channel');
			//if($_channel!='account') continue;
			$_channelType=$_channel;
			switch($_type){
				case 1:		$_channelType='article';break;
				case 3:		$_channelType='photo';break;
			}
			//$_path=appFilePath('manage.channel.config/control');
			//$_path=rd($_path,'channel',$_channelType);
			//isFile($_path)
			//$treeControl=VDCSDTML::getConfigTree($_path);
			$treeControl=$this->chn->getTree($_channelType,'','control',false);
			$cLen=$treeControl->getCount();
			//debugTree($treeControl);
			if($cLen>1){
				$treeConfig=VDCSDTML::getConfigTree('common.channel/'.$_channel.'/config');
				$treeName=utilString::toTree(utilString::toFilterBlank($treeControl->getItem('popedom.title')),';','=');
				//debugTree($treeConfig);
				$treeControl->doBegin();
				for($t=1;$t<=$cLen;$t++){
					$_key=$treeControl->getItemKey();
					$_value=$treeControl->getItemValue();
					//debugx($_key.': '.$_value);
					if(left($_key,15)=='popedom.module.'){
						$_module='';$_modulec='';
						$_name='pre.name';
						$_names='pre.names';
						$_keys=toSubstr($_key,16);
						$_key=$_keys;
						if(left($_keys,2)=='__'){
							switch($_value){
								case 'newline':		$_atts.='<br/>';break;
							}
						}
						else{
							if(ins($_keys,'-')>0){
								$_module=toSubstr($_keys,1,ins($_keys,'-')-1);
								$_key=toSubstr($_keys,ins($_keys,'-')+1);
							}
							$_name=$treeConfig->getItem($_name);
							if(len($_module)>0){
								$_modulec=$_module.UaRuleExtend::CMODULE;
								$_names='pre.'.$_module.':names';
							}
							$_names=$treeConfig->getItem($_names);
							switch($_key){
								case 'handle':
									$_titles=$this->getLangs('titles.handle');
									$_langs=$_key.'.option.{$key}';
									//debugx($_channel.':'.$_module.'= '.$_value);
									if($_value=='default') $_value=$this->getConfig($_channel.':'.$_module,'list.handle.select.option');
									break;
								default:
									$_titles='{$title}';
									$_langs=$_key.'.{$key}';
									if($_key=='model'){
										$_names=$this->getLang($_channel.':'.$_module,'title.name');
									}
									break;
							}
							$_ary=toSplit($_value,',');
							$an=count($_ary);$n=0;
							foreach($_ary as $_val){
								$_value=$_modulec.$_key.'.'.$_val;		//$_keys
								if($treeName->isItem($_value)){
									$_title=$treeName->getItem($_value);
								}
								else{
									if($_key=='action' && inp('list',$_val)>0){
										$_title=$this->getTitles('page','name='.$_names.';title='.$this->getLangs(rd($_langs,'key',$_val)));
									}
									else{
										$_title=$this->getTitles($_key,'name='.$_names.';title='.$this->getLangs(rd($_langs,'key',$_val)));
									}
									//debugx($_key.' '.$_title);
								}
								//debugx($_key.': '.$_value.'='.$_title);
								$_atts.=';'.$_value.'='.$_title;
								$n++;
								if($an>$n && $n%6==0) $_atts.='<br/>';
							}
						}
					}
					$treeControl->doMove();
				}
				if(len($_atts)>1) $_atts=toSubstr($_atts,2);
				//debug $_channel.' '.$_atts.'<br>'
				$treeForm=newTree();
				$treeForm->addItem('type','checkbox.popedom_'.$_channel);
				$treeForm->addItem('property','item=;action=;type=string;max=;min=');
				$treeForm->addItem('style','');
				$treeForm->addItem('caption',$this->getTitles('popedom','name='.$_name));
				$treeForm->addItem('att',$_atts);
				$treeForm->addItem('value','');
				$treeForm->addItem('explain','');
				$tableForm->addItem($treeForm);
				//debugx($_atts);
				//if($_channel=='account') debugx($_atts);
			}
		}
		/*
		$treeForm=newTree();
		$treeForm->addItem('type','checkbox.popedom_default');
		$treeForm->addItem('property','item=;action=;type=string;max=;min=');
		$treeForm->addItem('style','');
		$treeForm->addItem('caption',$this->getTitles('popedom','name=默认'));
		$treeForm->addItem('att','action.action.password=修改密码;');
		$treeForm->addItem('value','');
		$treeForm->addItem('explain','');
		$tableForm->addItem($treeForm);
		*/
		return VDCSXCMLExtend::getTable2FormXCML($tableForm);
	}
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		$this->addBoxVar('url.popedoms',$this->getURL('action=popedoms&id=[item:id]'));
	  	$this->doBoxParse();
	}
	
}
?>