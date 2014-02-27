<?
trait PortalManagerRefPopedom
{
	
	
	protected function refPopedomLoad()
	{
		if(!$this->isChecked('lock')) return false;
		$id=$this->id;
		$this->addDTML('x.params','id='.$this->id);
		$this->sqlQuery=$this->FieldID.'='.$id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->sqlQuery,'',1);
		//$sql='select * from '.$this->TableName.' where '.$this->sqlQuery.' limit 0,1';
		$this->treeRS=DB::queryTree($sql);
		if($this->treeRS->getCount()<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			$this->setStatus('nodata');
			return false;
		}
		//##########
		$this->treeRS->addItem('grade.name',PagesCommon::getDict('file:sort=manage.config/data.manager.grade',$this->treeRS->getItem($this->TablePX.'grade')));
		
		$_popedoms='';
		/*
		$roles=$this->treeRS->getItem('roles');
		if($roles){
			$manTable=newTable();
			$sqls='select popedoms from dbe_role where id in('.$roles.')';
			$manTable=DB::queryTable($sqls);
			$_popedoms.=$manTable->getValues('popedoms');
		}
		*/
		$_popedoms.=$this->treeRS->getItem($this->TablePX.'popedoms');
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
		$this->refFormLoad();
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
		$this->treeData->addItem($this->TablePX.'popedoms',$_popedoms);
		DB::execUpdatex($this->TableName,$this->TablePX.'popedoms',$this->treeData,$this->sqlQuery,$this->treeRS);

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