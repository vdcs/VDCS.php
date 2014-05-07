<?
class AppCacheBase
{
	
	public static function isDebug(){return DEBUGV=='app.cache';}
	
	public static function doUpdate()
	{
		global $_cfg;
		include_once(VDCS_PATH.'config/defined'.EXT_EXECUTE);
		
		//####################
		$_file=appPaths('data.cache/config/configure'.EXT_CACHE);
		
		$treeApp=self::getAppTree('vdcs.config/app');
		$treeApp->doAppendTree(self::getAppTree('common.config/app'));
		$treeApp->doAppendTree(self::getAppTree('common.config/app@'.DCS::serverString()));
		$treeApp->doAppendTree(self::getAppTree('common.config/app@'.DCS::browseDomain()));
		//debugTree($treeApp);
		
		$_v='<'.'?'.''.NEWLINE;
		$_v.=NEWLINE.'// App Value';
		//####################
		$_cfg['app']['year']=DCS::year();
		//####################
		$value=$treeApp->getItem('var.ext.script');	//$treeApp->delItem('var.ext.script');
		if(!$value) $value=EXT_EXECUTE;
		$treeApp->addItem('ext.script',$value);
		$value=$treeApp->getItem('var.ext.rewrite');	//$treeApp->delItem('var.ext.rewrite');
		if(!$value) $value=EXT_HTML;
		$treeApp->addItem('ext.rewrite',$value);
		//####################
		if(!$treeApp->isItem('var.rewrite.ext')) $treeApp->addItem('var.rewrite.ext',$value);		//channel
		if(!$treeApp->isItem('var.rewrite.exti')) $treeApp->addItem('var.rewrite.exti',$value);		//account,passport
		if(!$treeApp->isItem('var.rewrite.extc')) $treeApp->addItem('var.rewrite.extc',$value);		//common
		$treeApp->addItem('rewrite.ext',$treeApp->getItem('var.rewrite.ext'));
		$treeApp->addItem('rewrite.exti',$treeApp->getItem('var.rewrite.exti'));
		$treeApp->addItem('rewrite.extc',$treeApp->getItem('var.rewrite.extc'));
		//####################
		$value=$treeApp->getItem('var.ext.x');		//$treeApp->delItem('var.ext.x');
		if(!$value) $value=EXT_XML;
		$treeApp->addItem('ext.x',$value);$treeApp->addItem('ext.xml',$value);$treeApp->addItem('ext.xmls',$value);
		$value=$treeApp->getItem('var.ext.j');		//$treeApp->delItem('var.ext.j');
		if(!$value) $value='.json';
		$treeApp->addItem('ext.j',$value);$treeApp->addItem('ext.json',$value);
		if(!$treeApp->isItem('script.public')) $treeApp->addItem('script.public','public');
		//##########
		//$treeApp->addItem('header.xcompat.ie7','<meta http-equiv="X-UA-Compatible" content="IE=7" />');
		//$treeApp->addItem('header.xcompat.ie7e','<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />');
		//$treeApp->addItem('header.xcompat.ie8','<meta http-equiv="X-UA-Compatible" content="IE=8" />');
		//$treeApp->addItem('header.xcompat.edge','<meta http-equiv="X-UA-Compatible" content="edge" />');
		//$value=$treeApp->getItem('var.header.xcompat');
		//if(!$value) $value=$treeApp->getItem('header.xcompat.ie8');
		//if($treeApp->getItem('header.xcompat')=='default') $treeApp->addItem('header.xcompat',$value);
		//if(!$treeApp->isItem('header.xcompat')) $treeApp->addItem('header.xcompat',$value);
		//##########
		$value=$treeApp->getItem('var.url.mode');
		if(!$value) $value=VDCSDTML::URL_SCRIPT;
		$treeApp->addItem('url.mode',$value);
		//##########
		/*
		$value=$treeApp->getItemInt('var.pic.width');
		if($value<1) $value=120;
		$treeApp->addItem('pic.width',$value);
		$value=$treeApp->getItemInt('var.pic.height');
		if($value<1) $value=90;
		$treeApp->addItem('pic.height',$value);
		*/
		//####################
		self::doFillVars($treeApp);
		//####################
		foreach($_cfg['app'] as $k=>$v){
			$treeApp->addItem($k,$v);
		}
		//####################
		$treeApp->doBegin();
		for($t=0;$t<$treeApp->getCount();$t++){
			$_v.=NEWLINE.'$_cfg[\'app\'][\''.$treeApp->getItemKey().'\']='.VDCSCache::toValue($treeApp->getItemValue(),1).';';
			$treeApp->doMove();
		}
		
		$_v.=NEWLINE.NEWLINE;
		
		$sIni=utilSettings::loadINI(appPaths('common.config/configure'.EXT_INI));
		$sInio=utilSettings::loadINI(appPaths('common.config/configure@'.DCS::serverString().EXT_INI));
		$sInis=utilSettings::loadINI(appPaths('common.config/configure@'.DCS::browseDomain().EXT_INI));
		$aConfig=self::getIniSection('Config',$sIni,$sInio,$sInis);		//$sIni->getSection('Config');
		if(self::isDebug()){
			debugx(appPaths('common.config/configure'.EXT_INI));
			debugx(appPaths('common.config/configure@'.DCS::serverString().EXT_INI));
			debugx(appPaths('common.config/configure@'.DCS::browseDomain().EXT_INI));
		}

		//解析值(app,var)
		$_v.=NEWLINE.'// app,var';
		if(isa($aConfig)){
			switch($aConfig['var.script.d']){
				case 'now':		$aConfig['var.script.d']=r(r(r(DCS::now(),'-',''),':',''),' ','');break;
				case 'today':		$aConfig['var.script.d']=r(DCS::today(),'-','');break;
				case 'update':		$aConfig['var.script.d']=r(VDCS_UPDATE,'-','');break;
				//default:		$aConfig['var.script.d']=r(VDCS_UPDATE,'-','');break;
			}
			foreach($aConfig as $k=>$v){
				if(substr($k,0,4)=='app.' || substr($k,0,4)=='var.'){
					$_v.=NEWLINE.'$_cfg[\'app\'][\''.$k.'\']			= '.VDCSCache::toValue($v,1).';';
				}
			}
		}
		$_v.=NEWLINE.NEWLINE;
		
		//解析URL
		$_v.=NEWLINE.'// url';
		if(isa($aConfig)){
			foreach($aConfig as $k=>$v){
				if(substr($k,0,4)=='url.'){
					$_cfg['sys.url'][substr($k,4)]=$v;
				}
			}
		}
		//##########
		$_cfg['sys.url']['ua.index']=$_cfg['sys.url'][APP_UA.'.index']?$_cfg['sys.url'][APP_UA.'.index']:$_cfg['sys.url']['account'];
		$_cfg['sys.url']['ua.login']=$_cfg['sys.url'][APP_UA.'.login']?$_cfg['sys.url'][APP_UA.'.login']:$_cfg['sys.url']['login'];
		$_cfg['sys.url']['ua.logout']=$_cfg['sys.url'][APP_UA.'.logout']?$_cfg['sys.url'][APP_UA.'.logout']:$_cfg['sys.url']['logout'];
		//##########
		$manageDir=$sIni->get('Manage.dir');if(!$manageDir) $manageDir='manage';if(right($manageDir,1)!='/')$manageDir.='/';
		$manageURL=$sIni->get('Manage.url');
		if(is_dir(appPaths($manageDir,false,true))){		// hold www/manage/
			$_cfg['sys.url']['manage']=$manageDir;
			$_cfg['sys.dir']['manage']=$manageDir;
		}
		else{
			if(!$manageURL) $manageURL='manage'.EXT_SCRIPT;
			$_cfg['sys.url']['manage']=APP_BASEURL.$manageURL;
			$_cfg['sys.dir']['manage']='common/'.$manageDir;
		}
		//##########
		
		//##########
		foreach($_cfg['sys.url'] as $k=>$v){
			if(left($v,1)!='/'&&instr($v,'://')<1) $v=APP_BASEURL.$v;
			$_v.=NEWLINE.'$_cfg[\'app\'][\'url.'.$k.'\']			= '.VDCSCache::toValue($v,1).';';
		}
		$_v.=NEWLINE.NEWLINE;
		
		//解析目录
		$_v.=NEWLINE.'// dir';
		if(isa($aConfig)){
			foreach($aConfig as $k=>$v){
				if(substr($k,0,4)=='dir.'){
					$_cfg['sys.dir'][substr($k,4)]=$v;
				}
			}
		}
		foreach($_cfg['sys.dir'] as $k=>$v){
			//$path=realpath($v);
			$path=$v;
			if(!isRealPath($path)) $path=_BASE_PATH_ROOT.$v;
			$path=toDirPath($path,1);
			//debugx($v.'=='.$path);
			$_v.=NEWLINE.'$_cfg[\'sys.path\'][\''.$k.'\']			= '.VDCSCache::toValue($path).';';
		}
		$_v.=NEWLINE.NEWLINE;
		
		//解析 系统配置
		$_v.=NEWLINE.'// sys';
		if(len($aConfig['time.zone'])>0) $_v.=NEWLINE.'define(\'TIMEZONE\',					'.VDCSCache::toValue($aConfig['time.zone'],1).');';
		if(len($aConfig['cookies.name'])>0) $_v.=NEWLINE.'$_cfg[\'sys\'][\'cookies.name\']			= '.VDCSCache::toValue($aConfig['cookies.name'],1).';';
		$_v.=NEWLINE.NEWLINE;
		
		//解析 数据库配置
		$_v.=NEWLINE.'// db';
		$aDB=self::getIniSection('Database',$sIni,$sInio,$sInis);
		if($aDB['s.type']){
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'type\']			= '.VDCSCache::toValue($aDB['s.type'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'port\']			= '.VDCSCache::toValue($aDB['s.port'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'perdure\']			= '.VDCSCache::toValue($aDB['s.perdure'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'server\']			= '.VDCSCache::toValue($aDB['s.server'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'database\']			= '.VDCSCache::toValue($aDB['s.database'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'user\']			= '.VDCSCache::toValue($aDB['s.user'],1).';';
			//$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'username\']			= '.VDCSCache::toValue($aDB['s.username'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'password\']			= '.VDCSCache::toValue($aDB['s.password'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'charset\']			= '.VDCSCache::toValue($aDB['s.charset'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'charset.result\']		= '.VDCSCache::toValue($aDB['s.charset.result'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'tablepx\']			= '.VDCSCache::toValue($aDB['s.tablepx'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.dbs\'][\'debug\']			= '.VDCSCache::toValue($aDB['s.debug'],1).';';
		}
		if($aDB['type']){
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'type\']				= '.VDCSCache::toValue($aDB['type'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'port\']				= '.VDCSCache::toValue($aDB['port'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'perdure\']			= '.VDCSCache::toValue($aDB['perdure'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'server\']			= '.VDCSCache::toValue($aDB['server'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'database\']			= '.VDCSCache::toValue($aDB['database'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'user\']				= '.VDCSCache::toValue($aDB['user'],1).';';
			//$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'username\']			= '.VDCSCache::toValue($aDB['username'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'password\']			= '.VDCSCache::toValue($aDB['password'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'charset\']			= '.VDCSCache::toValue($aDB['charset'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'charset.result\']		= '.VDCSCache::toValue($aDB['charset.result'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'tablepx\']			= '.VDCSCache::toValue($aDB['tablepx'],1).';';
			$_v.=NEWLINE.'$_cfg[\'sys.db\'][\'debug\']			= '.VDCSCache::toValue($aDB['debug'],1).';';
		}
		$_v.=NEWLINE.NEWLINE;
		
		//解析 频道
		$_v.=NEWLINE.'// channel';
		$tableChannel=self::getAppTable('common.config/channel');
		$tableChannel->doBegin();
		while($tableChannel->isNext()){
			$type=$tableChannel->getItemValueInt('type');
			$channel=$tableChannel->getItemValue('channel');
			$portal='';
			switch($type){
				case 1:
					$portal='article';
					break;
				case 2:
					$portal='content';
					break;
			}
			if($portal) $_v.=NEWLINE.'$_cfg[\'channel\'][\''.$channel.'.portal\']			= '.VDCSCache::toValue($portal,1).';';
		}
		
		
		$_v.=NEWLINE;		//.'?'.'>';
		//debugx(appPaths('common.config/configure'.EXT_INI));
		//print_r($GLOBALS['_cfg']);
		if(self::isDebug()){
			debugvc($_v);
			//doFileWrite($_file,$_v);
		}
		else{
			//debugvc($_v);
			doFileWrite($_file,$_v);
		}
	}
	public static function getIniSection($section,&$sIni,&$sInio,&$sInis)
	{
		$rea=$sIni->getSection($section);
		$ary=$sInio->getSection($section);
		if($ary) $rea=array_merge($rea,$ary);
		$ary=$sInis->getSection($section);
		if($ary) $rea=array_merge($rea,$ary);
		return $rea;
	}
	
	
	public static function doClear()
	{
		$_file=dcsFilePath('data.cache/config/configure'.EXT_CACHE);
		doFileDel($_file);
	}
	
	
	public static function doFillVars(&$treeApp)
	{
		if(!$treeApp->isItem('web.generator')) $treeApp->addItem('web.generator',APP_VERSION_NAME);
		//if(!$treeApp->isItem('web.author')) $treeApp->addItem('web.author','VDCS');
		//if(!$treeApp->isItem('web.powerby')) $treeApp->addItem('web.powerby','<a href="http://www.vdcs.cn/" target="_blank"><img id="Power-by-VDCS" class="icon" src="/images/Power-by-VDCS.gif" title="Power by VDCS" /></a>');
	}
	
	
	public static function getAppTree($path)
	{
		if(!isRealPath($path)) $path=appPaths($path);
		//debugx($path);
		if(self::isDebug()) debugx($path);
		$_content=getFileContent($path);
		$_content=VDCSDTML::toParseV($_content);
		return getXCML2Tree($_content);
	}
	public static function getAppTable($path)
	{
		if(!isRealPath($path)) $path=appPaths($path);
		//debugx($path);
		$_content=getFileContent($path);
		$_content=VDCSDTML::toParseV($_content);
		return getXCML2Table($_content);
	}
	
}
