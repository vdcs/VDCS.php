<?
class CommonLabel
{
	const LABEL_CACHE_STATUS			= false;		//ÊÇ·ñ¿ªÊ¼LABEL»º´æ
	const LABEL_CACHE_UPDATE_TIME			= 60;			//µ¥Î» ·Ö
	const LABEL_CACHE_SPACE_SYMBOL			= '~~~$$$~~~';
	
	private $_isCache,$_CacheTime;
	private $mapXCML;
	
	public function __construct()
	{
		$this->_isCache=false;
		$this->_CacheTime=self::LABEL_CACHE_UPDATE_TIME;
	}
	
	public function __destruct()
	{
		unset($this->mapXCML);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setCache($b) { $this->_isCache=$b; }
	
	
	/*
	public function toDTMLCacheValue($re)
		toDTMLCacheValue=themes.toDTMLCacheValue(re)
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function toParse($re)
	{
		$_matches=utilRegex::toMatches($re,CommonTheme::PATTERN_DTML_LABEL_BLOCK);
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],$this->toParseBlock($_matches[1][$m],$_matches[2][$m],$_matches[3][$m]));
		}
		unset($_matches);
		return $re;
	}
	
	public function toParseLists($re,$channel,$key,$strTable,$pattern='')
	{
		if(len($pattern)<1) $pattern=CommonTheme::PATTERN_DTML_LABEL_LIST;
		$_matches=utilRegex::toMatches($re,$pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$re=r($re,$_matches[0][$m],$this->toParseListTable($channel,$key,$strTable,$_matches[1][$m]));
		}
		unset($_matches);
		return $re;
	}
	
	public function toParseListTable($channel,$key,$tableData,$strTpl)
	{
		$x_channel=$channel;
		$x_key=$key;
		$treeSQL=$this->getSQLTree($x_channel,$x_key,'new','','',10);
		if(!$tableData) return '<!-- toParseListTable:notable -->';
		return $this->toCommonProcess($x_channel,$tableData,$strTpl,$treeSQL,new utilTree());
	}
	
	
	/*
	########################################
	########################################
	*/
	//<label:block("news","list","res=new;classid=;row=10;col=1;len=45;lens=")>
	public function toParseBlock($strKey,$strItems,$strTpl)
	{
		$re='';
		if(len($strTpl)<1) return $re;
		global $cfg;
		//####################
		$x_channel=$strKey;
		$x_key='';
		if(ins($x_channel,'.')>0){
			utilString::lists($x_channel,$x_channel,$x_key,'.');
			//list($x_channel,$x_key)=split('.',$x_channel,2);
			//$x_key=toSubstr($x_channel,ins($x_channel,'.')+1);
			//$x_channel=toSubstr($x_channel,1,ins($x_channel,'.')-1);
		}
		//debugx($x_channel.','.$x_key);
		//####################
		$itemTree=utilString::toTree($strItems,';','=');
		//#################### »º´æ
		$tmpCacheSpace=$itemTree->getItemInt('cache');
		if($tmpCacheSpace>0){
			$tmpisCache=true;
		}
		else{
			$tmpisCache=$this->_isCache;
			$tmpCacheSpace=$this->_CacheTime;
		}
		if($tmpisCache){
			$tmpCacheTree=new utilTree();
			$tmpCacheName=$strKey.':'.$strItems;
			$tmpCacheKey='data.label.block';
			$tmpCacheKey='data.label.block.'.utilCoder::toMD5($tmpCacheName);
			$tmpCacheAry=VDCSCache::getCache($tmpCacheKey,'data',false);
			if(isa($tmpCacheAry)){
				$tmpCacheTree->setArray($tmpCacheAry);
				if($tmpCacheTree->isItem($tmpCacheName)){
					$re=$tmpCacheTree->getItem($tmpCacheName);
					$tmpCacheUpdate=toSubstr($re,1,ins($re,self::LABEL_CACHE_SPACE_SYMBOL)-1);
					//debugx($tmpCacheUpdate.'-'.$tmpCacheName);
					if(isInt($tmpCacheUpdate)){
						if((DCS::timer()-$tmpCacheUpdate)<$tmpCacheSpace*60){
							$re=toSubstr($re,ins($re,self::LABEL_CACHE_SPACE_SYMBOL)+len(self::LABEL_CACHE_SPACE_SYMBOL));
							return $re;
						}
					}
				}
			}
		}
		//#################### »º´æ
		$x_res=$itemTree->getItem('res');if(len($x_res)<1) $x_res='new';
		//int _len=$itemTree->getItemInt('len');
		//int _lens=$itemTree->getItemInt('lens');
		$x_row=$itemTree->getItemInt('row');if($x_row<1 || $x_row>500) $x_row=10;
		$x_col=$itemTree->getItemInt('col');if($x_col<1 || $x_col>500) $x_col=1;
		$x_offset=$itemTree->getItemInt('offset');if($x_offset<1 || $x_offset>1000) $x_offset=0;
		//####################
		$x_term=$itemTree->getItem('term');
		$x_query=$itemTree->getItem('query');
		$x_classid=$itemTree->getItemInt('classid');
		if($x_classid>0){
			if(!$cfg->clas) $cfg->doClassInit();
			$x_classids=$cfg->clas->getChannelValue($x_channel,$x_classid,'ids');
			if(len($x_classids)>0){
				if(len($x_query)>0){
					$x_query='classid in ('.$x_classids.') && '.$x_query;
				}
				else{
					$x_query='classid in ('.$x_classids.')';
				}
			}
		}
		$treeSQL=$this->getSQLTree($x_channel,$x_key,$x_res,$x_query,$x_term,$x_row * $x_col,$x_offset);
		//####################
		$sql=$treeSQL->getItem('sql');
		$tableData=new utilTable();
		if(len($sql)>0){
			$tableData=DB::queryTable($sql);
			//####################
			$re=$this->toCommonProcess($x_channel,$tableData,$strTpl,$treeSQL,$itemTree);
		}
		//#################### »º´æ
		if($tmpisCache){
			//debugx('-'.$tmpCacheName);
			$tmpCacheTree->addItem($tmpCacheName,DCS::timer().self::LABEL_CACHE_SPACE_SYMBOL.$re);
			//debugTree($tmpCacheTree);
			VDCSCache::setCache($tmpCacheKey,$tmpCacheTree->getArray(),'data');
			unset($tmpCacheTree);
		}
		//#################### »º´æ
		return $re;
	}
	
	
	public function toParseClass($strKey,$strItems,$strTpl)
	{
		global $cfg;
		$re='';
		if(len($strTpl)<1) return $re;
		$x_channel=$strKey;
		//####################
		//if(ins(strTpl,'[item:linkurl]')>0){
		//	   strTpl=r(strTpl,'[item:linkurl]',cfg.getChannelValues(x_channel,'configure','url.list','{$classid}=[item:id]'))
		//}
		//####################
		$itemTree=utilString::toTree($strItems,';','=');
		$x_res=$itemTree->getItem('res');
		$x_row=0;
		//####################
		//$x_m=$treeSQL->getItem('m');
		$x_ismulti=false;
		if($itemTree->getItem('m')=='multi') $x_ismulti=true;
		$treePart=null;
		if($x_ismulti) $treePart=$this->toTemplatePartTree($strTpl);
		//####################
		//Dim tableData,treeItem,t,n,x_tpl,x_levelid
		if(!$cfg->clas) $cfg->doClassInit();
		$tableData=$cfg->clas->getChannelTable($x_channel);
		$tableData->doItemBegin();
		switch($x_res){
		case 'level':
			$x_level=$itemTree->getItemInt('level');
			$x_row=$itemTree->getItemInt('row');
			$n=1;
			for($t=0;$t<$tableData->getRow();$t++){
				$x_levelid=$tableData->getItemValueInt('levelid');
				if($x_levelid <= $x_level){
					if($x_row>0 && $n>$x_row) break;
					$treeItem=$tableData->getItemTree();
					CommonTheme::doItemAppend($treeItem,$n);
					if(!$x_ismulti){
						$x_tpl=$strTpl;
					}
					else{
						if($treePart->isItem('level'.$x_levelid)){
							$x_tpl=$treePart->getItem('level'.$x_levelid);
						}
						else{
							$x_tpl=$treePart->getItem('item');
						}
					}
					$re.=VDCSDTML::toReplaceItems($x_tpl,$treeItem);
					$n=$n+1;
				}
				$tableData->doItemMove();
			}
			break;
		case 'sub':
			$x_classid=$itemTree->getItemInt('classid');
			$x_row=$itemTree->getItemInt('row');
			$n=1;
			for($t=0;$t<$tableData->getRow();$t++){
				if($tableData->getItemValueInt('fatherid')==$x_classid){
					if($x_row>0 && $n>$x_row) break;
					$treeItem=$tableData->getItemTree();
					CommonTheme::doItemAppend($treeItem,$n);
					if(!$x_ismulti){
						$x_tpl=$strTpl;
					}
					else{
						$x_levelid=$tableData->getItemValueInt('levelid');
						if($treePart->isItem('level'.$x_levelid)){
							$x_tpl=$treePart->getItem('level'.$x_levelid);
						}
						else{
							$x_tpl=$treePart->getItem('item');
						}
					}
					$re.=VDCSDTML::toReplaceItems($x_tpl,$treeItem);
					$n=$n+1;
				}
				$tableData->doItemMove();
			}
			break;
		default:
			for($t=0;$t<$tableData->getRow();$t++){
				$x_levelid=$tableData->getItemValueInt('levelid');
				$treeItem=$tableData->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				if(!$x_ismulti){
					$x_tpl=$strTpl;
				}
				else{
					if($treePart->isItem('level'.$x_levelid)){
						$x_tpl=$treePart->getItem('level'.$x_levelid);
					}
					else{
						$x_tpl=$treePart->getItem('item');
					}
				}
				$re.=VDCSDTML::toReplaceItems($x_tpl,$treeItem);
				$tableData->doItemMove();
			}
			break;
		}
		return $re;
	}
	
	public function toParseSpecial($strKey,$strItems,$strTpl)
	{
		$re='';
		if(len($strTpl)<1) return $re;
		$x_channel=$strKey;
		//####################
		//if(ins(strTpl,'[item:linkurl]')>0){
		//	   strTpl=r(strTpl,'[item:linkurl]',cfg.getChannelValues(x_channel,'configure','url.list','{$classid}=[item:id]'))
		//}
		//####################
		$itemTree=utilString::toTree($strItems,';','=');
		$x_level=$itemTree->getItemInt('level');
		$x_row=$itemTree->getItemInt('row');
		//####################
		global $cfg;
		/*
		$tableData=$cfg->spec->getChannelTable($x_channel);
		$tableData->doItemBegin();
		for($t=0;$t<$tableData->getRow();$t++){
			if($x_row>0 && $t>$x_row) break;
			$treeItem=$tableData->getItemTree();
			CommonTheme::doItemAppend($treeItem,$t+1);
			$re.=VDCSDTML::toReplaceItems($strTpl,$treeItem);
			$tableData->doItemMove();
		}
		*/
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toCommonProcess($channel,$tableData,$strTpl,$treeSQL,$itemTree)
	{
		global $cfg;
		$re='';
		//####################
		$x_field=$treeSQL->getItem('field');
		if(ins($strTpl,'[item:linkurl]')>0){
			$x_url=$treeSQL->getItem('url');
			$x_url=r($x_url,'{$id}','[item:id]');
			$strTpl=r($strTpl,'[item:linkurl]',$x_url);
		}
		//####################
		$x_isClass=false;
		if(ins($strTpl,'[item:class')>0){
			$x_isClass=true;
			$x_classURL=$treeSQL->getItem('url.list');
			$x_classURL=r($x_classURL,'{$classid}','[item:classid]');
			$x_classString='<a class="class" href="'.$x_classURL.'">[item:classname]</a>';
			//$strTpl=r(strTpl,'[item:class]',x_classString);
		}
		//####################
		/*
		$x_isSPic=false;
		if(ins($strTpl,'[item:spic')>0){
			$x_isSPic=true;
			$x_spicString='<img class="pic" src="[item:spic.url]" />';
			$strTpl=r($strTpl,'[item:spic]',$x_spicString);
		}
		*/
		//####################
		$x_isPic=false;
		if(ins($strTpl,'[item:pic')>0){
			$x_isPic=true;
			$x_picString='<img class="pic" src="[item:pic.url]" />';
			$strTpl=r($strTpl,'[item:pic]',$x_picString);
		}
		//####################
		//$x_m=$treeSQL->getItem('m');
		$x_ismulti=false;
		if($itemTree->getItem('m')=='multi') $x_ismulti=true;
		$treePart=null;
		if($x_ismulti) $treePart=$this->toTemplatePartTree($strTpl);
		//####################
		$jsonFields=$treeSQL->getItem('json.fields');
		//####################
		$tableData->doItemBegin();
		for($t=0;$t<$tableData->getRow();$t++){
			if(!$x_ismulti){
				$x_tpl=$strTpl;
			}
			else{
				if($treePart->isItem('item'.($t+1))){
					$x_tpl=$treePart->getItem('item'.($t+1));
				}
				else{
					$x_tpl=$treePart->getItem('item');
				}
			}
			$treeItem=$this->toFieldTree($tableData->getItemTree(),$x_field);
			//debugTree($treeItem);
			CommonTheme::doItemAppend($treeItem,$t+1);
			//####################
			if($jsonFields) $treeItem->extractJson($jsonFields);
			//####################
			if($x_isClass){
				$classID=$treeItem->getItemInt('classid');
				$className='';
				$classURL=r($x_classURL,'[item:classid]',$classID);
				$classString='';
				$treeItem->addItem('classurl',$classURL);
				if($classID>0){
					if(!$cfg->clas) $cfg->doClassInit();
					$className=$cfg->clas->getChannelName($channel,$classID);
					$treeItem->addItem('classid',$classID);
					$treeItem->addItem('classname',$className);
					$treeItem->addItem('classlink',$classURL);
					$classString=$x_classString;
				}
				$x_tpl=r($x_tpl,'[item:class]',$classString);
			}
			if($x_isPic){
				$picURL=CommonTheme::toUploadURL($treeItem->getItem('pic'));
				$treeItem->addItem('pic.url',$picURL);
				//$treeItem->delItem('pic');
				//$x_tpl=r(x_tpl,'[item:spic]',$x_spicString);
			}
			if($x_isSPic){
				$picURL=CommonTheme::toUploadURL($treeItem->getItem('spic'));
				$treeItem->addItem('spic.url',$picURL);
				//$treeItem->delItem('spic');
				//$x_tpl=r($x_tpl,'[item:spic]',$x_spicString);
			}
			$re.=VDCSDTML::toReplaceItems($x_tpl,$treeItem);
			$tableData->doItemMove();
		}
		return $re;
	}
	
	public function toTableFilter($channel,&$tableData,$treeSQL,$realTpl=true)
	{
		global $cfg;
		$x_field=$treeSQL->getItem('field');
		//####################
		if($realTpl){
			$x_isLink=true;
			$x_url=$treeSQL->getItem('url');
			$x_url=r($x_url,'{$id}','[item:id]');
			//$strTpl=r($strTpl,'[item:linkurl]',$x_url);
			
			$x_isClass=true;
			$x_classURL=$treeSQL->getItem('url.list');
			$x_classURL=r($x_classURL,'{$classid}','[item:classid]');
			$x_classString='<a class="class" href="'.$x_classURL.'">[item:classname]</a>';
			//$strTpl=r(strTpl,'[item:class]',x_classString);
			
			$x_isSPic=true;
			$x_spicString='<img class="pic" src="[item:spic.url]" />';
			//$strTpl=r($strTpl,'[item:spic]',$x_spicString);
			
			$x_isPic=true;
			$x_picString='<img class="pic" src="[item:pic.url]" />';
			//$strTpl=r($strTpl,'[item:pic]',$x_picString);
		}
		//####################
		$tableData->doAppendFields('sn,oe,'.utilString::toTree($x_field,';','=')->getFields().',linkurl,classurl,classid,classname,classlink,pic.url,spic.url');
		$tableData->doItemBegin();
		for($t=0;$t<$tableData->getRow();$t++){
			$treeItem=$this->toFieldTree($tableData->getItemTree(),$x_field);
			//debugTree($treeItem);
			CommonTheme::doItemAppend($treeItem,$t+1);
			if($x_isLink){
				$id=$treeItem->getItemInt('id');
				$x_linkurl=$x_url;
				$x_linkurl=r($x_linkurl,'[item:id]',$id);
				$treeItem->addItem('linkurl',$x_linkurl);
			}
			if($x_isClass){
				$classID=$treeItem->getItemInt('classid');
				$className='';
				$classURL=r($x_classURL,'[item:classid]',$classID);
				$classString='';
				$treeItem->addItem('classurl',$classURL);
				if($classID>0){
					if(!$cfg->clas) $cfg->doClassInit();
					$className=$cfg->clas->getChannelName($channel,$classID);
					$treeItem->addItem('classid',$classID);
					$treeItem->addItem('classname',$className);
					$treeItem->addItem('classlink',$classURL);
					$classString=$x_classString;
					$classString=r($classString,'[item:classid]',$classID);
					$classString=r($classString,'[item:classname]',$className);
					$classString=r($classString,'[item:classlink]',$classURL);
				}
				$treeItem->addItem('class',$classString);
			}
			if($x_isPic){
				$picURL=CommonTheme::toUploadURL($treeItem->getItem('pic'));
				$treeItem->addItem('pic.url',$picURL);
				$x_picString=r($x_picString,'[item:pic.url]',$picURL);
				$treeItem->addItem('pic',$x_picString);
			}
			if($x_isSPic){
				$picURL=CommonTheme::toUploadURL($treeItem->getItem('spic'));
				$treeItem->addItem('spic.url',$picURL);
				$x_picString=r($x_picString,'[item:spic.url]',$picURL);
				$treeItem->addItem('spic',$x_picString);
			}
			$tableData->setItem($treeItem);
			$tableData->doItemMove();
		}
		return $tableData;
	}
	
	
	public function toTemplatePartTree($strTpl,$strn=5)
	{
		$reTree=new utilTree();
		$strTpl='<label:part>'.$strTpl.'</label>';
		$rPattern=VDCSDTML::PATTERN_DTML_VARS;
		$rNode='(<@part:([\w]*)>'.PATTERN_FLAG_CONTENT.')?';
		$rPattern='<label:part>'.PATTERN_FLAG_CONTENT;
		for($n=0;$n<$strn;$n++){
			$rPattern.=$rNode;
		}
		$rPattern.='<\/label>';
		$_matches=utilRegex::toMatches($strTpl,$rPattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$reTree->addItem('item',$_matches[1][$m]);
			for($n=0;$n<floor((count($_matches)-2)/3);$n++){
				$reTree->addItem($_matches[($n)*3+3][$m],$_matches[($n)*3+4][$m]);
			}
			break;
		}
		//debugTree($reTree);
		//debugAry($_matches);
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getSQLTree($strChannel,$strKey,$strRes,$strQuery,$strTerm,$total,$offset=0)
	{
		$sqlChannel=$strChannel;
		if(len($strKey)>0){
			$sqlChannel.='.'.$strKey;
		}
		if(ins($strChannel,'.')>0){
			$strKey=toSubstr($strChannel,ins($strChannel,'.')+1);
			$strChannel=toSubstr($strChannel,1,ins($strChannel,'.')-1);
		}
		$tmpKeys='';
		if(len($strKey)>0) $tmpKeys=$strKey;
		if(len($tmpKeys)>0) $tmpKeys='.'.$tmpKeys;
		$strQueryAppend='';
		if(len($strQuery)>0){
			//$strQuery='`'.$strQuery;			//?????
			//$strQuery=r($strQuery,'=','`=');
			$strQueryAppend=' and '.$strQuery;
		}
		$treeSQL=CommonChannelExtend::getSQLTree($sqlChannel,'sql');
		//debugTree($treeSQL);
		//debugx('label'.$tmpKeys.'.sql.'.$strRes);
		$sql=$treeSQL->getItem('label'.$tmpKeys.'.sql.'.$strRes);
		$sql=rd($sql,'num',$total);
		$sql=rd($sql,'total',$total);
		$sql=rd($sql,'limit',$total);
		$sql=rd($sql,'offset',$offset);
		if($offset>0){
			$sql=r($sql,' limit 0,',' limit '.$offset.',');
		}
		//debugx('$term='.$strTerm);
		$sql=rd($sql,'term',$strTerm);
		$sql=rd($sql,'query',$strQuery);
		$sql=rd($sql,'query.append',$strQueryAppend);
		$reTree=new utilTree();
		$reTree->addItem('sql',$sql);
		$reTree->addItem('field',$treeSQL->getItem('_field'));
		$reTree->addItem('json.fields',$treeSQL->getItem('label'.$tmpKeys.'.json.fields'));
		$reTree->addItem('url',$treeSQL->getItem('_url'));
		$reTree->addItem('url.page',$treeSQL->getItem('_url.page'));
		$reTree->addItem('url.list',$treeSQL->getItem('_url.list'));
		$reTree->addItem('url.list.page',$treeSQL->getItem('_url.list.page'));
		unset($treeSQL);
		return $reTree;
	}
	
	
	public function toFieldTree($strTree,$strField)
	{
		$reTree=new utilTree();
		$treeField=new utilTree();
		$treeField->setString($strField,';','=');
		$treeField->doBegin();
		for($t=0;$t<$treeField->getCount();$t++){
			$reTree->addItem($treeField->getItemKey(),$strTree->getItem($treeField->getItemValue()));
			$treeField->doMove();
		}
		unsetr($treeField);
		$strTree->doBegin();
		for($t=0;$t<$strTree->getCount();$t++){
			$reTree->addItem($strTree->getItemKey(),$strTree->getItemValue());
			$strTree->doMove();
		}
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getXCMLValue($strFile,$k,$strItems='')
	{
		$re='';
		if(isa($this->mapXCML['tree='.$strFile])){
			$tmpTree=new utilTree();
			$tmpTree->setArray($this->mapXCML['tree='.$strFile]);
		}
		else{
			$tmpTree=VDCSDTML::getConfigCacheTable($strFile);		//$dcs->getConfigTree
			$this->mapXCML['tree='.$strFile]=$tmpTree->getArray();
		}
		$re=$tmpTree->getItem($k);
		if(len($strItems)>0){
			$treeItem=utilString::toTree($strItems,'&','=');
			$re=utilRegex::toReplaceVar($re,$treeItem);
		}
		return $re;
	}
	
	public function getXCMLItem($strFile,$k,$terms,$strItems='')
	{
		$re='';
		if(isa($this->mapXCML['table='.$strFile])){
			$tmpTable=new utilTable();
			$tmpTable->setArray($this->mapXCML['table='.$strFile]);
		}
		else{
			$tmpTable=VDCSDTML::getConfigCacheTable($strFile);		//$dcs->getConfigTree
			$this->mapXCML['table='.$strFile]=$tmpTable->getArray();
		}
		$re=$tmpTable->getTermsValue($terms,$k);
		if(len($strItems)>0){
			$tmpTreeItem=utilString::toTree($strItems,'&','=');
			$re=utilRegex::toReplaceVar($re,$tmpTreeItem);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	
	/*
	########################################
	########################################
	*/
	public function toParseLinks($sort,$tpl)
	{
		$re='';
		//if($this->_isCache){
			$tmpAry=VDCSCache::getCache('data.label.links','data',false);
			if(isa($tmpAry)){
				$tableData=new utilTable();
				$tableData->setArray($tmpAry);
			}
		//}
		if(!isTable($tableData)){
			$sql='select * from dbc_links where l_status=1 order by orderid desc,l_id asc';
			$tableData=DB::queryTable($sql);
			$tableData->doFilter ('l_');
			VDCSCache::setCache('data.label.links',$tableData->getArray(),'data');
		}
		$n=1;
		$tableData->doItemBegin();
		for($t=0;$t<$tableData->getRow();$t++){
			if($sort=$tableData->getItemValue('sort')){
				$treeItem=$tableData->getItemTree();
				CommonTheme::doItemAppend($treeItem,$n);
				$re.=CommonTheme::toReplaceEncodeFilter($tpl,$treeItem,VDCSDTML::PATTERN_DTML_ITEMS);
				$n=$n+1;
			}
			$tableData->doItemMove();
		}
		return $re;
	}
}
?>