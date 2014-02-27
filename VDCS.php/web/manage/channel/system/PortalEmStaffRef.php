<?
trait PortalEmStaffRef
{
	protected $UURC='staff';
	protected $MinID=10000;

	protected function refLoad()
	{
		$this->deptid=queryi('deptid');
		$this->addDTML('x.params','deptid='.$this->deptid);

		// pivotal
		$this->mpivotal=new UcPivotalManage();
		$this->mpivotal->setURC($this->UURC);
		$this->mpivotal->init();
	}
	public function refThemeCache()
	{
		//$this->theme->doCacheFilterLoop('attrtype','mpo.attrm.tableType');		//attrtype
	}
	
	
	//####################
	protected function refAddLoad()
	{
		//$this->FormFile='add';//使用同一个模板form.add.xcml
		if(!$this->isChecked('lock')) return false;
		$this->pages->addFormVar('isman',1);
		$this->loadPagesForm();
		return true;
	}
	
	protected function refEditLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return false;
		}
		$this->isInfo=false;
		$sql=DB::sqlSelect($this->getConfig('info:table.name'),'','*',$this->sqlQuery,'',1);
		$treeInfo=DB::queryTree($sql);
		if($treeInfo->getCount()>0){
			$this->treeRS->doAppendTree($treeInfo);
			$this->isInfo=true;
		}
		unset($treeInfo);
		$this->isEmployee=false;
		
		$this->treeEmployee=EcEmployee::getTree($this->id);
		if($this->treeEmployee->getCount()>0){
			$this->treeRS->doAppendTree($this->treeEmployee,'emp_');
			$this->isEmployee=true;
		}
		$this->pages->setFormTree($this->treeRS);
		$ary=DB::queryAry('select id,name,password from dbs_manager where uuid='.DB::q($this->id,1));
		$this->maid=$ary['id'];
		if(!$ary['name']) $ary['name']='暂无关联';
		$this->pages->addFormVar('maid',$this->maid);
		$isman=0;
		if($this->maid) $isman=1;
		$this->pages->addFormVar('isman',$isman);
		$this->pages->addFormVar('maname',$ary['name']);
		$this->pages->addFormVar('manpass',$ary['password']);
		
						
		$this->loadPagesForm();	
		return true;
	}
	
	protected function doFormCheck($action='')
	{
		$isukey=false;
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_id=$this->treeData->getItemInt($this->FieldID);
			if($_id<1) $_id=$this->id;
			if($action=='add' && $_id>0){
				$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'='.$_id.'';
				if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.id'));
			}
			$_no=$this->treeData->getItem($this->TablePX.'no');
			if(len($_no)>0){
				$isukey=true;
				if(!utilCheck::isName($_no)){ $this->addError($this->getLang('error.norule.no')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'no=\''.$_no.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.no'));
				}
			}
			$_name=$this->treeData->getItem($this->TablePX.'name');
			if(len($_name)>0){
				$isukey=true;
				if(!utilCheck::isName($_name)){ $this->addError($this->getLang('error.norule.name')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'name=\''.$_name.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.name'));
				}
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_email=$this->treeData->getItem($this->TablePX.'email');
			if(len($_email)>0){
				$isukey=true;
				if(!utilCheck::isEmail($_email)){ $this->addError($this->getLang('error.norule.email')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'email=\''.$_email.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.email'));
				}
			}
			$_mobile=$this->treeData->getItem($this->TablePX.'mobile');
			if(len($_mobile)>0){
				$isukey=true;
				if(!utilCheck::isMobile($_mobile)){ $this->addError($this->getLang('error.norule.mobile')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'mobile=\''.$_mobile.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.mobile'));
				}
			}
			$_idcard=$this->treeData->getItem($this->TablePX.'idcard');
			if(len($_idcard)>0){
				$isukey=true;
				if(!utilCheck::isIDCard($_idcard)){ $this->addError($this->getLang('error.norule.idcard')); }
				else{
					$sql='select count(*) from '.$this->TableName.' where '.$this->FieldID.'<>'.$this->id.' and '.$this->TablePX.'idcard=\''.$_idcard.'\'';
					if(DB::queryInt($sql)>0) $this->addError($this->getLang('error.exist.idcard'));
				}
			}
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext && !$isukey){
			$this->addError($this->getLang('error.no.ukey'));
		}

		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_names=$this->treeData->getItem($this->TablePX.'names');
			if(len($_names)>0){
				if(!utilCheck::isName($_names)) $this->addError($this->getLang('error.norule.names'));
			}
			$_shortname=$this->treeData->getItem($this->TablePX.'shortname');
			if(len($_shortname)>0){
				if(!utilCheck::isName($_shortname)) $this->addError($this->getLang('error.norule.shortname'));
			}
			$_realname=$this->treeData->getItem($this->TablePX.'realname');
			if(len($_realname)>0){
				if(!utilCheck::isName($_realname)) $this->addError($this->getLang('error.norule.realname'));
			}
		}
		
		//########## pivotal
		if($this->mpivotal->is()){
			$this->mpivotal->setUID($this->id);
			$this->mpivotal->doFormCheck();
		}
		//##########
	}
	
	
	protected function refPopedomLoad($type='')
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->addDTML('x.params','id='.$this->id);
		$this->sqlQuery='id='.$id;
		$sql=DB::sqlSelect('dbs_manager','','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$this->sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			$this->setStatus('nodata');
			return false;
		}
		//##########
		$this->treeRS->addItem('grade.name',PagesCommon::getDict('file:sort=manage.config/data.manager.grade',$this->treeRS->getItem('grade')));
		
		$_popedoms='';
		if($type=='view'){
			$roles=$this->treeRS->getItem('roles');
			if($roles){
				$manTable=newTable();
				$sqls='select popedoms from dbe_role where id in('.$roles.')';
				$manTable=DB::queryTable($sqls);
				$_popedoms.=$manTable->getValues('popedoms');
			}
		}
		$_popedoms.=$this->treeRS->getItem('popedoms');
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
		//$this->refFormLoad();
		return true;
	}

	protected function refPopedomParse()
	{
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
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
		$this->treeData->addItem('popedoms',$_popedoms);
		DB::execUpdatex('dbs_manager','popedoms',$this->treeData,$this->sqlQuery,$this->treeRS);

	}
	
	public function getPopedomFormXCML()
	{
		$tableChannel=VDCSDTML::getConfigTable('common.config/channel');
		$tableForm=VDCSXCML::newFormTable();
		//----------
		$treeForm=newTree();
		$treeForm->addItem('type','put.name');
		$treeForm->addItem('property','item=;action=;type=string;max=;min=');
		$treeForm->addItem('style','');
		$treeForm->addItem('caption','管理员名称');
		$treeForm->addItem('att',$_atts);
		$treeForm->addItem('value','');
		$treeForm->addItem('explain','');
		$tableForm->addItem($treeForm);
		//----------
		$treeForm=newTree();
		$treeForm->addItem('type','put.grade.name');
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
				//$treeConfig=VDCSDTML::getConfigTree('common.channel/'.$_channel.'/config');
				$treeConfig=CommonChannelExtend::getTree($_channel,'config');
				//if($_channel=='account') debugTree($treeConfig);
				$treeName=utilString::toTree(utilString::toFilterBlank($treeControl->getItem('popedom.title')),';','=');
				//debugTree($treeConfig);
				$treeControl->doBegin();
				for($t=1;$t<=$cLen;$t++){
					$_key=$treeControl->getItemKey();
					$_value=$treeControl->getItemValue();
					//debugx($_key.': '.$_value);
					if(left($_key,15)=='popedom.module.'){
						$_module='';$_modulec='';$_moduleac='';
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
							if(len($_module)>0){
								$_modulec=$_module.':';
								$_moduleac=$_module.UaRulerExtend::CMODULE;
							}
							$_name=$treeConfig->getItem('pre.'.$_modulec.'name');
							if(!$_name) $_name=$treeName->getItem($_moduleac.'name');
							if(!$_name) $_name='['.$_modulec.'name]';
							$_names=$treeConfig->getItem('pre.'.$_modulec.'names');
							if(!$_names) $_names=$treeName->getItem($_moduleac.'names');
							if(!$_names) $_names='['.$_modulec.'names]';
							switch($_key){
								case 'handle':
									$_titles=$this->getLangs('titles.handle');
									$_langs=$_key.'.option.{$key}';
									//debugx($_channel.':'.$_module.'= '.$_value);
									if($_value=='default') $_value=$this->getConfig($_channel.':'.$_module,'list.handle.select.option');
									//debugx($_channel.'#'.$_module.': '.$_value);
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
								$_val=trim($_val);
								if(!$_val) continue;
								$_value=$_moduleac.$_key.'.'.$_val;		//$_keys
								if(ins($_val,'=')>0){
									$_value=$_moduleac.$_key.'.'.toSubstr($_val,1,ins($_val,'=')-1);
								}
								if($treeName->isItem($_value)){
									$_title=$treeName->getItem($_value);
								}
								else{
									if($_key=='action' && inp('list',$_val)>0){
										$_title=$this->getTitles('page','name='.$_names.';title='.$this->getLangs(rd($_langs,'key',$_val)));
									}
									else{
										/*
										if($_channel=='account'){
											debugx($this->getLangs(rd($_langs,'key',$_val)));
											debugx($_module.'-'.$_val.'-'.$this->getLangs(rd($_langs,'key',$_val)));
										}
										*/
										$_title=$this->getTitles($_key,'name='.$_names.';title='.$this->getLangs(rd($_langs,'key',$_val)));
									}
									//debugx($_key.' '.$_title);
								}
								//if($_channel=='account') debugx($_key.': '.$_value.'='.$_title);
								$_atts.=';'.$_value.'='.$_title;
								$n++;
								//if($an>$n && $n%6==0) $_atts.='<br/>';
							}
						}
					}
					$treeControl->doMove();
				}
				if(len($_atts)>1) $_atts=toSubstr($_atts,2);
				//debugx($_channel.'#'.$_atts);
				$treeForm=newTree();
				$treeForm->addItem('type','checkbox.popedom_'.$_channel);
				$treeForm->addItem('property','item=;action=;type=string;max=;min=;ui.type=');
				$treeForm->addItem('style','');
				$treeForm->addItem('caption',$this->getTitles('popedom','name='.$treeConfig->getItem('pre.name')));
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
	

}
?>