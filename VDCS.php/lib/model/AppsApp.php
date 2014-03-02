<?
class AppsApp
{
	
	public static function _getConfigTree($node='config')
	{
		static $trees;
		if(!$trees) $trees=VDCSDTML::getConfigxTree('common.config/apps.app');
		return $node ? $trees->getFilterTree($node.'.') : $trees;
	}
	public static function getConfig($key){return self::_getConfigTree()->getItem($key);}
	public static function getKey($auth='')
	{
		$re=self::getConfig('api_'.$auth.'_key');
		if(!$re) $re=self::getConfig('api_key');
		return $re;
	}
	public static function getAppid(){return self::getConfig('appid');}
	
	public static function getServerConfig($key,$server='server')
	{
		if(!$server) $server='server';
		return self::_getConfigTree($server)->isItem($key)?self::_getConfigTree($server)->getItem($key):self::getConfig($key);
	}
	public static function getServerKey($auth='',$server='')
	{
		$re=self::getServerConfig('api_'.$auth.'_key',$server);
		if(!$re) $re=self::getServerConfig('api_key',$server);
		return $re;
	}


	/*
	########################################
	########################################
	*/
	public static function request($opt,$params,$auth=null)
	{
		$ret=array();
		$opt=AppsCommon::opt($opt);
		$opt['auth']=is_null($auth)?'interface':'';
		if(!$opt['api']){
			$ret['status']='noapi';
			return $ret;
		}
		$url=self::urlBuild($opt,$params);
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
	public static function requestParser($opt,$params,$auth=null)
	{
		$ret=self::request($opt,$params,$auth);
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
	public static function parser($opt,$params,$auth=null){return self::requestParser($opt,$params,$auth);}
	

	//authorizer
	public static function tokenAccess()
	{
		return utilCoder::toMD5i(utilCode::getRand(10));
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function urlBuild($opt,$params)
	{
		$opt=AppsCommon::opt($opt);
		if(!isa($opt) || !$opt['api']) return '';
		$url=self::urlFilter($opt);
		$url=DCS::urlLink($url,'appid='.self::getServerConfig('appid',$opt['server']));
		if($opt['auth']=='interface'){
			$auth_tim=DCS::timer();
			$api_key=self::getServerKey($opt['app'],$opt['server']);
			//debugxx($auth_tim.','.$api_key);
			$auth_token=utilCoder::toMD5($auth_tim.','.$api_key);
			$url=DCS::urlLink($url,'auth_token='.$auth_token.'&auth_tim='.$auth_tim.'');
		}
		$url=r($url,'{x}',self::getServerConfig('api_x',$opt['server']));
		$url=DCS::urlLink($url,$params);
		return $url;
	}
	
	public static function urlFilter($opt)
	{
		$url='';
		$urls=self::getServerConfig('api_urls',$opt['server']);
		if($urls){
			$treeURL=utilString::toTree($urls,';','=');
			//debugTree($treeURL);
			$url=$treeURL->getItem($opt['api']);
		}
		//$opt['auth']
		if(!$url) $url=self::getServerConfig('api_url',$opt['server']);
		$url=rv($url,'api',$opt['api']);
		if(!DCS::isURL($url)) $url=self::getServerConfig('api_host',$opt['server']).$url;
		return $url;
	}
	
}
