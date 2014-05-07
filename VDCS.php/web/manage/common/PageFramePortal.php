<?
class PageFramePortal extends ManagePortalBase
{
	public $tableNav,$tableMenu;
	
	public function doDestroy()
	{
		unset($this->tableNav,$this->tableMenu);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->theme->setMode('common');
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setDebug(false);
	}
	
	public function doLoad()
	{
		$this->page=queryx('page');
		if(!$this->page) $this->page=$GLOBALS['routes'][2];
		if(!$this->page) $this->page='welcome';
		//debugx($this->page);
		$this->channel=queryx('channel');
		if(!$this->channel) $this->channel=ManageCommon::cfg('channel.default');
		//debugx($this->channel);
		
		$this->theme->setChannel('frame');
		$this->theme->setPage('frame');
		if(ins('nav,menu',$this->page)>0) $this->theme->setModule($this->page);
	}
	
	public function doParse()
	{
		switch($this->page){
			case 'menu':
				$this->doParseMenu();
				break;
			case 'nav':
			default:
				$this->tableNav=$this->getNavTable();
				break;
		}
	}
	
	public function doThemeCache()
	{
		switch($this->page){
			case 'menu':
				$this->theme->doCacheFilterLoop('menu','this.tableMenu');
				break;
			case 'nav':
			default:
				$this->theme->doCacheFilterLoop('nav','this.tableNav');
				break;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	
	public function doParseMenu()
	{
		global $ctl;
		$this->tableMenu=newTable();		//$this->tableMenu=$this->getMenuTable();
		$this->tableMenu->setFields('type,name,url,config');
		//####################
		$ischannel=false;
		if(!$this->ruler->isPopedomCheck($this->channel,'')){
			$menuFile=appFilePath('manage.config/menu.nopopedom');
			if(!isFile($menuFile)) $menuFile=appFilePath('vdcs.mconfig/menu.nopopedom');
		}
		else{
			$treeChannel=$this->getChannelTree($this->channel);
			//debugTree($treeChannel);
			if($treeChannel->getCount()<1){
				$this->channel=ManageCommon::cfg('channel.default');
				$treeChannel=$this->getChannelTree($this->channel);
			}
			else{
				$ischannel=true;
				$this->loadChannel($this->channel);
				//debugTree($treeConfigure);
			}
			//$menuFile=appFilePath('manage.channel.config/menu');
			//$menuFile=rd($menuFile,'channel',$this->channel);
			$filename='menu';
			$menuFile=VDCSConfig::toPathsReal(ManageCommon::getPaths('config'),$filename,$this->channel,0);
			//##########
			$menuFileAt=r(appFilePath('manage.channel.config/'.$filename),EXT_XCML,'@defined'.EXT_XCML);
			$menuFileAt=rd($menuFileAt,'channel',$this->channel);
			if(isFile($menuFileAt)) $menuFile=$menuFileAt;
			//##########
		}
		//####################
		if(isTree($treeChannel)){
			$treeItem=newTree();
			$treeItem->addItem('type','bar');
			$treeItem->addItem('name',$treeChannel->getItem('name'));
			$this->tableMenu->addItem($treeItem);
			unset($treeItem);
		}
		//####################
		$menuXML=VDCSDTML::getConfigContent($menuFile);
		//debugvc($menuXML);
		$menuXML=r($menuXML,'{#ua}',APP_UA);
		$menuXML=r($menuXML,'{$url.root}',ManageCommon::getURL(''));
		$menuXML=r($menuXML,'{$url.main}',ManageCommon::getURL('main'));
		$menuXML=r($menuXML,'{$url.channel}',ManageCommon::getURL('portals'));
		if($ischannel){
			$menuXML=utilRegex::toDisplaceRegex($menuXML,CommonChannelExtend::getPreTree($this->channel),PATTERN_VAR);
			$menuXML=utilRegex::toReplacePre($menuXML,CommonChannelExtend::getPreTree($this->channel));
		}
		//debugx($menuXML);
		//####################
		$xmlMenu=new utilXCML();
		$xmlMenu->loadXML($menuXML);
		$xmlMenu->doParse();
		$urlInit=$xmlMenu->getConfigure('url.init');if(!$urlInit) $urlInit=$xmlMenu->getConfigure('url_init');
		$treeItem=newTree();
		$xmlMenu->doItemBegin();
		//debugx($this->config('frame.menu.ignores'));
		//doMenuParser(xmlMenu, re, tmpURLPortal, tmpFrame, tmpBars, tmpBar, tmpSub, tmpItem, tmpIcons, tmpSpace)
		for($i=0;$i<$xmlMenu->getItemLength();$i++){
			$treeConfig=utilString::toTree($xmlMenu->getItem('config'),';','=');
			//debugx($treeConfig->getItem('act'));
			if(inp($this->config('frame.menu.ignores'),$treeConfig->getItem('act'))<1 && $this->ruler->isPopedomCheck($this->channel,$treeConfig->getItem('act'))){
				$_type='';
				$_name=$xmlMenu->getItem('name');
				$_url=$this->toMenuURL($xmlMenu->getItem('url'));
				if(($_tn=ins(left($_name,5),'.'))>0){
					$_type=left($_name,$_tn-1);
					$_name=substr($_name,$_tn);
				}
				$treeItem->addItem('type',$_type);
				$treeItem->addItem('name',$_name);
				$treeItem->addItem('url',$_url);
				$treeItem->addItem('config','icon='.$treeConfig->getItem('icon').';style='.$treeConfig->getItem('style').'');
				$this->tableMenu->addItem($treeItem);
				
			}
			$xmlMenu->doItemMove();
		}
		$xmlMenu->doDestroy();
		/*
		'####################
		'####################
		tmpFile = dcs.common.toFilePath("manage.channel.config/menu@define")
		tmpFile = commons.rd(tmpFile, "channel", portals)    'channel
		'####################
		If commons.xFiles.isFile(tmpFile) Then
		tmpFileContent = dcs.getConfigContent(tmpFile)
		tmpFileContent = commons.toDisplaceRegex(tmpFileContent, tmpConfigurePreTree, commons.PATTERN_VAR)
		tmpFileContent = commons.toDisplaceRegex(tmpFileContent, tmpConfigurePreTree, commons.PATTERN_PRE)
		'##########
		tmpBars = ""
		'tmpFrames = tmpFrame
		re = re & tmpSpace
		Set xmlMenu = New utilXCML
		xmlMenu.loadXML (tmpFileContent)
		Call xmlMenu.doParse
		Call xmlMenu.doParseItem
		Call doMenuParser(xmlMenu, re, tmpURLPortal, tmpFrame, "", tmpBar, tmpSub, tmpItem, tmpIcons, tmpSpace)
		Call xmlMenu.doDestroy
		Set xmlMenu = Nothing
		End If
		*/
		unset($menuXML,$xmlMenu,$treeItem);
		//####################
		if($urlInit=='null') $urlInit='';
		else{
			if(len($urlInit)<1){
				if($this->channel) $urlInit=rd(ManageCommon::getURL('portals'),'channel',$this->channel);
				else $urlInit=ManageCommon::getURL('main');
			}
		}
		$ctl->addVar('url.init',$urlInit);
	}
	
	public function toMenuURL($url)
	{
		if(ins($url,';')<1 && ins($url,'=')<1) $url='url='.$url;
		$treeURL=utilString::toTree($url,';','=');
		$url=$treeURL->getItem('url');
		if(!$url){
			//$url=ManageCommon::getURL('portals');
			$channel=$treeURL->getItem('channel');
			if(!$channel) $channel=$treeURL->getItem('portals');
			if(!$channel) $channel=$this->channel;
			$url=ManageCommon::entryURL($channel,$treeURL->getItem('p'),$treeURL->getItem('m'),$treeURL->getItem('mi'),$treeURL->getItem('i'),$treeURL->getItem('x'),$treeURL->getItem('params'));
			/*
			$url=rd($url,'channel',$portals);
			if(len($treeURL->getItem('portal'))>0){
				$url=ManageCommon::toURL($url,'portal='.$treeURL->getItem('portal'));
			}
			if(len($treeURL->getItem('params'))>0){
				$url=ManageCommon::toURL($url,$treeURL->getItem('params'));
			}
			*/
		}
		return $url;
	}
	
	
	public function getChannelTree($channel)
	{
		$reTree=new utilTree();
		if(inp($this->cfg('frame.nav.before').','.$this->cfg('frame.nav.after'),$channel)>0){
			$reTree->addItem('channel',$channel);
			$tmpName=$this->cfg('lang.nav.'.$channel);
			$reTree->addItem('name',$tmpName);
			$reTree->addItem('names',$tmpName);
		}
		else{
			$this->loadChannelStruct();
			$this->tableChannelStruct->doItemBegin();
			for($i=0;$i<$this->tableChannelStruct->getRow();$i++){
				if($this->tableChannelStruct->getItemValue('channel')==$channel){
					$reTree=$this->tableChannelStruct->getItemTree();
					//tmpTrue=true;
					break;
				}
				$this->tableChannelStruct->doItemMove();
			}
		}
		return $reTree;
	}
	
	public function getNavTable()
	{
		$reTable=new utilTable();
		$reTable->setFields('channel,name,names');
		$tmpTree=new utilTree();
		$navIgnores=$this->cfg('frame.nav.ignores');
		//----------
		$tmpAry=toSplit($this->cfg('frame.nav.before'),',');
		for($i=0;$i<count($tmpAry);$i++){
			$tmpChannel=$tmpAry[$i];
			if(inp($navIgnores,$tmpChannel)<1 && $this->ruler->isPopedomCheck($tmpChannel,'')){
				$tmpName=$this->cfg('lang.nav.'.$tmpChannel);
				if(len($tmpName)<1) $tmpName='['.$tmpChannel.']';
				$tmpTree->addItem('channel',$tmpChannel);
				$tmpTree->addItem('name',$tmpName);
				$reTable->addItem($tmpTree);
			}
		}
		//----------
		$this->loadChannelStruct();
		$this->tableChannelStruct->doBegin();
		while($this->tableChannelStruct->isNext()){
			$tmpChannel=$this->tableChannelStruct->getItemValue('channel');
			if(inp($navIgnores,$tmpChannel)<1 && $this->ruler->isPopedomCheck($tmpChannel,'')){
				$tmpName=$this->tableChannelStruct->getItemValue('name');
				if(len($tmpChannel)>0 && len($tmpName)>0){
					$tmpTree->addItem('channel',$tmpChannel);
					$tmpTree->addItem('name',$tmpName);
					$reTable->addItem($tmpTree);
				}
			}
		}
		//----------
		$tmpAry=toSplit($this->cfg('frame.nav.after'),',');
		for($i=0;$i<count($tmpAry);$i++){
			$tmpChannel=$tmpAry[$i];
			if(inp($navIgnores,$tmpChannel)<1 && $this->ruler->isPopedomCheck($tmpChannel,'')){
				$tmpName=$this->cfg('lang.nav.'.$tmpChannel);
				if(len($tmpName)<1) $tmpName='['.$tmpChannel.']';
				$tmpTree->addItem('channel',$tmpChannel);
				$tmpTree->addItem('name',$tmpName);
				$reTable->addItem($tmpTree);
			}
		}
		//----------
		return $reTable;
	}
}
?>