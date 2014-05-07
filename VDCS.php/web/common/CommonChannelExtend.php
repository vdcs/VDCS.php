<?
class CommonChannelExtend
{
	const TABLES_CONFIG_NAME		= 'dbs_config';
	const TABLES_CLASS_NAME			= 'dbs_class';
	const TABLES_SPECIAL_NAME		= 'dbs_special';
	const TABLES_UPLOAD_NAME		= 'dbs_upload';
	const TABLES_NOTE_NAME			= 'dbs_note';
	const TABLES_NOTES_NAME			= 'dbs_notes';
	const TABLES_EVENT_NAME			= 'dbs_event';
	const TABLES_MANAGER_NAME		= 'dbs_manager';
	
	const CacheMode				= 'config';
	const ModeType				= 'channel';
	const ModeString			= 'common.channel';
	
	
	/*
	########################################
	########################################
	*/
	public static function getTree($channel,$file,$isCache=true)
	{
		$rfile=self::ModeType.'.'.$channel.'.'.$file;
		if($isCache){
			$arys=VDCSCache::getCache($rfile,'config',false);
			if(isAry($arys)){
				$reTree=newTree();
				$reTree->setArray($arys);
				return $reTree;
			}
		}
		//debugx($channel.'--'.$file);
		$reTree=self::getTreeFilter($channel,$file);
		if($isCache) VDCSCache::setCache($rfile,$reTree->getArray(),'config');
		return $reTree;
	}
	public static function getTreeFilter($channel,$file)
	{
		//debugx($channel);
		$cpath=self::ModeString.DIR_SEPARATOR.$channel.DIR_SEPARATOR;
		//##########
		//$treeConfigBase=VDCSDTML::getConfigTree($cpath.'config');
		$_xml=VDCSDTML::getConfigContent($cpath.'config');
		$_xml=r($_xml,'{@channel}',$channel);
		$treeConfigBase=getXCML2Tree($_xml);
		if(!$treeConfigBase->isItem('pre.root')) $treeConfigBase->addItem('pre.root',appv('url.root'));
		if(!$treeConfigBase->isItem('pre.rootc.script')) $treeConfigBase->addItem('pre.rootc.script',appv('url.root').'p.php?router='.$channel.'/');
		if(!$treeConfigBase->isItem('pre.rootc.rewrite')) $treeConfigBase->addItem('pre.rootc.rewrite',appv('url.root').$channel.'/');
		//debugTree($treeConfigBase);
		$treeConfigPre=$treeConfigBase->getFilterTree('pre.');
		//debugTree($treeConfigPre);
		$_xml=VDCSDTML::getConfigContent($cpath.'config@define');
		$_xml=r($_xml,'{@channel}',$channel);
		$_xml=utilRegex::toReplacePre($_xml,$treeConfigPre);
		$treeConfigBase=getXCML2Tree($_xml);
		$treeConfigPre->doAppendTree($treeConfigBase->getFilterTree('pre.'));
		//debugTree($treeConfigPre);
		//##########
		$_xml=VDCSDTML::getConfigContent($cpath.'config');
		$_xml=utilRegex::toReplacePre($_xml,$treeConfigPre);
		$treeConfigBase=getXCML2Tree($_xml);
		//debugTree($treeConfigBase);
		//##########
		$_xmld=VDCSDTML::getConfigContent($cpath.'config@define');
		$_xmld=r($_xmld,'{@channel}',$channel);
		$_xmld=utilRegex::toReplacePre($_xmld,$treeConfigPre);
		$treeConfigBase->doAppendTree(getXCML2Tree($_xmld));
		//debugTree($treeConfigBase);
		//##########
		if($file=='config') return $treeConfigBase;
		//##########
		$_xml=VDCSDTML::getConfigContent($cpath.$file);
		//debugvc($_xml);
		$_xml=utilRegex::toReplacePre($_xml,$treeConfigPre);
		//debugvc($_xml);
		$reTree=getXCML2Tree($_xml);
		//debugTree($reTree);
		//##########
		//if(inp('configure,sql',$file)>0){
			$_xmld=VDCSDTML::getConfigContent($cpath.$file.'@define');
			$_xmld=utilRegex::toReplacePre($_xmld,$treeConfigPre);
			$reTree->doAppendTree(getXCML2Tree($_xmld));
		//}
		//##########
		unset($_xml,$_xmld);
		if($file=='configure'){
			$reTree->doAppendTree($treeConfigBase);
			$reTree->setItem('__node',$reTree->getItem('__node').',url');		//,url.script,url.rewrite
			$urlMode = $treeConfigBase->getItem('config.url');
			if($urlMode==VDCSDTML::URL_DEFAULT) $urlMode=appv('url.mode');
			if($urlMode==VDCSDTML::URL_DEFAULT) $urlMode=VDCSDTML::URL_SCRIPT;
			if(!$urlMode) $urlMode=VDCSDTML::URL_SCRIPT;
			$urlroot=$treeConfigPre->getItem('rootc');
			$urlrootc=$treeConfigPre->getItem('rootc.'.$urlMode);
			$urlrootcscript=$treeConfigPre->getItem('rootc.script');
			$urlrootcrewrite=$treeConfigPre->getItem('rootc.rewrite');
			switch($channel){
				case 'common':		$rewriteext=appv('rewrite.ext');break;
				case 'account':
				case 'passport':	$rewriteext=appv('rewrite.exti');break;
				default:		$rewriteext=appv('rewrite.extc');break;
			}
			if(!$reTree->isItem('pre.rootc')) $reTree->addItem('pre.rootc',$urlrootc);
			if(!$reTree->isItem('url.script.index')) $reTree->addItem('url.script.index',$urlrootcscript);
			if(!$reTree->isItem('url.script.p')) $reTree->addItem('url.script.p',$urlrootcscript.'{$p}');
			if(!$reTree->isItem('url.script.pm')) $reTree->addItem('url.script.pm',$urlrootcscript.'{$p}/{$m}');
			if(!$reTree->isItem('url.script.pmi')) $reTree->addItem('url.script.pmi',$urlrootcscript.'{$p}/{$m}/{$mi}');
			//if(!$reTree->isItem('url.script.pma')) $reTree->addItem('url.script.pma',$urlrootcscript.'{$p}/{$m}&action={$action}');
			if(!$reTree->isItem('url.script.pa')) $reTree->addItem('url.script.pa',$urlrootcscript.'/pa&ap={$p}');
			if(!$reTree->isItem('url.script.pam')) $reTree->addItem('url.script.pam',$urlrootcscript.'/pa&ap={$p}&am={$m}');
			if(!$reTree->isItem('url.script.pami')) $reTree->addItem('url.script.pami',$urlrootcscript.'/pa&ap={$p}&am={$m}&ami={$mi}');
			if(!$reTree->isItem('url.script.x')) $reTree->addItem('url.script.x',$urlrootcscript.'{$p}&x=x');
			if(!$reTree->isItem('url.script.j')) $reTree->addItem('url.script.j',$urlrootcscript.'{$p}&x=j');
			if(!$reTree->isItem('url.script.page')) $reTree->addItem('url.script.page',$urlrootcscript.'page');
			if(!$reTree->isItem('url.script.res')) $reTree->addItem('url.script.res',$urlrootcscript.'{$res}');
			if(!$reTree->isItem('url.rewrite.index')) $reTree->addItem('url.rewrite.index',$urlrootcrewrite);
			if(!$reTree->isItem('url.rewrite.p')) $reTree->addItem('url.rewrite.p',$urlrootcrewrite.'{$p}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.pm')) $reTree->addItem('url.rewrite.pm',$urlrootcrewrite.'{$p}/{$m}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.pmi')) $reTree->addItem('url.rewrite.pmi',$urlrootcrewrite.'{$p}/{$m}/{$mi}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.pa')) $reTree->addItem('url.rewrite.pa',$urlrootcrewrite.'{$p}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.pam')) $reTree->addItem('url.rewrite.pam',$urlrootcrewrite.'{$p}/{$m}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.pami')) $reTree->addItem('url.rewrite.pami',$urlrootcrewrite.'{$p}/{$m}/{$mi}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.x')) $reTree->addItem('url.rewrite.x',$urlrootcrewrite.'{$p}/{$m}/{$mi}.x');
			if(!$reTree->isItem('url.rewrite.j')) $reTree->addItem('url.rewrite.j',$urlrootcrewrite.'{$p}/{$m}/{$mi}.j');
			if(!$reTree->isItem('url.rewrite.page')) $reTree->addItem('url.rewrite.page',$urlrootcrewrite.'{$page}'.$rewriteext);
			if(!$reTree->isItem('url.rewrite.res')) $reTree->addItem('url.rewrite.res',$urlrootcrewrite.'res_{$res}'.$rewriteext);
			//debugTree($reTree);
			$treeURL=$reTree->getFilterTree('url.'.$urlMode.'.');
			$reTree->doAppendTree($treeURL,'url.');
			unset($treeURL);
			//##########
			//pic.width,pic.height
			if($reTree->isItem('num.pic.width')){
				if($reTree->getItemInt('num.pic.width') < 1){
					$reTree->setItem('num.pic.width',appv('pic.width'));
					$reTree->setItem('num.pic.height',appv('pic.height'));
				}
			}
			//##########
		}
		if(inp('config,configure,sql',$file)>0){
			self::doClearBlank($reTree);
			/*
			$reTree->doBegin();
			for($t=0;$t<$reTree->getCount();$t++){
				$reTree->setItemValue(utilCode::toVarBlank($reTree->getItemValue()));
				$reTree->doMove();
			}
			*/
		}
		return $reTree;
	}
	
	//getBriefKeyTree
	public static function getPreTree($channel,$isCache=true,$ptype=true)
	{
		if($ptype) $treeConfig=self::getTree($channel,'config',$isCache);
		else $treeConfig=VDCSDTML::getConfigTree('common.channel/'.$channel.'/config');
		self::doClearBlank($treeConfig);
		return $treeConfig->getFilterTree('pre.');
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getSQLTree($channel,$file='sql')
	{
		if(!$file) $file='sql';
		$k='';
		if(ins($channel,'.')>0){
			$k=substr($channel,ins($channel,'.'));
			$channel=substr($channel,0,ins($channel,'.')-1);
		}
		$tmpKeys='';
		if($k) $tmpKeys=$k;
		if($tmpKeys) $tmpKeys='.'.$tmpKeys;
		$cacheKey='channel.'.$channel.'.'.$file.$tmpKeys.'.parse';		//'config.sql.map';
		$treeSQL=new utilTree();
		$ary=VDCSCache::getCache($cacheKey,'config',false);
		if(isa($ary)){
			$treeSQL->setArray($ary);
			unset($ary);
		}
		else{
			$treeLabel=self::getTree($channel,$file,false);
			$treeLabel->doBegin();
			for($t=0;$t<$treeLabel->getCount();$t++){
				$v=utilCode::toVarBlank($treeLabel->getItemValue());
				if(ins($v,'top 1 ')>0){
					$v=r($v,'top 1 ','');
					$v.=' limit 0,1';
				}
				if(ins($v,'top {$num} ')>0){
					$v=r($v,'top {$num} ','');
					$v.=' limit 0,{$num}';
				}
				$treeSQL->addItem($treeLabel->getItemKey(),$v);
				$treeLabel->doMove();
			}
			unset($treeLabel);
			$sqls=$treeSQL->getItem('label'.$tmpKeys.'.block');
			$sqls=rd($sqls,'table.name',$treeSQL->getItem('label'.$tmpKeys.'.table.name'));
			$sqls=rd($sqls,'table.fields',DB::toSafeFields($treeSQL->getItem('label'.$tmpKeys.'.table.fields')));
			$treeOrder=new utilTree();
			$treeOrder->setString($treeSQL->getItem('label'.$tmpKeys.'.block.order'),';','=');
			$treeOrder->doBegin();
			//debugTree($treeOrder);
			for($t=0;$t<$treeOrder->getCount();$t++){
				$treeSQL->addItem('label'.$tmpKeys.'.sql.'.$treeOrder->getItemKey(),rd($sqls,'order',$treeOrder->getItemValue()));
				$treeOrder->doMove();
			}
			$treeSQL->addItem('_field',$treeSQL->getItem('label'.$tmpKeys.'.table.field'));
			//config
			$treeConfig=self::getTree($channel,'configure',true);
			//url
			$url=$treeSQL->getItem('label'.$tmpKeys.'.block.url');
			//Log.note('label.block.url',url)
			//if(ins(url,'?')<1 And ins(url,'/')<1 And ins(url,'.')<1 Then
			if(substr($url,0,1)=='!'){
				$url=substr($url,1);
				$urlPage=$url;
			}
			else{
				$url=$treeConfig->getItem('url.'.$url);
				$urlPage=$treeConfig->getItem('url.'.$url.'.page');
				if(!$urlPage) $urlPage=$url;
			}
			$treeSQL->addItem('_url',$url);
			$treeSQL->addItem('_url.page',$urlPage);
			//url.list
			$urlList=$treeSQL->getItem('label'.$tmpKeys.'.list.url');
			//if(ins(urlList,'?')<1 And ins(urlList,'/')<1 And ins(urlList,'.')<1 Then
			if(substr($urlList,0,1)=='!'){
				$urlList=substr($urlList,1);
				$urlListPage=$urlList;
			}
			else{
				$urlList=$treeConfig->getItem('url.'.$urlList);
				$urlListPage=$treeConfig->getItem('url.'.$urlList.'.page');
				if(len($urlListPage)<1) $urlListPage=$urlList;
			}
			$treeSQL->addItem('_url.list',$urlList);
			$treeSQL->addItem('_url.list.page',$urlListPage);
			VDCSCache::setCache($cacheKey,$treeSQL->getArray(),'config');
			unset($treeOrder,$treeConfig);
		}
		return $treeSQL;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getYearTable($channel,$TableName,$FieldTime,$isCache=true)
	{
		if($isCache){
			$arys=VDCSCache::getCache(self::ModeType.'.'.$channel.'.year','config',false);
			if(isa($arys)){
				$reTable=newTable();
				$reTable->setArray($arys);
				return $reTable;
			}
		}
		$timYear='YEAR(FROM_UNIXTIME('.$FieldTime.'))';
		$sql='SELECT '.$timYear.' AS `year`,COUNT(*) AS `total` From '.$TableName.' GROUP BY '.$timYear.' order by '.$timYear.' desc';
		$reTable=DB::queryTable($sql);
		if(!$reTable->isObj()) $reTable->setFields('year,total');
		if($isCache) VDCSCache::setCache(self::ModeType.'.'.$channel.'.year',$reTable->getArray(),'config');
		return $reTable;
	}
	
	
	/*
	public function getChannelTables() As utilTable
		if(var_isCache Then
			Dim tmpAry As Variant
			tmpAry=VDCSCache::getCache('sys.channel','data')
			if(isa(tmpAry) Then
				$reTable=newTable();
				getChannelTables.setArray (tmpAry)
				return $reTable;
			}
		}
		$reTable=VDCSDTML::getConfigTable('common.config/channel');
		if(var_isCache Then VDCSCache::setCache('sys.channel',getChannelTables.getArray())
		return $reTable;
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public static function doCacheUpdate($channel,$file='all')
	{
		if(!$channel) return;
		switch($file){
			case 'all':
				
				break;
			case 'sql':
				
				break;
			case 'config':
			default:
				
				break;
		}
		if($file=='all'||$file=='config'){
			self::doCacheUpdateDel('channel.'.$channel.'.config');
			self::doCacheUpdateDel('channel.'.$channel.'.configure');
			self::doCacheUpdateDel('common.channel/'.$channel.'/config');
			self::doCacheUpdateDel('common.channel/'.$channel.'/configure');
		}
		if($file=='all'||$file=='sql'){
			self::doCacheUpdateDel('channel.'.$channel.'.sql.parse');
		}
	}
	public static function doCacheClear($s)
	{
		$f=pathDir('data.cache').'config/'.r($s,'/','__').EXT_CACHE;
		//debugx(($f);
		utilFile::doFileDel($f);
	}
	
	
	/*
	########################################
	########################################
	*/
	private static $tableChannelStruct=null;
	private static function loadStruct()
	{
		if(!isTable(self::$tableChannelStruct)){
			$keyType_=true;	//keys.checkType(dcs,1)
			//##########
			$aryTypeName[1]='article';
			$aryTypeName[11]='news';
			$aryTypeName[3]='photo';
			$aryTypeName[10]='shop,ask,blog';
			//##########
			$tmpTable=VDCSDTML::getConfigTable('common.config/channel');
			//debugTable($tmpTable);
			self::$tableChannelStruct=new utilTable();
			self::$tableChannelStruct->setFields($tmpTable->getFields());
			self::$tableChannelStruct->doAppendFields('typename');
			$tmpTable->doBegin();
			while($tmpTable->isNext()){
				$itemBool=false;
				$typev=$tmpTable->getItemValueInt('type');
				if(!$keyType_){
					$channelv=$tmpTable->getItemValue('channel');
					switch($typev){
						case 1:
							if($channelv==$aryTypeName[1] || $channelv==$aryTypeName[11]) $itemBool=true;
							break;
						case 3:
							if($channelv==$aryTypeName[3]) $itemBool=true;
							break;
						default:
							if($typev<1){
								if(inp($aryTypeName[10],$channelv)<1) $itemBool=true;
							}
							break;
					}
				}
				else{
					$itemBool=true;
				}
				if($itemBool){
					switch($typev){
						case 1:		$typename=$aryTypeName[1]; break;
						case 3:		$typename=$aryTypeName[3]; break;
						default:	$typename=''; break;
					}
					$treeItem=$tmpTable->getItemTree();
					$treeItem->addItem('typename',$typename);
					self::$tableChannelStruct->addItem($treeItem);
					//self::$tableChannelStruct->setItemValue('typename',$typename);
				}
			}
			unset($aryTypeName,$tmpTable,$treeItem);
		}
	}
	
	public static function getStructTree($channel)
	{
		$reTree=new utilTree();
		self::loadStruct();
		self::$tableChannelStruct->doBegin();
		while(self::$tableChannelStruct->isNext()){
			if($channel==self::$tableChannelStruct->getItemValue('channel')){
				$reTree=self::$tableChannelStruct->getItemTree();
				break;
			}
		}
		return $reTree;
	}
	
	public static function getStructTypename($channel)
	{
		$treeChannelStruct=self::getStructTree($channel);
		$typename=$treeChannelStruct->getItem('typename');
		unset($treeChannelStruct);
		return $typename;
	}
	
	public static function getStructTermTable($strTerm,$strValue,$strTerms)
	{
		global $cfg;
		$reTable=new utilTable();
		$reTable->setFields('channel,type,typename,name,names,unit,title,model,linkurl');
		self::loadStruct();
		self::$tableChannelStruct->doBegin();
		while(self::$tableChannelStruct->isNext()){
			$tmpChannel=self::$tableChannelStruct->getItemValue('channel');
			$tmpTree=$cfg->getChannelTree($tmpChannel,'configure',false);
			if($tmpTree->getCount()>0){
				$tmpBool=false;
				switch($strTerms){
					case 'all':
						$tmpBool=true;
						break;
					case 'exact':
						if($tmpTree->getItem($strTerm)==$strValue) $tmpBool=True;
						break;
					case 'in':
						if(inp($strValue,$tmpTree->getItem($strTerm))>0) $tmpBool=True;
						break;
					default:
						if(inp($tmpTree->getItem($strTerm),$strValue)>0) $tmpBool=True;
						break;
				}
				if($tmpBool){
					$tmpTreeItem=$tmpTree->getFilterTree('var.');
					$tmpTreeItem->addItem('channel',$tmpChannel);
					$tmpTreeItem->addItem('type',self::$tableChannelStruct->getItemValue('type'));
					$tmpTreeItem->addItem('typename',self::$tableChannelStruct->getItemValue('typename'));
					$tmpTreeItem->addItem('model',$tmpTree->getItem('config.model'));
					$tmpTreeItem->addItem('linkurl',$tmpTree->getItem('url.index'));
					$reTable->addItem($tmpTreeItem);
				}
			}
			unset($tmpTree,$tmpTreeItem);
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doClearBlank(&$tree_,$ignores='template.')
	{
		$iglen=len($ignores);
		$tree_->doBegin();
		for($t=1;$t<$tree_->getCount();$t++){
			$key_=$tree_->getItemKey();
			/*
			if(right($key_, 3)='.px'){
				$tree_->addItem(Left($key_, Len($key_)-3) & '.prefix', $tree_->getItemValue());
			}
			if(right($key_, 7)='.prefix'){
				$tree_->addItem(Left($key_, Len($key_)-7) & '.px', $tree_->getItemValue());
			}
			*/
			if(left($key_, $iglen)!=$ignores){
				$tree_->setItem($key_,utilCode::toVarBlank($tree_->getItemValue()));
			}
			$tree_->doMove();
		}
	}
	
}
