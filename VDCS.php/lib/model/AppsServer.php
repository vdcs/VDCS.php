<?
class AppsServer
{
	public static $appid='';

	public static function _getConfigTree($node='config')
	{
		static $trees;
		if(!$trees) $trees=VDCSDTML::getConfigxTree('common.config/apps');
		return $appid ? $trees->getFilterTree($appid.'.') : $trees;
	}
	public static function getConfig($key){return self::_getConfigTree()->getItem($key);}
	public static function getKey($auth='')
	{
		$re=self::getConfig('key_'.$auth);
		if(!$re) $re=self::getConfig('key');
		return $re;
	}
	
	public static function getSiteTree($appid)
	{
		static $trees;
		if(!$trees){
			$trees=VDCSDTML::getConfigxTree('common.config/apps.site');
		}
		return $appid ? $trees->getFilterTree($appid.'.') : $trees;
		return self::getConfigTree($appid);
	}
	public static function getSiteConfig($appid,$key){return self::getSiteTree($appid)->getItem($key);}
	public static function getSiteKey($appid,$auth='')
	{
		$re=self::getSiteConfig($appid,'api_key_'.$auth);
		if(!$re) $re=self::getSiteConfig($appid,'api_key');
		return $re;
	}


	/*
	########################################
	########################################
	*/
	public static function request($app,$opt,$params,$auth=null)
	{
		if(isTree($app)){
			$treeApp=$app;
			$appid=$treeApp->getItem('id');
		}
		else{
			$appid=$app;
			$treeApp=self::getConfigTree($appid);
		}
		//debugTree($treeApp);
		$ret=array();
		$opt=AppsCommon::opt($opt);
		$opt['auth']=is_null($auth)?'interface':'';
		if(!$opt['api']){
			$ret['status']='noapi';
			return $ret;
		}
		$url=self::urlBuild($treeApp,$opt,$params);
		$ret['query_string']=queryString();
		$ret['api_url']=$url;
		debugxx('api_url='.$url);
		if(query('debug')=='apps') debugx($url);
		$ret['results']=VDCSHTTP::request($url);
		if(len($ret['results'])<1){
			$ret['status']='noresult';
			return;
		}
		$ret['status']='succeed';
		return $ret;
	}
	public static function requestParser($app,$opt,$params,$auth=null)
	{
		$ret=self::request($app,$opt,$params,$auth);
		if(query('debug')=='apps') debugvc($ret['results']);
		if($ret['status']!='succeed'){
			return $ret;
		}
		$isxml=substr($ret['results'],0,5)=='<'.'?xml'?true:false;
		if($isxml){
			$ret['api.maps']=getXCML2Map($ret['results']);
			$ret['api.var']=$ret['api.maps']->getItemTree('var');
			$ret['api.item']=$ret['api.maps']->getItemTable('item');
		}
		else{
			$treeVar=newTree();
			$treeVar->addItem('status','request');
			$treeVar->addItem('message','request bad.');
			$ret['api.var']=$treeVar;
		}
		$ret['var.status']=$ret['api.var']->getItem('status');
		return $ret;
	}
	public static function parser($app,$opt,$params,$auth=null){return self::requestParser($app,$opt,$params,$auth);}
	
	public static function __interfaceParse($app,$opt,$params)
	{
		return self::requestParser($app,$opt,$params,'interface');
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function urlBuild($treeApp,$opt,$params)
	{
		if(!$opt['auth']) $opt['auth']='auth';
		if(!isa($opt) || !$opt['api']) return '';
		$url=self::urlFilter($treeApp,$opt);
		if($opt['auth']=='interface'){
			$auth_tim=DCS::timer();
			if(!($api_key=$treeApp->getItem('api_key_'.$opt['auth']))) $api_key=$treeApp->getItem('api_key');
			//debugxx($auth_tim.','.$api_key);
			$auth_token=utilCoder::toMD5($auth_tim.','.$api_key);
			$url=DCS::urlLink($url,'auth_port=app&auth_token='.$auth_token.'&auth_tim='.$auth_tim.'');
		}
		$url=DCS::urlLink($url,$params);
		return $url;
	}
	public static function urlFilter($treeApp,$opt)
	{
		$url='';
		$urls=$treeApp->getItem('api_urls');
		if($urls){
			$treeURL=utilString::toTree($urls,';','=');
			//debugTree($treeURL);
			$url=$treeURL->getItem($opt['api']);
		}
		//$opt['auth']
		if(!$url) $url=$treeApp->getItem('api_url');
		$url=rv($url,'api',$opt['api']);
		if(!DCS::isURL($url)) $url=$treeApp->getItem('api_host').$url;
		return $url;
	}

}
