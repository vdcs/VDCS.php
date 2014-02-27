<?
class ManageChannel
{
	public $channel='',$modulec='',$portal='',$module='',$modulei='',$action='';
	
	public $_var=array();
	public $_mapConfigure=array();
	
	
	public function __construct()
	{
		$this->_var['channel.clone']='system';
	}
	public function __destruct()
	{
		unset($this->_var,$this->_mapConfigure);
	}
	
	
	public function setVar($k,$v){$this->_var[$k]=$v;}
	public function getVar($k){return $this->_var[$k];}
	
	public function getModules()
	{
		$re=$this->portal;
		if($this->module) $re.='.'.$this->module;
		if($this->modulei) $re.='.'.$this->modulei;
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit($s='')
	{
		if($s) $this->set($s);
		$this->basepath=ManageCommon::getPaths('config');
		//debuga($this->basepath);
	}
	
	public function load($s) { $this->set($s); }
	public function is() { return (len($this->channel)>0) ? true : false; }
	public function set($s) { $this->channel=$s; }
	public function get() { return $this->channel; }
	
	
	/*
	########################################
	########################################
	*/
	public function getTree($channel,$module,$file='',$isCache=false)
	{
		if(len($channel)<1) $channel=$this->channel;
		if($channel!=$this->channel) $_portals=$channel;
		if(len($file)<1) $file='configure';
		//debugx('channel='.$channel.',module='.$module);
		if(len($_portals)<1) $_portals=CommonChannelExtend::getStructTypename($channel);
		if(len($_portals)<1) $_portals=$channel;
		if(!$file) $file='configure';
		//debugx('getTree: '.$channel.'--'.$module.'--'.$file.'--'.$_portal);
		//##########
		$basepath='manage.channel.config/';
		if(len($module)>0){
			$filename=rd($file.'.{$module}','module',$module);
			//$filename=rd($file.'.{$modulei}','modulei',$modulei);
		}
		else{
			$filename=$file;
		}
		//$filepath=appFilePath($basepath.$filename);
		//$filepath=rd($filepath,'channel',$_portal);
		//debuga($this->basepath);
		//debugTrace();
		//debugx($filename);
		$filepath=VDCSConfig::toPathsReal($this->basepath,$filename,$channel,0);
		//debugx($filepath);
		if(!is_file($filepath) && queryx('debug')=='path') debugx($channel.'.'.$filename.' no found: '.$filepath);
		//##########
		$cacheName='manage.channel.'.$channel.($module?'_'.$module:'').'.'.$file;
		//debugx($cacheName);
		//##########
		if($isCache){
			$arys=VDCSCache::getCache($cacheName,'config',false);
			if(isAry($arys) && @filemtime(VDCSCache::toPath($cacheName,'config'))>@filemtime($filepath)){
				$reTree=newTree();
				$reTree->setArray($arys);
				//debugTree($reTree);
				return $reTree;
			}
		}
		//##########
		$_channelClone=$this->_var['channel.clone'];
		if(!isFile($filepath) && $_channelClone){
			$filepath=r($filepath,'/'.$channel.'/','/'.$_channelClone.'/');
		}
		//debugx($filepath);
		$_xml=VDCSDTML::getConfigContent($filepath);
		$reTree=getXCML2Tree($_xml);
		$treePre=CommonChannelExtend::getPreTree($channel);
		$treePre->doAppendTree($reTree->getFilter('pre.'));
		$_xml=utilRegex::toReplacePre($_xml,$treePre);
		$reTree=getXCML2Tree($_xml);
		//debugTree($reTree);
		//##########
		$filepathAt=VDCSConfig::toPathsPlace($this->basepath,$filename,$_portal,'config.manage');
		$filepathAt=r($filepathAt,EXT_XCML,'@define'.EXT_XCML);
		if(isFile($filepathAt)){
			$_xml=VDCSDTML::getConfigContent($filepathAt);
			$_xml=utilRegex::toReplacePre($_xml,CommonChannelExtend::getPreTree($channel));
			$reTree->doAppendTree(getXCML2Tree($_xml));
		}
		unset($_xml);
		//##########
		//debugTree($reTree);
		CommonChannelExtend::doClearBlank($reTree);
		if($isCache) VDCSCache::setCache($cacheName,$reTree->getArray(),'config');
		return $reTree;
	}
	
	//func('','config');			[channel]	
	//func('order','config');		[channel]	order
	//func('shop/order','config');		shop		order
	protected function paramParse($modules,&$_channel,&$_portal='',&$mapKey='')
	{
		$_modules=$modules;
		if($modules=='_') $modules='';
		$_channel='';$_portal=$modules;
		if(ins($modules,':')>0) utilString::lists($modules,$_channel,$_portal,':');
		elseif(ins($modules,'/')>0) utilString::lists($modules,$_channel,$_portal,'/');
		if(!$_channel) $_channel=$this->channel;
		$mapKey=$_channel.'.'.$_portal;
		//,&$_keys
		//$_keys=$_channel.OBJECT_CONNECT_SYMBOL;
		//if(len($_portal)>0) $_keys.=$_portal.OBJECT_CONNECT_SYMBOL;
		//debugx($_modules.' = modules='.$modules.' , channel='.$_channel.', portal='.$_portal.', mapKey='.$mapKey);
	}
	public function getConfigure($modules,$node,$k=null)
	{
		$_modules=$modules;
		//debugx($modules);
		$this->paramParse($modules,$_channel,$_portal,$mapKey);
		if(!$this->_mapConfigure[$mapKey]){
			//debugx($_modules.' = modules='.$modules.' , channel='.$_channel.', portal='.$_portal.', mapKey='.$mapKey);
			$this->_mapConfigure[$mapKey]=$this->getTree($_channel,$_portal,'configure');
			//debugTree($this->_mapConfigure[$mapKey]);
		}
		/*if($_channel=='account' && !$this->dbgmap){
			debugTree($this->_mapConfigure[$mapKey]);
			$this->dbgmap=true;
		}*/
		if($k==null){
			$k=$node;
			$node='';
		}
		//debugx(($node?($node.'.'):'').$k);
		return $this->_mapConfigure[$mapKey]->getItem(($node?($node.'.'):'').$k);
	}
	
	public function getConfigureTree($modules='',$node='')
	{
		$this->paramParse($modules,$_channel,$_portal,$mapKey);
		//debugx($modules.' = '.$_channel.'--'.$_portal.'--'.$k.'--'.$mapKey);
		if(!$this->_mapConfigure[$mapKey]) $this->_mapConfigure[$mapKey]=$this->getTree($_channel,$_portal,'configure');
		return $node?$this->_mapConfigure[$mapKey]->getFilterTree($node.'.'):$this->_mapConfigure[$mapKey];
	}
	public function getConfigureValue($node,&$modules,&$k=null)
	{
		if($k==null){
			$k=$modules;
			$modules=$this->modules;
		}
		//debugx($modules.', '.$node.', '.$k);
		return $this->getConfigure($modules,$node,$k);
	}
	public function getConfig($modules,$k=null){return $this->getConfigureValue('config',$modules,$k);}
	//public function getConfigm($k){return $this->getConfig($this->modulec,$k);}
	public function getLang($modules,$k=null){$re=$this->getConfigureValue('lang',$modules,$k);return $re;}
	//public function getLangm($k){return $this->getLang($this->modulec,$k);}
	
	public function getTitle($modules=null,$action=null)
	{
		if(is_null($modules)) $modules=$this->modules;
		if(is_null($action)) $action=$this->action;
		//debugx('modules='.$this->modules);
		$_key='title'.($action?('.'.$action):'');
		$re=(len($modules)>0) ? $this->getLang($modules,$_key) : $this->getLang($_key);
		if(len($re)<1) $re='['.$modules.':'.$_key.']';
		//debugx($_key.'='.$re);
		return $re;
	}
	public function getPre($modules,$k=null)
	{
		/*
		if(is_null($field)){
			$field=$channel;
			$channel=$this->channel;
		}*/
		$re=$this->getConfigureValue('pre',$modules,$k);
		if(!$re) $re=CommonChannelExtend::getPreTree($this->channel)->getItem($k);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public $tableDataHandle=null;
	public function setHandle($s){$this->chn->setHandle($s);}
	public function doHandle($modules=null)
	{
		$re=false;
		$this->_var['handle.is']=false;
		
		if(isPost()){
			$handle=post('_select_handle');
			$ids=posts('_select_id',1);
			$backurl=post('_backurl');
		}
		else{
			$handle=query('handle');
			$ids=utilCode::toValues(querys('selectids'),1,'string');
			$backurl=query('backurl');
		}
		//debugx($handle.'='.$ids);
		if(!$handle || !$ids) return $re;
		
		if(is_null($modules)) $modules=$this->modules;		// && $this->module
		$_total=count(explode(',',$ids));
		$this->setVar('handle',$handle);
		$this->setVar('handle.ids',$ids);
		$this->setVar('handle.total',$_total);
		//$tmpModule=(module.length()>0) ? module+':' : '';
		$sqlHandle=$this->getConfig($modules,'list.handle.sql.'.$handle);
		if(len($sqlHandle)>0){
			$sqlData='select * from '.$this->getConfig($modules,'table.name').' where '.$this->getConfig($modules,'table.field.id').' in ('.$ids.')';
			$this->tableDataHandle=DB::queryTable($sqlData);
			if(inp($this->getConfig($modules,'list.handle.put'),$handle)>0){
				switch($handle){
					case 'audit':
						
						break;
					case 'delete':
						//$usere->doNotesDeduct(var_channel,'put',ids,'')
						break;
				}
			}
			if($this->_var['handle.upload']){
				$_rootid=$this->_var['handle.upload.rootid'];
				if(!$_rootid) $_rootid=$ids;
				$_dataid=$this->_var['handle.upload.dataid'];
				if($_dataid=='ids') $_dataid=$ids;
				CommonUploadExtend::doParseDelete($this->channel,$_rootid,$_dataid);
			}
			$sqlHandle=rd($sqlHandle,'ids',$ids);
			$sqlHandle=rd($sqlHandle,'tim',DCS::timer());
			//debugx($sqlHandle);
			if(!$this->_var['handle.debug.exec']) DB::execBatch($sqlHandle);
			//##########
			$message=$this->getLang($modules,'handle.ok.'.$handle);
			if(!$message) $message='['.$handle.' handle total {$count}.';
			$message=$this->toHandleStatReplace($message,$_total);
			if(!$backurl) $backurl=DCS::browseURL(true);
			$this->setVar('handle.message',$message);
			$this->setVar('handle.backurl',$backurl);
			//$this->setVar('handle.debug',PageScript::JSMake($message,$backurl));
			$this->_var['handle.is']=true;
			$re=true;
		}
		return $re;
	}
	public function isHandle(){return $this->_var['handle.is'];}
	public function toHandleStatReplace($re,$total=1)
	{
		$re=rd($re,'total',$total);
		$re=rd($re,'count',$total);
		return $re;
	}
}
?>