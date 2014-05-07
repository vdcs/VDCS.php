<?
define('FILE_TEMPLET_FORM',			'form.{$channel}');
define('FILE_TEMPLET_FORMS',			'form.{$channel}.{$file}');
define('FILENAME_FORM',				'form.xcml');
define('FILENAME_FORMS',			'form.{$file}.xcml');
define('FILENAME_CONFIGURE',			'configure.xcml');
define('FILENAME_CONFIGURES',			'configure.{$module}.xcml');
define('FILENAME_MODULE_FORM',			'form.{$module}.xcml');
define('FILENAME_MODULE_FORMS',			'form.{$module}.{$file}.xcml');

class PagesForm
{
	public $_var=array();
	Public $eForm,$xcmlForm;
	Private $_PathTemplate,$_FilePages,$_FileForm,$_FileName,$_FileForms,$_FileNames;
	public $tree_formVar,$tree_formPre,$tree_formData,$tree_formDatas,$tree_formRS;
	Private $_FormFile,$_FormFiles,$_FormXml,$_FormXmls,$_FormChannel,$_FormTitle,$_FormName,$_FormAction,$_FormUrl,$_FormNames,$_FormActions;
	//Private $_isConvertFlag;
	Private $_form_isframe,$_form_ispost,$_form_isposts,$_form_isoptionselect;
	Private $_xml_repet;
	Private $_debug,$_debug_filename;
	Private $SP_CODE_isMode,$SP_CODE_isSwitch,$SP_CODE_configure,$SP_CODE_Tree,$SP_CODE_code_name,$SP_CODE_code_value,$SP_CODE_code_values,$SP_CODE_remark_name,$SP_CODE_remark_type;
	Private $DEFAULT_FormNAME,$DEFAULT_FORM_OPTION_SELECT_PREFIX,$DEFAULT_FORM_OPTION_SELECT_VALUE;
	Private $TYPE_TEXTAREA,$TYPE_NODATAVALUE;
	
	const ID_VALUE_ITEM		= '_pages_form_{$name}';
	
	public function __construct()
	{
		$this->eForm=new utilError();
		$this->xcmlForm=new utilXCML();
		
		$this->tree_formVar=new utilTree();
		$this->tree_formPre=new utilTree();
		$this->tree_formVar=new utilTree();
		$this->tree_formData=new utilTree();
		$this->tree_formDatas=new utilTree();
		$this->tree_formRS=new utilTree();
		
		$this->_form_frame='normal';
		//$this->_isConvertFlag=false;
		
		$this->_FileName='form';
		$this->_FileNames='form.{$file}';
		$this->_FileAtSymbol = '@';
		$this->_FileAtDefault = 'default';
		
		$this->_form_isframe=true;
		$this->_form_ispost=false;
		$this->_form_isposts=false;
		$this->_form_isoptionselect=false;
		
		$this->_xml_repet=3;
		
		$this->SP_CODE_isMode=false;
		$this->SP_CODE_isSwitch=false;
		$this->SP_CODE_code_value=-1;
		$this->SP_CODE_code_values=-1;
		
		$this->DEFAULT_FormNAME='frm_post';
		$this->DEFAULT_FORM_OPTION_SELECT_PREFIX='__is__';
		$this->DEFAULT_FORM_OPTION_SELECT_VALUE='1';
		$this->TYPE_TEXTAREA='textarea,textbox,editor,editor_ubb,editor_html';
		$this->TYPE_NODATAVALUE='put,bar,multibar,upload';
	}
	
