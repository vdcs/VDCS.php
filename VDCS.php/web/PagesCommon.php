<?
class PagesCommon
{
	
	public static function URL_SCRIPT() { return appURL('script'); }
	
	
	/*
	########################################
	########################################
	*/
	public static $_treeTemplate;
	public static function loadTemplate()
	{
		if(!self::$_treeTemplate){
			self::$_treeTemplate=getXCML2Tree(VDCSDTML::getConfigContent('common.config/control/pages.forms'),'key','value');
		}
	}
	public static function getTemplatForm($k) { self::loadTemplate(); return self::$_treeTemplate->getItem('form.'.$k); }
	
	
	/*
	##############################
	##############################
	*/
	/*
	getFormElement('select','0:正常|1:特殊')
	getFormElement('select.name','0:正常|1:特殊')
	getFormElement('select.name=222','0:正常|1:特殊')
	getFormElement('select.name=222$$$styles','0:正常|1:特殊')
	*/
	public static function getFormElement($strType,$strValue,$strAtt,$strTemplate='')
	{
		$re='';
		$tmpType=$strType;
		if(!(strpos($tmpType,'$$$')===false)){
			$tmpStyle=substr($tmpType,strpos($tmpType,'$$$')+3);
			$tmpType=substr($tmpType,0,strpos($tmpType,'$$$'));
		}
		if(!(strpos($tmpType,'.')===false)){
			$tmpName=substr($tmpType,strpos($tmpType,'.')+1);
			$tmpType=substr($tmpType,0,strpos($tmpType,'.'));
		}
		$tmpValue=$strValue;
		$tmpAtt=$strAtt;
		//debugx($strType.'='.$tmpType.'-'.$tmpName);
		switch($tmpType){
			case 'hidden':
				$tmpValue=utilCode::toFormHidden($tmpValue);
				if(!$strTemplate) $strTemplate='<input type="'.$tmpType.'" name="{$name}" value="{$value}" />';
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'style',$tmpStyle);
				$re=rd($re,'att',$tmpAtt);
				break;
			case 'text':
			case 'input':
				$tmpValue=utilCode::toFormInput($tmpValue);
				if(!$strTemplate) $strTemplate='<input type="'.$tmpType.'" name="{$name}" value="{$value}" {$style} {$att} />';	// class="txt"
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'style',$tmpStyle);
				$re=rd($re,'att',$tmpAtt);
				break;
			case 'password':
				$tmpValue=utilCode::toFormInput($tmpValue);
				if(!$strTemplate) $strTemplate='<input type="'.$tmpType.'" name="{$name}" value="{$value}" {$style} {$att} />';	// class="txt password"
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'style',$tmpStyle);
				$re=rd($re,'att',$tmpAtt);
				break;
			case 'textarea':
			case 'textbox':
				$tmpValue=utilCode::toFormTextarea($tmpValue);
				if(!$strTemplate) $strTemplate='<textarea name="{$name}" {$style} {$att}>{$value}</textarea>';
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'style',$tmpStyle);
				$re=rd($re,'att',$tmpAtt);
				break;
			case 'editor':
			case 'editor_ubb':
				$tmpValue=utilCode::toFormTextarea($tmpValue);
				if(!$strTemplate){
					$strTemplate=NEWLINE.'<textarea id="{$name}" name="{$name}" cols="50" rows="6">{$value}</textarea>';
					$strTemplate.=NEWLINE.'<script type="text/javascript">dcs.UBBeditor.replaceTextarea("{$name}",{toolbar:"{$toolbar}",height:{$height}});</script>';
					/*
					$strTemplate.=NEWLINE.'<script type="text/javascript">';
					$strTemplate.=NEWLINE.'var dcsEditor=new VDCS.libEditor("{$name}","ubb","{$toolbar}");';
					$strTemplate.=NEWLINE.'dcsEditor.setSize(null,{$height});';
					$strTemplate.=NEWLINE.'dcsEditor.doReplaceTextarea();';
					$strTemplate.=NEWLINE.'</script>';
					*/
				}
				$tmpTreeAtt=utilString::toTree($tmpAtt,';','=');
				$tmpHeight=$tmpTreeAtt->getItemInt('height');
				if($tmpHeight<1) $tmpHeight=200;
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'height',$tmpHeight);
				$re=rd($re,'toolbar',$tmpTreeAtt->getItem('toolbar'));
				break;
			case 'editor_html':
				$tmpValue=utilCode::toFormEditorHTML($tmpValue);
				if(!$strTemplate){
					$strTemplate=NEWLINE.'<textarea id="{$name}" name="{$name}" cols="50" rows="6">{$value}</textarea>';
					//$strTemplate.=NEWLINE.'<script type="text/javascript" src="'.self::URL_SCRIPT().'HTMLeditor/HTMLeditor.js"></script>';
					//$strTemplate.=NEWLINE.'<p><a href="javascript:htmlEditor();">HTML编辑器</a></p>';
					$strTemplate.=NEWLINE.'<script type="text/javascript">dcs.HTMLeditor.replaceTextarea("{$name}",{toolbar:"{$toolbar}",height:{$height}});</script>';
				}
				$tmpTreeAtt=utilString::toTree($tmpAtt,';','=');
				$tmpHeight=$tmpTreeAtt->getItemInt('height');
				if($tmpHeight<1) $tmpHeight=200;
				$re=$strTemplate;
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=rd($re,'height',$tmpHeight);
				$re=rd($re,'toolbar',$tmpTreeAtt->getItem('toolbar'));
				break;
			case 'radio':
				$tmpTreeAtt=utilString::toTree(self::toConvertAtt($tmpAtt),';','=');
				//debugTree($tmpTreeAtt);
				for($i=0;$i<$tmpTreeAtt->getCount();$i++){
					$tmpItemValue=$tmpTreeAtt->getItemKey();
					$tmpItemTitle=$tmpTreeAtt->getItemValue();
					if(strlen($tmpItemValue)>0 || strlen($tmpItemTitle)>0){
						if(strlen($tmpItemTitle)<1) $tmpItemTitle='['.$tmpItemValue.']';
						$tmpItemChecked='';
						if((''.$tmpItemValue)=='null') $tmpItemValue='';
						if((''.$tmpValue)==OPTION_VALUE_NO.($i+1) || strcasecmp($tmpValue,$tmpItemValue)==0) $tmpItemChecked=' checked';
						if((''.$tmpItemTitle)=='null') $tmpItemTitle='';
						else if(strlen($tmpItemTitle)<1) $tmpItemTitle=$tmpItemValue;
						$isbr=false;
						if(ins($tmpItemTitle,'<br/>')>0){
							$tmpItemTitle=r($tmpItemTitle,'<br/>','');
							$isbr=true;
						}
						$re.='<label><input type="radio" class="normal" name="'.$tmpName.'" value="'.$tmpItemValue.'" '.$tmpItemChecked.' '.$tmpStyle.' />'.$tmpItemTitle.'</label> ';
						if($isbr) $re.='<br/>';
					}
					$tmpTreeAtt->doMove();
				}
				break;
			case 'checkbox':
			case 'checkopt':
				$tmpTreeAtt=utilString::toTree(self::toConvertAtt($tmpAtt),';','=');
				$namepos=$tmpType=='checkbox'?'[]':'';
				for($i=0;$i<$tmpTreeAtt->getCount();$i++){
					$tmpItemValue=$tmpTreeAtt->getItemKey();
					$tmpItemTitle=$tmpTreeAtt->getItemValue();
					if(strlen($tmpItemValue)>0 || strlen($tmpItemTitle)>0){
						if(strlen($tmpItemTitle)<1) $tmpItemTitle='['.$tmpItemValue.']';
						$tmpItemChecked='';
						if((''.$tmpItemValue)=='null') $tmpItemValue='';
						if(inp($tmpValue,$tmpItemValue)>0 || inp($tmpValue,OPTION_VALUE_NO.($i+1))>0 || (''.$tmpValue)==OPTION_VALUE_ALL) $tmpItemChecked=' checked';
						if((''.$tmpItemTitle)=='null') $tmpItemTitle='';
						else if(strlen($tmpItemTitle)<1) $tmpItemTitle=$tmpItemValue;
						$isbr=false;
						if(ins($tmpItemTitle,'<br/>')>0){
							$tmpItemTitle=r($tmpItemTitle,'<br/>','');
							$isbr=true;
						}
						$re.='<label><input type="checkbox" class="normal" name="'.$tmpName.$namepos.'" value="'.$tmpItemValue.'" '.$tmpItemChecked.' '.$tmpStyle.' />'.$tmpItemTitle.'</label> ';
						if($isbr) $re.='<br/>';
					}
					$tmpTreeAtt->doMove();
				}
				break;
			case 'select':
				$tmpTreeAtt=utilString::toTree(self::toConvertAtt($tmpAtt),';','=');
				if($tmpName) $re='<select name="'.$tmpName.'" '.$tmpStyle.'>';	// class="sel" multiple
				for($i=0;$i<$tmpTreeAtt->getCount();$i++){
					$tmpItemValue=$tmpTreeAtt->getItemKey();
					$tmpItemTitle=$tmpTreeAtt->getItemValue();
					if(strlen($tmpItemValue)>0 || strlen($tmpItemTitle)>0){
						if(strlen($tmpItemTitle)<1) $tmpItemTitle='['.$tmpItemValue.']';
						$tmpItemChecked="";
						if((''.$tmpItemValue)=='null') $tmpItemValue='';
						if((''.$tmpValue)==OPTION_VALUE_NO.($i+1) || strcasecmp($tmpValue,$tmpItemValue)==0) $tmpItemChecked=' selected';
						if((''.$tmpItemTitle)=='null') $tmpItemTitle='';
						else if(strlen($tmpItemTitle)<1) $tmpItemTitle=$tmpItemValue;
						$re.=NEWLINE.'<option value="'.$tmpItemValue.'"'.$tmpItemChecked.'>'.$tmpItemTitle.'</option>';
					}
					$tmpTreeAtt->doMove();
				}
				if($tmpName) $re.=NEWLINE.'</select>';
				break;
			case 'selectbox':
				
				break;
			case 'upload':
				$tmpTreeAtt=utilString::toTree($tmpAtt,';','=');
				$tmpValue=utilCode::toFormHidden($tmpValue);
				if($tmpName){
					$strTemplate=self::getTemplatForm('element.upload.'.$tmpName);
				}
				else{
					$strTemplate=self::getTemplatForm('element.upload');
					if(!$strTemplate) $strTemplate='<iframe name="frame_upload" frameborder="0" width="100%" height="30" scrolling="no" src="{$up.url}channel={$up.channel}&sorts={$up.sorts}&filetype={$up.filetype}&filename={$up.filename}&formname={$up.formname}&forminput={$up.forminput}"></iframe>';
				}
				$re=$strTemplate;
				$_url=appURL('common.upload');if(!$_url) $_url=appURL('root').'p.'.EXT_SCRIPT.'?cp=common&p=upload';
				if(ins($_url,'?')>0) $_url.='&';
				else $_url.='?';
				$re=rd($re,'up.url',$_url);
				$re=rd($re,'name',$tmpName);
				$re=rd($re,'value',$tmpValue);
				$re=preg_replace('/\{\$up\.(.+?)\}/ies','$tmpTreeAtt->getItem(\'$1\')',$re);
				break;
		}
		return $re;
	}
	
	public static function toConvertAtt($strer,$p1=';',$p2='=')
	{
		$re='';
		$ary=explode($p1,$strer);
		foreach($ary as $v){
			if(ins($v,$p2)<1) $v.=$p2.$v;
			$re.=$p1.$v;
		}
		if(len($re)>0) $re=substr($re,1);
		return $re;
	}
	
	
	/*
	########################################
	################# Dict #################
	########################################
	*/
	public static $_tableDict=null;
	public static function doDictReload($f='') { if(!$f) $f='common.config/control/dict'; self::$_tableDict=VDCSDTML::getConfigCacheTable($f); }
	
	public static function getDictTable($strKey){return self::getDict($strKey,'',true);}
	public static function getDict($strKey,$strItems,$istable=false)
	{
		$tmpPlace=strpos($strKey,':');
		if($tmpPlace===false){
			if($istable) return self::getSortTable($strKey);
			return self::getDictValue($strKey,$strItems);
		}
		$tmpKey=toLower(substr($strKey,0,$tmpPlace));
		$tmpItems=substr($strKey,$tmpPlace+1);
		$tmpValue=toLower($strItems);
		//debugx($tmpKey.'--'.$tmpItems.'--'.$tmpValue);
		//----------
		$tmpTreeConfig=null;
		if(!self::$_tableAtt) self::doAttReload();
		self::$_tableAtt->doItemBegin();
		for($i=0;$i<self::$_tableAtt->getRow();$i++){
			if(self::$_tableAtt->getItemValue('key')==$tmpKey){
				$tmpTreeConfig=self::$_tableAtt->getItemTree();
				break;
			}
			self::$_tableAtt->doItemMove();
		}
		if(!$tmpTreeConfig){
			if($istable) return newTable();
			return '[att.error: config]';
		}
		//----------
		if(strpos($tmpItems,'=')===false) $tmpItems='sort='.$tmpItems;
		$tmpTreeItem=utilString::toTree($tmpItems,';','=');
		$tmpIndex=utilRegex::toReplaceVar($tmpTreeConfig->getItem('index'),$tmpTreeItem);
		//----------
		if($tmpKey=='file'){
			$tmpisExterior=false;
			$tmpFile=substr($tmpIndex,5);
			if(!(strpos($tmpIndex,DIR_SEPARATOR)===false)) { $tmpisExterior=true; }
			else{ $tmpFile='common.config/control/sort.'.$tmpFile; }
			$xcml=new utilXCML();
			$xcml->loadXML(VDCSDTML::getConfigContent($tmpFile));
			$xcml->doParse();
			if($xcml->isObj()){
				$tmpFile=$xcml->getConfigure('file');
				if(len($tmpFile)>0){
					$xcml=new utilXCML();
					$xcml->loadXML(VDCSDTML::getConfigContent($tmpFile));
					$xcml->doParse();
					$tmpisExterior=true;
				}
			}
			if(!$xcml->isObj()){
				if($istable) return newTable();
				return '[att.error: file or key]['.$tmpIndex.']['.$tmpFile.']';
			}
			if($tmpisExterior){
				$tmpTreeConfig->addItem('field.value',vv($xcml->getConfigure('field.att.value'),'value'));
				$tmpTreeConfig->addItem('field.name',vv($xcml->getConfigure('field.att.name'),'name'));
				$tmpTreeConfig->addItem('field.title',vv($xcml->getConfigure('field.att.title'),'title'));
			}
			$tmpTableData=utilXCMLExtend::toTable($xcml);
		}
		else{
			$tmpTableData=DB::queryTable($tmpIndex);
		}
		if($istable) return $tmpTableData;		// return table
		//----------
		$re='';
		if($tmpTableData){
			$tmpFieldValue=$tmpTreeConfig->getItem('field.value');
			$tmpTableData->doItemBegin();
			for($t=0;$t<$tmpTableData->getRow();$t++){
				if(strcasecmp($tmpTableData->getItemValue($tmpFieldValue),$tmpValue)==0){
					$re=$tmpTableData->getItemValue($tmpTreeConfig->getItem('field.title'));
					if(len($re)<1) $re=$tmpTableData->getItemValue($tmpTreeConfig->getItem('field.name'));
					break;
				}
				$tmpTableData->doItemMove();
			}
		}
		return $re;
	}
	
	public static function getSortTable($strSort)
	{
		$strSort=toLower($strSort);
		if(!self::$_tableDict) self::doDictReload();
		$reTable=newTable();
		$reTable->setFields(self::$_tableDict->getFields());
		self::$_tableDict->doItemBegin();
		for($i=0;$i<self::$_tableDict->getRow();$i++){
			if(strcasecmp(self::$_tableDict->getItemValue('sort'),$strSort)==0){
				$reTable->addItem(self::$_tableDict->getItemTree());
			}
			self::$_tableDict->doItemMove();
		}
		return $reTable;
	}
	public static function getDictValue($strSort,$strValue)
	{
		if(len($strSort)<1 || len($strValue)<1) return '';
		$re='';
		$strSort=toLower($strSort);
		if(!self::$_tableDict) self::doDictReload();
		self::$_tableDict->doItemBegin();
		for($i=0;$i<self::$_tableDict->getRow();$i++){
			if(strcasecmp(self::$_tableDict->getItemValue('sort'),$strSort)==0 && strcasecmp(self::$_tableDict->getItemValue('value'),$strValue)==0){
				$re=self::$_tableDict->getItemValue('title');
				if(len($re)<1) $re=self::$_tableDict->getItemValue('name');
				break;
			}
			self::$_tableDict->doItemMove();
		}
		return $re;
	}
	
	public static function getDictAtt($items)
	{
		$re='';
		if(ins($items,'=')>0){
			$sortk=substr($items,0,ins($items,'=')-1);
			$sortv=substr($items,ins($items,'='));
		}
		else{
			$sortk='sort';
			$sortv=$items;
		}
		if(!self::$_tableDict) self::doDictReload();
		self::$_tableDict->doItemBegin();
		for($i=0;$i<self::$_tableDict->getRow();$i++){
			if(strcasecmp(self::$_tableDict->getItemValue($sortk),$sortv)==0){
				$re.=';'.self::$_tableDict->getItemValue('value').'='.self::$_tableDict->getItemValue('name');
			}
			self::$_tableDict->doItemMove();
		}
		if($re) $re=toSubstr($re,2);
		return $re;
	}
	
	
	//####################
	//####################
	public static function dictItemString($dicts=null)
	{
		if(is_null($dicts)) $dicts=query('dicts');
		$dicta=toSplit($dicts,',');
		$rea=[];
		foreach($dicta as $dict){
			//debugx($dict);
			$tableDict=PagesCommon::getDictTable($dict);
			//debugTable($tableDict);
			$_item=self::dictTableString($tableDict);
			//debugx($_item);
			array_push($rea,$dict.'==='.$_item);
		}
		$_items=implode('$$$',$rea);
		//debugx($_items);
		return $_items;
	}
	public static function dictTableString($tableDict)
	{
		if($tableDict->getRow()<1) return '';
		$rea=[];
		$tableDict->doBegin();
		while($tableDict->isNext()){
			$title=$tableDict->getItemValue('title');
			if(len($title)<1) $title=$tableDict->getItemValue('name');
			if(len($title)<1) $title='['.$tableDict->getItemValue('value').']';
			array_push($rea,$tableDict->getItemValue('value').'###'.$tableDict->getItemValue('name').'###'.$title);
		}
		return implode('|||',$rea);
	}
	
	
	/*
	########################################
	################# Att ##################
	########################################
	*/
	protected static $_tableAtt=null;
	public static function doAttReload($f='') { if(!$f) $f='common.config/control/att'; self::$_tableAtt=VDCSDTML::getConfigCacheTable($f); }
	
	public static function getAtt($strKey,$strItems)
	{
		if($strKey=='dict') return self::getDictAtt($strItems);
		//----------
		if(self::$_tableAtt==null) self::doAttReload();
		self::$_tableAtt->doItemBegin();
		for($i=0;$i<self::$_tableAtt->getRow();$i++){
			if(self::$_tableAtt->getItemValue('key')==$strKey) { $tmpTreeConfig=self::$_tableAtt->getItemTree(); break; }
			self::$_tableAtt->doItemMove();
		}
		if(!$tmpTreeConfig) return '=[att.error: config]';
		//----------
		$tmpItems=$strItems;
		$tmpTableData;
		$tmpisFieldValue=false;
		$tmpisFieldName=false;
		$tmpisFieldTitle=false;
		if(strpos($tmpItems,'=')===false){
			$tmpTreeItem=new utilTree();
			$tmpTreeItem->addItem('sort',$tmpItems);
		}
		else{
			$tmpTreeItem=utilString::toTree($tmpItems,';','=');
			if(!(strpos($tmpItems,'field.value=')===false)) { $tmpTreeConfig->addItem('field.value',vv($tmpTreeItem->getItem('field.value'),'value')); $tmpisFieldValue=true; }
			if(!(strpos($tmpItems,'field.name=')===false)) { $tmpTreeConfig->addItem('field.name',vv($tmpTreeItem->getItem('field.name'),'name')); $tmpisFieldName=true; }
			if(!(strpos($tmpItems,'field.title=')===false)) { $tmpTreeConfig->addItem('field.title',vv($tmpTreeItem->getItem('field.title'),'title')); $tmpisFieldTitle=true; }
		}
		//debugTree($tmpTreeConfig);
		$tmpIndex=utilRegex::toReplaceVar($tmpTreeConfig->getItem('index'),$tmpTreeItem);
		//----------
		if($strKey=='file'){
			$tmpisExterior=false;
			$tmpFile=substr($tmpIndex,5);
			if(!(strpos($tmpIndex,DIR_SEPARATOR)===false)) { $tmpisExterior=true; }
			else { $tmpFile='common.config/control/sort.'.$tmpFile; }
			//debugx(VDCSDTML::getConfigContent($tmpFile));
			//debugx($tmpFile);
			$xcml=new utilXCML();
			$xcml->loadXML(VDCSDTML::getConfigContent($tmpFile));
			$xcml->doParse();
			if($xcml->isObj()){
				$cFile=$xcml->getConfigure('file');
				if(len($cFile)>0){
					$xcml=new utilXCML();
					$xcml->loadXML(VDCSDTML::getConfigContent($cFile));
					$xcml->doParse();
					$tmpisExterior=true;
				}
			}
			if(!$xcml->isObj()) return '=[att.error: file or key]';
			
			//debugx($xcml->getConfigure('field.att.value').'-'.$xcml->getConfigure('field.att.title'));
			//debugx(vv($xcml->getConfigure('field.att.value'),'value'));
			if(!$tmpisFieldValue) $tmpTreeConfig->addItem('field.value',vv($xcml->getConfigure('field.att.value'),'value'));
			if(!$tmpisFieldName) $tmpTreeConfig->addItem('field.name',vv($xcml->getConfigure('field.att.name'),'name'));
			if(!$tmpisFieldTitle) $tmpTreeConfig->addItem('field.title',vv($xcml->getConfigure('field.att.title'),'title'));
			
			if($tmpisExterior){
				$tmpTreeConfig->setItem('field.level',$xcml->getConfigure('field.att.level'));
			}
			//debugx($tmpFile);
			//debugTree($tmpTreeConfig);
			$tmpTableData=utilXCMLExtend::toTable($xcml);
		}
		else{
			$tmpTableData=DB::queryTable($tmpIndex);
		}
		//----------
		$re='';
		if($tmpTableData){
			$tmpFieldValue=$tmpTreeConfig->getItem('field.value');
			$tmpFieldName=$tmpTreeConfig->getItem('field.name');
			$tmpFieldTitle=$tmpTreeConfig->getItem('field.title');
			$tmpFieldLevel=$tmpTreeConfig->getItem('field.level');
			$tmpLevelBase=$tmpTreeConfig->getItemInt('level.base');
			$tmpSpaceBase=$tmpTreeConfig->getItem('space.base');
			$tmpSpaceLevel=$tmpTreeConfig->getItem('space.level');
			//debugx($tmpFieldValue.','.$tmpFieldTitle);
			$tmpTableData->doItemBegin();
			for($t=0;$t<$tmpTableData->getRow();$t++){
				$tmpSpace=$tmpSpaceBase;
				if(len($tmpFieldLevel)>0){
					for($l=$tmpLevelBase;$l<=$tmpTableData->getItemValueInt($tmpFieldLevel);$l++){
						$tmpSpace.=$tmpSpaceLevel;
					}
				}
				$tmpTitle=$tmpTableData->getItemValue($tmpFieldName);
				if(len($tmpTitle)<1) $tmpTitle=$tmpTableData->getItemValue($tmpFieldTitle);
				$re.=';'.$tmpTableData->getItemValue($tmpFieldValue).'='.$tmpSpace.$tmpTitle;
				$tmpTableData->doItemMove();
			}
			if(len($re)>0) $re=toSubstr($re,2);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getValueControlParams($func,$param1,$param2,$param3,$param4)
	{
		$aryParams[1]=$param1;
		$aryParams[2]=$param2;
		$aryParams[3]=$param3;
		$aryParams[4]=$param4;
		return self::getValueControl($func,$aryParams);
	}
	public static function getValueControl($func,$aryParams)
	{
		switch($func){
			case 'att':
				$re=self::getAtt($aryParams[1],$aryParams[2],$aryParams[3]);
				break;
			case 'dict':
				$re=self::getDict($aryParams[1],$aryParams[2]);
				break;
			case 'form.element':
				/*
				if($aryParams[2]=='__value'){
					$aryParams[2]='';
					if(ins($aryParams[1],'.')>0) $aryParams[2]=self::getDataItem(substr($aryParams[1],ins($aryParams[1],'.')+1));
				}
				*/
				if(len($aryParams[4])>0){
					if(ins($aryParams[4],'$$$')>0){
						$aryParams[3]=substr($aryParams[4],4);
					}
					else{
						switch($aryParams[3]){
							case 'dtml':			//???
								global $ctl;
								$aryParams[3]=$ctl->treeDTML->getItem($aryParams[4]);
								break;
							default:
								$aryParams[3]=self::getAtt($aryParams[3],$aryParams[4]);
								break;
						}
					}
				}
				$re=self::getFormElement($aryParams[1],$aryParams[2],$aryParams[3]);
				break;
			default:
				$re='[func: '.$func.']';
				break;
		}
		return $re;
	}
}
?>