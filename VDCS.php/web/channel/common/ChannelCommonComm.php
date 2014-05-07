<?
class ChannelCommonComm extends ChannelCommonBase
{
	
	public function doLoad()
	{
		$page=query('page');
		if(!$page) $page='comm';
		$this->theme->setChannelDir('_page');
		$this->theme->setPage($page);

		//debugTree(utilFat::getFileTree('vdcs.web/manage/config/form/@part.dat'));
	}
	
	public function doParse()
	{
		global $_cfg,$dcs;
		//$test=new Tester();
		$_reload=query('reload');
		switch($_reload){
			case 'app':
				doCacheAppUpdate();
				break;
			case 'cache.config':
				$this->doReloadCache('config');
				break;
			case 'cache.data':
				$this->doReloadCache('data');
				break;
			case 'cache.themes':
				$this->doReloadCache('themes');
				break;
		}
		
		$_clear=query('clear');
		switch($_clear){
			case 'app':
				doCacheAppClear();
				break;
			case 'session':
				$dcs->client->doSessionClear();
				break;
			case 'cookies':
				$dcs->client->doCookiesClear();
				break;
		}
		
		$this->treeDTML->addItem('session.string',test::toTreeString($dcs->client->getSessionTree()));
		$this->treeDTML->addItem('cookies.string',test::toTreeString($dcs->client->getCookiesTree()));
		$_cfg_=$_cfg;
		unset($_cfg_['sys.db']);
		unset($_cfg_['sys.path']);
		$this->treeDTML->addItem('app.string',test::toAryString($_cfg_,'$_cfg'));
		
		$this->doTest();
	}
	
	public function doReloadCache($type)
	{
		$paths=appDirPath('data.cache/'.$type);
		//debugx($paths);
		//debugTable(utilFile::getDirTable($paths));
		utilFile::doDirDelFile($paths);
	}
	
	public function doTest()
	{
		$tmpContent=<<<DTML
gsg
{#config:forum("url.list","{\$forumid}==[item:forumid]")#}
sdfasdfa
{#config:article("url.view")#}
sdfasdfa
{#config:("url.view")#}
sdfasdfa
DTML;
		
		/*
		$n=preg_match_all(PATTERN_DTML_PRE_CONFIG,$tmpContent,$__matches);
		$this->treeDTML->addItem('test.data','n='.n);
		$this->treeDTML->addItem('test.data2',test::toAryString($__matches));
		*/
		$this->treeDTML->addItem('test.data2','test data2 content');
		
		//$this->e->addItem('测试提示信息');
		//if($this->e->isCheck()) { $ctl->doRaiseError(); }
		$this->treeDTML->addItem('test.data3','测试内容');
	}
}
?>