	public function __destruct()
	{
		unset($this->_var);
		unset($this->eForm,$this->xcmlForm);
		unset($this->tree_formVar,$this->tree_formPre,$this->tree_formVar);
		unset($this->tree_formData,$this->tree_formDatas,$this->tree_formRS);
		unset($this->_FormXml,$this->_FormXmls);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getDebug() { return $this->_debug; }
	public function setDebugFilename($s) { $this->_debug_filename=$s; }
	
	public function setPathForm($p1,$p2='',$p3=''){$this->addPathForm('p1',$p1);$this->addPathForm('p2',$p2);$this->addPathForm('p2',$p3);}
	public function getPathForm($n='p1') { return $this->_var['PathPorm'][$n]; }
	public function addPathForm($k,$v){if($v) $this->_var['PathPorm'][$k]=$v;}
	
	public function setPathTemplate($s) { $this->_PathTemplate=$s; }
	public function getPathTemplate() { return $this->_PathTemplate; }
	
	public function setFilePages($s) { $this->_FilePages=$s; }
	public function getFilePages() { return $this->_FilePages; }
	
	public function setFileForm($s,$sname='') { $this->_FileForm=$s; $this->setFileName($sname); }
	public function getFileForm() { return $this->_FileForm; }
	
	public function setFileName($s) { if(len($s)>0) $this->_FileName=$s; }
	public function getFileName() { return $this->_FileName; }
	
	public function setFileForms($s) { $this->_FileForms=$s; }
	public function getFileForms() { return $this->_FileForms; }
	
	public function setFileAt($at0,$at1,$at2='')
	{
		if($at0) $this->_var['FileAt0']=$at0;
		if($at1) $this->_var['FileAt1']=$at1;
		if($at2) $this->_var['FileAt2']=$at2;
	}
	public function getFileAt($type_=''){return $this->_var['FileAt'.$type_];}
	
	public function getFormXCML() { return $this->xcmlForm; }
	public function doFormDestroy() { unset($this->xcmlForm); }
	
	public function doFormInit()
	{
		/*
		if(!oFunc.oPages.isInit){
			$this->oPages->setPathTemplate ($this->_PathTemplate);
			$this->oPages->setFilePages ($this->_FilePages);
			func.doInit
		}
		*/
	}
	
	
	/*
	########################################
	########################################
	*/
	//public function setConvertFlag($s) { $this->_isConvertFlag=$s; }
	//public function isConvertFlag() { return $this->_isConvertFlag; }
	
	public function setFormFrame($s) { $this->_form_frame=$s; if($s=='none') $this->_form_isframe=false; }
	public function getFormFrame() { return $this->_form_frame; }
	
	public function setFormChannel($s) { $this->_FormChannel=trim($s); }
	public function getFormChannel() { return $this->_FormChannel; }
	
	public function setFormModule($s) { $this->_FormModule=trim($s); }
	public function getFormModule() { return $this->_FormModule; }
	
	public function setFormFile($s,$s2=null) { if($s!=null) $this->_FormFile=trim($s); if($s2!=null) $this->_FormSubFile=trim($s2); }
	public function getFormFile($t=null){
		switch($t){
			case 'sub':	$re=$this->_FormSubFile;break;
			case 'full':
				$rea=array();
				if($this->_FormModule) array_push($rea,$this->_FormModule);
				if($this->_FormFile) array_push($rea,$this->_FormFile);
				if($this->_FormSubFile) array_push($rea,$this->_FormSubFile);
				$re=implode('.',$rea);
				//debugx($re);
				/*
				$re=$this->_FormModule;
				if($this->_FormFile){
					if($re){
						$re.='.'.$this->_FormFile;
					}
					else{
						$re=$this->_FormFile;
					}
				}
				if($this->_FormSubFile) $re.='.'.$this->_FormSubFile;
				*/
				break;
			default:	$re=$this->_FormFile;break;
		}
		return $re;
	}
	
	public function setFormFiles($strer) { $this->_FormFiles=$strer; }
	public function getFormFiles() { return $this->_FormFiles; }
	
	public function setFormXML($strer) { $this->_FormXml=$strer; }
	public function getFormXML() { return $this->_FormXml; }
	
	public function setFormXMLS($strer) { $this->_FormXmls=$strer; }
	public function getFormXMLS() { return $this->_FormXmls; }
	
	public function setFormError($o) { $this->eForm=$o; }
	public function getFormError() { return $this->eForm; }
	
	
	public function isFormFrame() { return $this->_form_isframe; }
	
	public function isFormPost() { return $this->_form_ispost; }
	public function isFormPosts() { return $this->_form_isposts; }
	
	public function setFormTitle($strer) { $this->_FormTitle=$strer; }
	public function getFormTitle() { return $this->_FormTitle; }
	
	public function setFormName($strer) { $this->_FormName=$strer; }
	public function getFormName() { return $this->_FormName; }
	public function getFormNames() { return $this->_FormNames; }
	
	public function setFormAction($strer) { $this->_FormAction=$strer; }
	public function getFormAction() { return $this->_FormAction; }
	
	public function setFormURL($strer) { $this->_FormUrl=$strer; }
	public function getFormURL() { return $this->_FormUrl; }
	
	public function addFormVar($strKey,$strValue) { $this->tree_formVar->addItem($strKey,$strValue); }
	public function getFormVar($strKey) { return $this->tree_formVar->getItem($strKey); }
	
	public function addFormPre($strKey,$strValue) { $this->tree_formPre->addItem($strKey,$strValue); }
	public function getFormPre($strKey) { return $this->tree_formPre->getItem($strKey); }
	public function addFormXCML($strKey,$strValue) { $this->tree_formPre->addItem('xcml:'.$strKey,$strValue); }
	
	public function addFormData($strKey,$strValue) { $this->tree_formData->addItem($strKey,$strValue); }
	public function addFormDatas($strKey,$strValue) { $this->tree_formDatas->addItem($strKey,$strValue); }
	
	public function doFormDataAdd($strKey,$strValue,$strValues) { $this->tree_formData->addItem($strKey,$strValue); $this->tree_formDatas->addItem($strKey,$strValues); }
	
	public function isFormData($strKey) { $this->tree_formData->isItem($strKey); }
	public function getFormData($strKey) { return $this->tree_formData->getItem($strKey); }
	public function getFormDataTree() { return $this->tree_formData; }
	public function getFormDatasTree() { return $this->tree_formDatas; }
	
	public function isFormSelect($strKey)
	{
		$re=false;
		if($this->tree_formDatas->getItem($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$this->strKey)==$this->DEFAULT_FORM_OPTION_SELECT_VALUE) $re=true;
		return $re;
	}
	
	public function setFormTree($strTree) { if(isTree($strTree)) $this->tree_formRS=$strTree; }
	public function addFormTreeItem($strKey,$strValue) { $this->tree_formRS->addItem($strKey,$strValue); }
	public function getFormTreeItem($strKey) { return $this->tree_formRS->getItem($strKey); }
	
	public function setFormRS($o)
	{
		if(isTree($o)) $this->tree_formRS=$o;
		//ifTypeName(strer)=commons.DB_RECORDSET_TYPENAME Then Set tree_formRS=utilDBResultSet.getTree(strer)
	}
	
	public function addFormRSValue($strKey,$strValue) { $this->tree_formRS->addItem($strKey,$strValue); }
	public function getFormRSValue($strKey) { return $this->tree_formRS->getItem($strKey); }
	
	
	public function doPathReplace($channel)
	{
		foreach($this->_var['PathPorm'] as $k=>$v){
			$this->_var['PathPorm'][$k]=rd($v,'channel',$channel);
		}
	}
	
	public function getIncludeContentTree()
	{
		if(!$this->treeIncludeContent){
			$reTree=newTree();
			$reTree->doAppend(utilFat::getFileTree('vdcs.mconfig/form/@part.dat'));
			$reTree->doAppend(utilFat::getFileTree('vdcs.mconfig/form/@part.m.dat'));
			$reTree->doAppend(utilFat::getFileTree('vdcs.mconfig/form/@part.ua.dat'));
			if(!VDCS_MANAGE_PATH){
				$reTree->doAppend(utilFat::getFileTree('vdcs.web/config/form/@part.dat'));
				$reTree->doAppend(utilFat::getFileTree('vdcs.web/config/form/@part.m.dat'));
				$reTree->doAppend(utilFat::getFileTree('vdcs.web/config/form/@part.ua.dat'));
			}
			$this->treeIncludeContent=$reTree;
		}
		return $this->treeIncludeContent;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadForm($t=1)
	{
		$this->_form_ispost=isPost();
		$this->_form_isposts=$this->_form_ispost;
		if(!$this->_FormXml){
			$_FileFull=$this->getFormFile('full');	//$this->_FormFile;
			$tmpFile=$this->_FormFiles;$tmpFileName=$this->_FileNames;
			if(!$tmpFile){
				//debugx($this->_FileForm);
				if(len($this->_FileForm)>0 || len($this->_FileForms)>0){
					if(len($_FileFull)>0){
						$tmpFile=$this->_FileForms;
						$tmpFileName=$this->_FileNames;
					}
					else{
						$tmpFile=$this->_FileForm;
						$tmpFileName=$this->_FileName;
					}
					$tmpFile=rd($tmpFile,'channel',$this->_FormChannel);
					$tmpFile=rd($tmpFile,'file',$_FileFull);
					$tmpFileName=rd($tmpFileName,'file',$_FileFull);
					//$this->_PathPorm=rd($this->_PathPorm,'channel',$this->_FormChannel);
					$this->doPathReplace($this->_FormChannel);
					if(!isFile($tmpFile)){
						$tmpFile=VDCSConfig::toPathsReal($this->_var['PathPorm'],$tmpFileName,'',0);
					}
				}
				else{
					if(!$_FileFull) return;
					$tmpPathForm=$this->_var['PathPorm']['p1'];
					if(!$tmpPathForm) $tmpPathForm=appDirPath('common.config/form/');
					$tmpFile=$tmpPathForm.$_FileFull;
					$tmpFileName=rd($tmpFileName,'file',$_FileFull);
					if(!isFile($tmpFile)){
						$tmpFile=VDCSConfig::toPathsReal($this->_var['PathPorm'],$tmpFileName,'',0);
					}
				}
			}
			$basepath=$this->_var['PathPorm']['p1'];
			for($a=1;$a<3;$a++){
				$_value=$this->_var['FileAt'.$a];
				if(len($_value)>0){
					$tmpVarAt='@'.$_value;
					$tmpFileAt=$basepath.r($tmpFile,EXT_XCML,'@'.$_value.EXT_XCML);
					if(isFile($tmpFileAt)) $tmpFile=$tmpFileAt;
				}
			}
			if(!isFile($tmpFile)){
				$filename=$tmpFileName;
				$tmpFile=VDCSConfig::toPathsReal($this->_var['PathPorm'],$filename,'',0);
			}
			$this->_debug.=NEWLINE.'<!-- file=' . $tmpFile . ' -->';
			$this->_FormXml=utilFile::getConfig($tmpFile);
			if(len($this->_FormXml)<100){
				//form.data@{@@at.defaulted}
				$tmpFilePath=$tmpFile;
				$tmpBasePath = toSubstr($tmpFilePath, 1, Len($tmpFilePath) - Len(tmpFileName) - Len(EXT_XCML));
				$tmpPath = $tmpBasePath.$tmpFileName.$this->_FileAtSymbol.$this->_var['FileAt0'].EXT_XCML;
				if(isFile($tmpPath)){
					$tmpPath = $tmpBasePath.$tmpFileName.$this->_FileAtSymbol.$this->_FileAtDefault.EXT_XCML;
					//debugx($tmpPath);
					$this->_debug.=NEWLINE.'<!-- file='.$tmpPath.' -->';
					$this->_FormXml=utilFile::getConfig($tmpPath);
				}
			}
		}
		//debugAry($this->_var);
		
		if(len($this->_FormXml)<1) return;
		$this->_FormXmls=$this->_FormXml;
		
		//进行预变量、变量、系统及文件包含处理
		$this->_FormXmls=utilRegex::toReplacePre($this->_FormXmls,$this->tree_formPre);
		for($i=0;$i<$this->_xml_repet;$i++){
			$this->_FormXmls=CommonTheme::toParseIncludeTree($this->_FormXmls,$this->getIncludeContentTree());
			$this->_FormXmls=CommonTheme::toParseInclude($this->_FormXmls,$this->_var['PathPorm'],false);
			$this->_FormXmls=utilRegex::toReplacePre($this->_FormXmls,$this->tree_formPre);
		}
		$this->_FormXmls=CommonTheme::toParseIncludeTree($this->_FormXmls,$this->getIncludeContentTree());
		$this->_FormXmls=CommonTheme::toParseInclude($this->_FormXmls,$this->_var['PathPorm'],true);
		if(query('debug')=='form.include.tree') debugTree($this->getIncludeContentTree());
		if(query('debug')=='form.xml') debugvc($this->_FormXmls);
		$this->_FormXmls=utilRegex::toReplacePre($this->_FormXmls,$this->tree_formPre);
		$this->_FormXmls=utilRegex::toReplaceVar($this->_FormXmls,$this->tree_formVar);
		$this->_FormXmls=VDCSDTML::toParseApp($this->_FormXmls);
		$this->_FormXmls=VDCSDTML::toParseDCS($this->_FormXmls);
		$this->_FormXmls=CommonTheme::toParseControl($this->_FormXmls);
		//if($this->_isConvertFlag) CommonTheme::toFlagDecode($this->_FormXmls);
		//debugTree($this->tree_formPre);
		//debugvc($this->_FormXmls);
		
		if($t==1) $this->loadFormParse();
	}
	
	public function loadFormParse()
	{
		//将处理后的XCML写入调试文件名中
		if(len($this->_debug_filename)>0) utilFile::doFileCreate($this->_debug_filename,$this->_FormXmls);
		
		//debugx($this->_FormXmls);
		$this->xcmlForm=new utilXCML();
		$this->xcmlForm->loadXML($this->_FormXmls);
		$this->xcmlForm->doParse();
		$this->xcmlForm->doParseItem();
		
		//########## sp_code ##########
		$this->SP_CODE_configure=$this->xcmlForm->getConfigure('sp.code');
		if(len($this->SP_CODE_configure)>0){
			$this->SP_CODE_isMode=true;
			$this->SP_CODE_Tree=utilString::toTree($SP_CODE_configure,';','=');
			$this->SP_CODE_code_name=$this->SP_CODE_Tree->getItem('code');
			$this->SP_CODE_remark_name=$this->SP_CODE_Tree->getItem('remark');
			
			if($this->_form_ispost){
				$tmpSPCode=post('_sp_code');
				if(inp('0,1,2',$tmpSPCode,',')>0){
					$this->SP_CODE_code_value=toInt($tmpSPCode);
					$this->SP_CODE_isSwitch=true;
					$this->SP_CODE_remark_type=$this->toSPCodeElementType($this->SP_CODE_code_value);
					$this->_form_ispost=false;
				}
			}
		}
		//debugx($this->SP_CODE_configure);
		//########## sp_code ##########
		
		$this->_debug.=NEWLINE.'<!-- getErrorType='.$this->xcmlForm->getErrorType().' -->';
		$this->_debug.=NEWLINE.'<!-- xml.length='.len($this->_FormXmls).' -->';
		$this->_debug.=NEWLINE.'<!-- getItemlength='.$this->xcmlForm->getItemLength().' -->';
		$this->_debug.=NEWLINE.'<!-- action='.$this->_FormAction.' -->';
		$this->loadFormPost();
	}
	
	public function toSPCodeElementType($c)
	{
		$re='';
		switch($c){
			case 0: $re='textbox'; break;
			case 1: $re='editor_ubb'; break;
			case 2: $re='editor_html'; break;
		}
		return $re;
	}
	
	public function loadFormPost()
	{
		if(!isXCML($this->xcmlForm)) return;
		if(!$this->_form_isposts) return;
		$treeNode=new utilTree();
		$treeItemProperty=new utilTree();
		$tmpMin=$tmpMax=0;
		$isCut=$isRule=false;
		$tmpNodeType=$tmp_type=$tmp_name='';
		$tmpItemPropertyType=$tmpAction=$tmpCaption=$tmpError=$tmpData=$tmpDatas='';
		$this->xcmlForm->doItemBegin();
		for($i=0;$i<$this->xcmlForm->getItemlength();$i++){
			$treeNode=$this->xcmlForm->getItemTree();
			$tmpNodeType=$treeNode->getItem('type');
			if(ins($tmpNodeType,'.')<1){
				$tmp_type='';
				$tmp_name=$tmpNodeType;
			}
			else{
				$tmp_type=toLower(left($tmpNodeType,ins($tmpNodeType,'.')-1));
				$tmp_name=right($tmpNodeType,len($tmpNodeType) - ins($tmpNodeType,'.'));
			}
			$treeItemProperty=utilString::toTree($treeNode->getItem('property'),';','=');
			$tmpItemPropertyType=$treeItemProperty->getItem('type');
			$tmpAction=$treeItemProperty->getItem('action');
			if((len($this->_FormAction)<1 || len($tmpAction)<1 || $tmpAction==$this->_FormAction) && inp($this->TYPE_NODATAVALUE,$tmp_type)<1){
				if($tmp_type=='checkbox' || $treeItemProperty->getItem('multi')=='yes'){
					switch($tmpItemPropertyType){
						case 'id': $tmpData=utilCode::toValues(posts($tmp_name),1,'string'); break;
						case 'int': $tmpData=utilCode::toValues(posts($tmp_name),1,'string'); break;
						case 'num':
						case 'money':
						case 'price': $tmpData=utilCode::toValues(posts($tmp_name),2,'string'); break;
						default: $tmpData=utilCode::toValues(posts($tmp_name),0,'string'); break;
					}
				}
				else{
					switch($tmpItemPropertyType){
						case 'id': $tmpData=utilCode::toValues(posts($tmp_name),1,'string'); break;
						case 'int': $tmpData=posti($tmp_name); break;
						case 'num': $tmpData=postn($tmp_name); break;
						case 'money':
						case 'price': $tmpData=utilCode::toPrice(postn($tmp_name)); break;
						default:
							if(inp($this->TYPE_TEXTAREA,$tmp_type,',')>0) $tmpData=post($tmp_name);	//??? postcontent($tmp_name,1)
							else $tmpData=post($tmp_name);
							break;
					}
				}
				
				if($tmpItemPropertyType=='time' && !isNum($tmpData)) { $tmpDatas=VDCSTime::toNumber($tmpData); }
				else { $tmpDatas=$tmpData; }
				//##########
				$isRule=false;
				$isCut=false;
				switch($tmpItemPropertyType){
					case 'time':
						$tmpMin=$treeItemProperty->getItemInt('min');
						if($tmpMin>0 && ($tmpDatas)<1) $isRule=true;
						break;
					case 'date':
						$tmpMin=$treeItemProperty->getItemInt('min');
						if($tmpMin>0 && !isDate($tmpDatas)) $isRule=true;
						break;
					case 'int':
						$tmpMin=$treeItemProperty->getItemInt('min');
						if($tmpMin>0 && toInt($tmpData)<$tmpMin) $isRule=true;
						break;
					case 'num':
					case 'money':
					case 'price':
						$tmpMin=$treeItemProperty->getItemNum('min');
						if($tmpMin>0 && toNum($tmpData)<$tmpMin) $isRule=true;
						//ifcdbl(tmpdata)<cdbl($treeItemProperty->getItemNum('min')) then isrule=true
						break;
					default:
						$tmpMin=$treeItemProperty->getItemInt('min');
						if($tmpMin>0 && len($tmpData)<$tmpMin) $isRule=true;
						$isCut=true;
						break;
				}
				$tmpMax=$treeItemProperty->getItemInt('max');
				if($isCut){
					if($tmpMax>0) $tmpData=utilCode::toCut($tmpData,$tmpMax);
				}
				else{
					if($tmpMax>0 && len($tmpData)>$tmpMax) $isRule=true;
				}
				//##########
				
				$this->doFormDataAdd($tmp_name,$tmpData,$tmpDatas);
				if($treeItemProperty->getItem('option')=='select'){
					$this->doFormDataAdd($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name,getPost($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name),getPost($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name));
				}
				//debugx($tmp_name);
				//##########
				if($isRule){
					$tmpCaption=$treeNode->getItem('caption');
					if(ins($tmpCaption,'$$$')>0) $tmpCaption=left($tmpCaption,ins($tmpCaption,'$$$') - 1);
					$tmpError=PagesCommon::getTemplatForm('error.item');
					$tmpError=rd($tmpError,'caption',$tmpCaption);
					$this->eForm->addItem($tmpError,$tmp_name,'rule');
				}
			}
			$this->xcmlForm->doItemMove();
		}
		unset($treeNode);unset($treeItemProperty);
	}
	
	public function getFormParse()
	{
		$tmpFormItem=$tmpstr='';
		$treeNode=new utilTree();
		$treeItemProperty=new utilTree();
		$tmpCaption=$tmpCaptionExplain=$tmpOptionSelect=$tmpNote=$tmpExplain=$tmpInput=$tmpClass=$tmpClewSign='';
		if(!isXCML($this->xcmlForm)) return '';
		$re='';
		$re.=self::toScriptFrame($this->xcmlForm->getConfigure('script.begin'));
		if($this->_form_isframe){
			$tmpFormScript='';
			$this->_FormActions=$this->_FormUrl;
			if(len($this->_FormActions)<1) $this->_FormActions=DCS::browsePath(true);
			$this->_FormNames=$this->_FormName;
			if(len($this->_FormNames)<1) $this->_FormNames=$this->xcmlForm->getConfigure('form/name');
			if(len($this->_FormNames)<1) $this->_FormNames=$this->DEFAULT_FormNAME;
			if($this->_form_frame=='table'){
				$tmpstr=PagesCommon::getTemplatForm('header.table');
			}
			else{
				if(len($this->_FormTitle)>0){
					$tmpstr=PagesCommon::getTemplatForm('header.title');
					$tmpstr=rd($tmpstr,'title',$this->_FormTitle);
				}
				else{
					$tmpstr=PagesCommon::getTemplatForm('header');
				}
			}
			$tmpstr=rd($tmpstr,'form.action',$this->_FormActions);
			$tmpstr=rd($tmpstr,'form.name',$this->_FormNames);
			$tmpstr=rd($tmpstr,'form.script',$this->xcmlForm->getConfigure('form/script'));
			$tmpstr=rd($tmpstr,'upid',post('_upid'));
			$tmpstr=rd($tmpstr,'multibar',post('_multibar'));
			$re.=$tmpstr;
		}
		$isItem=false;
		$treeFormvClassi=utilString::toTree(PagesCommon::getTemplatForm('classi'),';','=');
		$this->xcmlForm->doItemBegin();
		$tmp_Input=$tmp_Value=$tmp_Value_Option=$tmpStyleItem=$tmpStyleInput=$tmpAction='';
		for($i=0;$i<$this->xcmlForm->getItemlength();$i++){
			$tmpFormItem='item';
			$treeNode=$this->xcmlForm->getItemTree();
			//debugx($treeNode->getItem('type'));
			$isItem=true;
			$tmp_Input=$tmp_Value=$tmpStyleItem='';
			$tmpStyleInput=$treeNode->getItem('style');
			if(ins($tmpStyleInput,'$$$')>0){
				$tmpStyleItem=left($tmpStyleInput,ins($tmpStyleInput,'$$$') - 1);
				$tmpStyleInput=right($tmpStyleInput,len($tmpStyleInput) - ins($tmpStyleInput,'$$$'));
			}
			$treeItemProperty=utilString::toTree($treeNode->getItem('property'),';','=');
			$tmpAction=$treeItemProperty->getItem('action');
			if(len($this->_FormAction)<1 || len($tmpAction)<1 || $tmpAction==$this->_FormAction){
				$tmpNodeType='';$tmp_type='';$tmp_name='';
				$tmpNodeType=$treeNode->getItem('type');
				if(ins($tmpNodeType,'.')<1){
					$tmp_type=$tmpNodeType;
					$tmp_name='';
				}
				else{
					$tmp_type=substr($tmpNodeType,0,ins($tmpNodeType,'.') - 1);
					$tmp_name=substr($tmpNodeType,ins($tmpNodeType,'.'));
				}
				if($this->_form_isposts && $tmp_type!='put'){
					if($this->tree_formData->isItem($tmp_name)) $tmp_Value=$this->tree_formData->getItem($tmp_name);
				}
				else if($this->tree_formRS->isItem($tmp_name)) { $tmp_Value=$this->tree_formRS->getItem($tmp_name); }
				else{	//if ins(noDataValue,tmp_type)<1 then
					$tmp_Value=$treeNode->getItem('value');
				}
				//########## sp_code ##########
				if($this->SP_CODE_isMode && $tmp_name){
					switch(toLower($tmp_name)){
						case toLower($this->SP_CODE_code_name):
							if(SP_CODE_code_value>-1) $this->SP_CODE_code_values=$this->SP_CODE_code_value;
							else $this->SP_CODE_code_values=toInt($tmp_Value);
							$tmp_type='radio';
							$tmp_Value=$this->SP_CODE_code_values;
							$this->SP_CODE_remark_type=$this->toSPCodeElementType($this->SP_CODE_code_values);
							$treeNode->setItem('caption',PagesCommon::getTemplatForm('item.code.caption'));
							$treeNode->setItem('value',$this->SP_CODE_code_values);
							$treeNode->setItem('att',PagesCommon::toAtt('file','sp.code'));
							$tmpstr=PagesCommon::getTemplatForm('item.code.explain');
							$tmpstr=rd($tmpstr,'form.name',$this->_FormNames);
							$tmpstr=rd($tmpstr,'form.field.sp.code',$this->SP_CODE_code_name);
							$treeNode->setItem('explain',$tmpstr);
							break;
						case toLower($this->SP_CODE_remark_name):
							if(len($this->SP_CODE_remark_type)>0) $tmp_type=$this->SP_CODE_remark_type;
							if($this->SP_CODE_code_values==0) $tmpStyleInput='cols=60 rows=15';
							if($this->SP_CODE_code_values!=1){
								$treeNode->setItem('caption',r($treeNode->getItem('caption'),PagesCommon::getTemplatForm('title.upload'),''));
							}
							break;
					}
				}
				
				if($treeItemProperty->getItem('type')=='time'){
					if(isInt($tmp_Value)) $tmp_Value=VDCSTime::toString($tmp_Value);
				}
				
				//########## sp_code ##########
				switch($tmp_type){
					case 'bar':
					case 'multibar':
					case 'blank':
						$tmp_Input='';
						$isItem=false;
						break;
					case 'item':
						$tmp_Input='';
						break;
					case 'put':
						$tmpFormItem='item.put';
						$tmp_Input=PagesCommon::getTemplatForm('put');
						$tmp_Input=rd($tmp_Input,'text',$tmp_Value);
						if(len($treeItemProperty->getItem('class'))<1) $treeItemProperty->addItem('class','put');
						break;
					//case 'hide':
					case 'hidden':
						//isitem=false;
						$tmpFormItem='item.hidden';
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
					case 'radio':
					case 'select':
					case 'checkbox':
					case 'checkopt':
					case 'password':
					case 'textarea':
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
					case 'textbox':
						$tmpFormItem='item.textbox';
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
					case 'editor':
					case 'editor_ubb':
					case 'editor_html':
						$tmpFormItem='item.editor';
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
					case 'upload':
						$tmpFormItem='item.upload';
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
					default:
						$tmp_type='text';
						$tmp_Input=PagesCommon::getFormElement($tmp_type . '.' . $tmp_name . '$$$' . $tmpStyleInput,$tmp_Value,$treeNode->getItem('att'),$treeNode->getItem('template'));
						break;
				}
				$iditem=rd(self::ID_VALUE_ITEM,'name',$tmp_name?$tmp_name:('item'.$i));
				if($isItem){
					$tmpCaption=$treeNode->getItem('caption');
					$tmpCaptionExplain='';
					$placeholder=$treeItemProperty->getItem('placeholder');
					if(ins($tmpCaption,'$$$')>0){
						$tmpCaptionExplain=substr($tmpCaption,ins($tmpCaption,'$$$')+2);
						$tmpCaption=substr($tmpCaption,0,ins($tmpCaption,'$$$')-1);
					}
					if(ins($tmpCaptionExplain,'###')>0){
						$placeholder=substr($tmpCaptionExplain,ins($tmpCaptionExplain,'###')+2);
						$tmpCaptionExplain=substr($tmpCaptionExplain,0,ins($tmpCaptionExplain,'###')-1);
					}
					$names=$tmpCaption;
					//----------
					$tmpOptionSelect='';
					if($treeItemProperty->getItem('option')=='select' || $treeItemProperty->getItem('option')=='selected'){
						$tmp_Value_Option='';
						if($this->_form_ispost && $tmp_type!='put'){
							if($this->tree_formData->isItem($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name)) $tmp_Value_Option=$this->tree_formData->getItem($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name);
						}
						else if(isTree($this->tree_formRS) && $this->tree_formRS->isItem($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name)){
							$tmp_Value_Option=$this->tree_formRS->getItem($this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name);
						}
						else if(inp($this->TYPE_NODATAVALUE,$tmp_type,',')<1){
							if($treeItemProperty->getItem('option')=='selected') $tmp_Value_Option=$this->DEFAULT_FORM_OPTION_SELECT_VALUE;
						}
						$tmpOptionSelect=PagesCommon::getFormElement('checkbox.'.$this->DEFAULT_FORM_OPTION_SELECT_PREFIX.$tmp_name,$tmp_Value_Option,$this->DEFAULT_FORM_OPTION_SELECT_VALUE.':null');
						$tmpOptionSelect=trim($tmpOptionSelect);
						$this->_form_isoptionselect=true;
					}
					//----------
					$tmpNote='';
					$tmpExplain=$treeNode->getItem('explain');
					if(ins($tmpExplain,'$$$')>0){
						$tmpNote=substr($tmpExplain,0,ins($tmpExplain,'$$$')-1);
						$tmpExplain=substr($tmpExplain,ins($tmpExplain,'$$$')+2);
					}
					$tmpInput=$tmp_Input;
					$tmpClass=PagesCommon::getTemplatForm('class.row'.$treeItemProperty->getItem('class'));
					$tmpClassi=$treeFormvClassi->getItem($treeItemProperty->getItem('classi'));
					$_classiu=$tmpNote?'unit':'';
					$_tip_explain=$tmpExplain;$_extend_explain='';
					if($_tip_explain){
						if(ins($_tip_explain,'###')>0){
							$_extend_explain=substr($_tip_explain,ins($_tip_explain,'###')+2);
							$_tip_explain=substr($_tip_explain,0,ins($_tip_explain,'###')-1);
						}
						$_tip_explain=trim($_tip_explain,'<br>');
						$_tip_explain=trim($_tip_explain,'<br/>');
						$_tip_explain=r($_tip_explain,"\n",'<br>');
					}
					$_tip_type='';
					$tmpClewSign='';
					if($treeItemProperty->getItemNum('min')>0){
						$tmpClewSign=PagesCommon::getTemplatForm('clew.sign');
						$_tip_type='require';
						if(!$_tip_explain) $_tip_explain=rd(PagesCommon::getTemplatForm('tip.require'),'caption',$tmpCaption);
					}
					if(!$_tip_type && len($_tip_explain)>0){
						$_tip_type='help';
					}
					$tmpstr='';
					if(inp('radio,checkbox,select,textarea,password',$tmp_type)>0 && ins($tmpFormItem,'.')<1){
						$__formitem='item.'.($tmp_type=='textbox'?'textarea':$tmp_type);
						$tmpstr=PagesCommon::getTemplatForm($__formitem);
					}
					$ui_type=$tmp_type;
					if($treeItemProperty->isItem('ui.type')) $ui_type=$treeItemProperty->getItem('ui.type');
					if($ui_type=='null') $ui_type='';
					//debugx('ui_type:'.$ui_type);
					if(len($tmpstr)<3) $tmpstr=PagesCommon::getTemplatForm($tmpFormItem);
					$tmpstr=rd($tmpstr,'id.item',$iditem);
					$tmpstr=rd($tmpstr,'style',$tmpStyleItem);
					$tmpstr=rd($tmpstr,'class',$tmpClass);
					$tmpstr=rd($tmpstr,'caption',$tmpCaption);
					$tmpstr=rd($tmpstr,'caption.explain',$tmpCaptionExplain);
					$tmpstr=rd($tmpstr,'names',$names);
					$tmpstr=rd($tmpstr,'placeholder',$placeholder);
					$tmpstr=rd($tmpstr,'option.select',$tmpOptionSelect);
					$tmpstr=rd($tmpstr,'input',$tmpInput);
					$tmpstr=rd($tmpstr,'form.name',$this->_FormNames);
					$tmpstr=rd($tmpstr,'type',$tmp_type);
					$tmpstr=rd($tmpstr,'ui.type',$ui_type);
					$tmpstr=rd($tmpstr,'name',$tmp_name);
					$tmpstr=rd($tmpstr,'value',$tmp_Value);
					$tmpstr=rd($tmpstr,'att',$treeNode->getItem('att'));
					$tmpstr=rd($tmpstr,'classi',$tmpClassi);
					$tmpstr=rd($tmpstr,'classiu',$_classiu);
					$tmpstr=rd($tmpstr,'note',$tmpNote);
					$tmpstr=rd($tmpstr,'unit',$tmpNote);
					$tmpstr=rd($tmpstr,'explain',$tmpExplain);
					$tmpstr=rd($tmpstr,'clew.sign',$tmpClewSign);
					$tmpstr=rd($tmpstr,'tip.type',$_tip_type);
					$tmpstr=rd($tmpstr,'tip.explain',$_tip_explain);
					$tmpstr=rd($tmpstr,'extend.explain',$_extend_explain);
					$tmpstr=rd($tmpstr,'prop.type',$treeItemProperty->getItem('type'));
					$tmpstr=rd($tmpstr,'prop.min',$treeItemProperty->getItemInt('min'));
					$tmpstr=rd($tmpstr,'prop.max',$treeItemProperty->getItemInt('max'));
					$re.=$tmpstr;
				}
				else{
					switch($tmp_type){
						case 'bar':
						case 'multibar':
							$tmpCaption=$treeNode->getItem('caption');
							$tmpClass=$treeItemProperty->getItem('class');
							if(len($tmpClass)<1) $tmpClass=PagesCommon::getTemplatForm('class.'.$tmp_type);
							if(len($tmp_name)>0) $tmpstr=PagesCommon::getTemplatForm($tmp_type.'.'.$tmp_name);
							else $tmpstr=PagesCommon::getTemplatForm($tmp_type);
							$tmpstr=rd($tmpstr,'id.item',$iditem);
							$tmpstr=rd($tmpstr,'class',$tmpClass);
							$tmpstr=rd($tmpstr,'caption',$tmpCaption);
							$tmpstr=rd($tmpstr,'explain',$treeNode->getItem('explain'));
							$treeAtt=utilString::toTree($treeNode->getItem('att'),';','=');
							if($treeAtt->getItem('display')=='block'){
								$treeAtt->addItem('display.pop','pop');
								$treeAtt->addItem('display.active','active');
							}
							$tmpstr=utilRegex::toReplaceVar($tmpstr,$treeAtt);
							if($tmp_type=='multibar' && ins($re,'<!-- multibar.body -->')>0) { $re=r($re,'<!-- multibar.body -->',$tmpstr); }
							else { $re.=$tmpstr; }
							break;
						case 'blank':
							$tmpCaption=$treeNode->getItem('caption');
							$tmpClass=$treeItemProperty->getItem('class');
							if(len($tmpClass)<1) $tmpClass=PagesCommon::getTemplatForm('class.'.$tmp_type);
							if(len($tmp_name)>0) $tmpstr=PagesCommon::getTemplatForm($tmp_type.'.'.$tmp_name);
							else $tmpstr=PagesCommon::getTemplatForm($tmp_type);
							$tmpstr=rd($tmpstr,'id.item',$iditem);
							$tmpstr=rd($tmpstr,'class',$tmpClass);
							$tmpstr=rd($tmpstr,'caption',$tmpCaption);
							$tmpstr=rd($tmpstr,'explain',$treeNode->getItem('explain'));
							$tmpstr=utilRegex::toReplaceVar($tmpstr,utilString::toTree($treeNode->getItem('att'),';','='));
							$re.=$tmpstr;
							break;
						//case 'hide':
						case 'hidden':
							$re.=$tmp_Input;
							break;
					}
				}
			}
			$this->xcmlForm->doItemMove();
		}
		if($this->_form_isframe){
			$tmpOptionSelectAll='';
			$tmpSubmitTitle=$this->getFormVar('_submit_title');
			if(len($tmpSubmitTitle)<1) $tmpSubmitTitle=PagesCommon::getTemplatForm('submit.title');
			if(len($tmpSubmitTitle)<1){
				$tmpSubmitTitle=PagesCommon::getTemplatForm('submit.title.'.$this->_FormAction);
				if(len($tmpSubmitTitle)<1) $tmpSubmitTitle=PagesCommon::getTemplatForm('submit.title.default');
			}
			if($this->_form_isoptionselect){
				$tmpOptionSelectAll=PagesCommon::getTemplatForm('option.select.all');
			}
			$submitScript=$this->xcmlForm->getConfigure('form/submit.script');
			if(inp($submitScript,'onclick=')<1) $submitScript.=' onclick="return $pf.isSubmit()"';
			$tmpstr=PagesCommon::getTemplatForm('footer'.(inp('table',$this->_form_frame)>0?'.'.$this->_form_frame:''));
			$tmpstr=rd($tmpstr,'option.select.all',$tmpOptionSelectAll);
			$tmpstr=rd($tmpstr,'option.select.prefix',$this->DEFAULT_FORM_OPTION_SELECT_PREFIX);
			$tmpstr=rd($tmpstr,'submit.title',$tmpSubmitTitle);
			$tmpstr=rd($tmpstr,'submit.script',$submitScript);
			$re.=$tmpstr;
		}
		unsetr($treeNode,$treeItemProperty);
		$re.=self::toScriptFrame($this->xcmlForm->getConfigure('script.end'));
		return $re;
	}
	
	public static function toScriptFrame($re)
	{
		if(len($re)>3 and ins($re,'<script')<1) $re='<script type="text/javascript">'.$re.'</script>';
		return $re;
	}
	
}
?>