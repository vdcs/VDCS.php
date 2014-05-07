<?
define('FILE_EXTEND_DEFINE',			'@define');

class CommonConfig
{
	const TABLES_CONFIG			= 'dbs_config';
	
	protected $_isCache=false;
	protected $_configs=array();
	
	protected $_channel;
	public $chn;
	public $clas=null,$spec=null;
	
	protected $_isClass=false;
	protected $tableChannelClass=null,$treeChannelClass=null;
	
	protected $aryLocation,$aryTitle;
	protected $_web=array();
	protected $_isPage,$_isNewday,$_isLimit,$_isOnlineUpdate;
	
	
	public function __construct()
	{
		$this->_isCache=true;
		$this->_isPage=true;
		$this->_isNewday=false;
		$this->_isLimit=false;
		$this->_isOnlineUpdate=true;
	}
	public function __destruct()
	{
		unset($this->_configs,$this->_web);
		unset($this->clas,$this->spec);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setCache($b) { $this->_isCache=$b; }
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		//$this->loadConfig();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initConfigs($sort='data'){VDCSCache::delCache('config.'.$sort);}
	public function loadConfigs($sort='data')
	{
		if(!$this->_configs[$sort]){	//isTree(
			$ary=VDCSCache::getArray('config.'.$sort,'data',false);
			if(isa($ary)){
				$this->_configs[$sort]=new utilTree();
				$this->_configs[$sort]->setArray($ary);
			}
			else{
				$this->_configs[$sort]=DB::getQueryTrees('!select `key`,`value` from '.self::TABLES_CONFIG.' where `sort`=\''.$sort.'\'');
				VDCSCache::setAry('config.'.$sort,$this->_configs[$sort]->getArray(),'data');
			}
		}
	}
	public function doConfigsUpdate($k,$v,$sort='data')
	{
		$isItem=true;
		$this->loadConfigs($sort);
		if(!$this->_configs[$sort]){	//isTree(
			if(!$this->_configs[$sort]->isItem($k)) $isItem=false;
			$this->_configs[$sort]->addItem($k,$v);
			VDCSCache::setAry('config.'.$sort,$this->_configs[$sort]->getArray(),'data');
		}
		if($isItem){
			$sql='update '.self::TABLES_CONFIG.' set `value`=\''.utilCode::toSQL($v).'\' where `sort`=\''.$sort.'\' and `key`=\''.$k.'\'';
		}
		else{
			$sql='insert into '.self::TABLES_CONFIG.'(`id`,`sort`,`key`,`value`) values(1,\''.$sort.'\',\''.$k.'\',\''.utilCode::toSQL($v).'\')';
		}
		//debugx(($sql);
		DB::exec($sql);
	}
	public function doConfigsUpdateBatch($s,$sort='data')
	{
		$treeBatch=utilString::toTree($s,BATCH_SYMBOL,'=');
		$treeBatch->doBegin();
		for($t=0;$t<$treeBatch->getCount();$i++){
			$this->doConfigsUpdate($treeBatch->getItemKey(),$treeBatch->getItemValue(),$sort);
			$treeBatch->doMove();
		}
	}
	
	
	public function getData($k,$sort='data'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItem($k);}
	public function getDataInt($k,$sort='data'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItemInt($k);}
	public function getDataNum($k,$sort='data'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItemNum($k);}
	
	public function setData($k,$v,$sort='data'){$this->doConfigsUpdate($k,$v,$sort);}
	public function setDataBatch($s,$sort='data'){$this->doConfigsUpdateBatch($s,$sort);}
	
	
	public function getDat($k,$sort='dat'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItem($k);}
	public function getDatInt($k,$sort='dat'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItemInt($k);}
	public function getDatNum($k,$sort='dat'){$this->loadConfigs($sort);return $this->_configs[$sort]->getItemNum($k);}
	
	public function setDat($k,$v,$sort='dat'){$this->doConfigsUpdate($k,$v,$sort);}
	public function setDatBatch($s,$sort='dat'){$this->doConfigsUpdateBatch($s,$sort);}
	
	
	/*
	########################################
	########################################
	*/
	public function getConfigure($k,$sort='config',$file='data/config')
	{
		$keys='configure.'.$sort;
		if(!$this->_configs[$keys]){	//isTree(
			$this->_configs[$keys]=VDCSDTML::getConfigTree('common.config/'.$file);
		}
		return $this->_configs[$keys]->getItem($k);
	}
	public function getLang($k){return $this->getConfigure($k,'lang','data/language');}
	public function getConfig($k){return $this->getConfigure($k,'config','data/config');}
	
	
	/*
	########################################
	########################################
	########################################
	########################################
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function doClassInit($channel_='')
	{
		if($this->clas) return;
		if(!$channel_) $channel_=$this->_channel;
		$this->clas=new ModelClass();
		$this->clas->setChannel($channel_);
		$this->clas->setUse(true);
		$this->clas->setCache($this->_isCache);
		$this->clas->doInit();
	}
	
	public function doSpecialInit($channel_='')
	{
		if($this->spec) return;
		if(!$channel_) $channel_=$this->_channel;
		$this->spec=new ModelSpecial();
		$this->spec->setChannel($channel_);
		$this->spec->setUse(true);
		$this->spec->setCache($this->_isCache);
		$this->spec->doInit();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doChannelInit()
	{
		$this->chn=$this->getChannelStruct();
	}
	
	public function setChannel($s) { $this->_channel=$s; }
	public function getChannel() { return $this->_channel; }
	
	public function getPath($s) { return $this->getChannelPath($this->_channel,$s); }
	public function getTree($s) { return $this->getChannelTree($this->_channel,$s); }
	public function getTable($s) { return $this->getChannelTable($this->_channel,$s); }
	
	public function getConfigTree() { return $this->chn->getConfigTree(); }
	public function getConfigValue($k) { return $this->chn->getConfigValue($k); }
	public function vp($k) { return $this->chn->vp($k); }
	public function v($k) { return $this->chn->v($k); }
	public function num($k) { return $this->chn->num($k); }
	public function cnum($k) { return $this->chn->cnum($k); }
	public function cfg($k) { return $this->chn->cfg($k); }
	public function cfgi($k) { return $this->chn->cfgi($k); }
	public function cfgn($k) { return $this->chn->cfgn($k); }
	public function url($k) { return $this->chn->url($k); }
	
	public function toLinkURL($page,$items=''){return $this->chn->toLinkURL($page,$items);}
	
	
	//######################################
	public function getLinkURL($channel,$page,$items='')
	{
		if(!$channel) $channel=$this->_channel;
		if(ins($page,'{$')>0) $re=$page;
		else $re=$this->getChannelTree($channel,'configure')->getItem('url.'.$page);
		$re=self::toURLItems($re,$items);
		return $re;
	}
	
	public static function toURLItems($re,$items='')
	{
		if($items){
			$arys=utilString::toArray($items,'&','=');
			foreach($arys as $k=>$v){
				if(ins($re,'{$'.$k.'}')>0) $re=rd($re,$k,$v);
				else $re=urlLink($re,$k.'='.$v);
			}
			$re=utilRegex::toReplaceVar($re,newTree());
			//$re=utilRegex::toReplaceVar($re,utilString::toTree($items,'&','='));
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getChannelPath($channel,$file='configure')
	{
		!$channel && $channel=$this->_channel;
		return appFilePath('common.channel/'.$channel.'/'.$file);
	}
	public function getChannelTree($channel,$file='configure',$isCache=true)
	{
		!$channel && $channel=$this->_channel;
		!$file && $file='configure';
		return CommonChannelExtend::getTree($channel,$file,$isCache);
	}
	public function getChannelTable($channel,$file='configure')
	{
		!$channel && $channel=$this->_channel;
		if(!isset($file{0})) $file='configure';
		return VDCSDTML::getConfigCacheTable('common.channel/'.$channel.'/'.$file);
	}
	public function getChannelValue($channel,$file,$k) { return $this->getChannelTree($channel,$file)->getItem($k); }
	public function getChannelConfigure($channel,$k) { return $this->getChannelTree($channel,'configure')->getItem($k); }
	
	public function getChannelStruct($channel=null)
	{
		!$channel && $channel=$this->_channel;
		$chn=new CommonConfigChannel();
		$chn->setChannel($channel);
		$chn->doInit($this->getChannelTree($channel,'configure'));
		return $chn;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setLocation($k,$v){$this->_web['location.'.$k]=$v;}
	public function setWeb($k,$v) { $this->_web[$k]=$v; }
	public function getWeb($k) { return $this->_web[$k]; }
	public function getWebs()
	{
		$this->_web['nav.title']=$this->getTitles();
		$this->_web['nav.title.seo']=$this->getTitles('seo');
		$this->_web['nav.title.values']=$this->getTitles('values');
		$this->_web['nav.title.test']=$this->_web['nav.title.values'];
		return $this->_web;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setTitle($k,$tit=null,$url=null)
	{
		if(is_null($tit)){$tit=$k;$k='';}
		$kurl=$k.'url';
		$this->aryTitle['title.'.$k]=array(utilCode::toHTML($tit),$url);
		$this->aryTitle['title'.$k]=$this->aryTitle['title.'.$k];
		//debugx($k.','.$tit.','.$kurl);
		if($tit){
			$this->setWeb('var.title'.$k,$tit);
			$this->setWeb('var.title.'.$k,$tit);
			$this->setWeb('var.'.$k.'title',$tit);
		}
		//debuga($this->aryTitle);
		$this->setWeb('var.'.$kurl,$url);
	}
	public function getTitle($k='',$t=0)
	{
		if($t==1) return '<a href="'.$this->aryTitle[$k][1].'">'.$this->aryTitle[$k][0].'</a>';
		else return $this->aryTitle[$k][0];
	}
	public function getTitles($re='',$spc=' ')
	{
		if(!$re) $re='{title.chn} {title.portal} {title.module} {title.mi} {title.class} {title.base} {title.page} {title} {title.action} {title.sub} {title.sub2} {title.sub3}';
		if($re=='seo') $re='{title.sub3} {title.sub2} {title.sub} {title} {title.base} {title.class} {title.mi} {title.module} {title.portal} {title.chn} {title.page}';
		else if($re=='test' || $re=='values') $re='title.chn={title.chn};title.p={title.p};title.portal={title.portal};title.m={title.m};title.module={title.module};title.mi={title.mi};title.class={title.class};title.base={title.base};title.page={title.page};title={title};title.action={title.action};title.sub={title.sub};title.sub2={title.sub2};title.sub3={title.sub3}';
		$re=preg_replace('/{'.PATTERN_FLAG_VAR.'}/ies','\$this->getTitle(\'$1\')',$re);
		if(!$spc) $spc=' ';
		$b=true;
		while($b){
			$re=r($re,$spc.$spc,$spc);
			if(ins($re,$spc.$spc)<1) $b=false;
		}
		//debugx(t($re));
		return t($re);
	}
	
}


class CommonConfigChannel
{
	private $_channel;
	private $treeConfig=null,$treeSQL=null;
	
	public function __construct()
	{
		$this->treeConfig=new utilTree();
	}
	public function __destruct()
	{
		unset($this->treeConfig,$this->treeSQL);
	}
	
	
	public function setChannel($s) { $this->_channel=$s; }
	public function getChannel() { return $this->_channel; }
	
	
	public function doInit($tConfig=null)
	{
		if($tConfig) $this->treeConfig=$tConfig;
	}
	
	public function getConfigTree() { return $this->treeConfig; }
	public function getConfigValue($k) { return $this->treeConfig->getItem($k); }
	public function vp($k) { return $this->treeConfig->getItem('pre.'.$k); }
	public function v($k) { return $this->treeConfig->getItem('var.'.$k); }
	public function num($k) { return $this->treeConfig->getItemNum('num.'.$k); }
	public function cnum($k) { return $this->treeConfig->getItemNum('num.'.$k); }
	public function cfg($k) { return $this->treeConfig->getItem('config.'.$k); }
	public function cfgi($k) { return $this->treeConfig->getItemInt('config.'.$k); }
	public function cfgn($k) { return $this->treeConfig->getItemNum('config.'.$k); }
	public function url($k) { return $this->treeConfig->getItem('url.'.$k); }
	
	public function toLinkURL($page,$items='')
	{
		$re=$this->treeConfig->getItem('url.'.$page);
		$re=CommonConfig::toURLItems($re,$items);
		return $re;
	}
	
	//######################################
	public function initSQL(){if($this->treeSQL==null)$this->treeSQL=CommonChannelExtend::getSQLTree($this->_channel);}
	public function getSQLTree(){$this->initSQL();return $this->treeSQL; }
	
	public function isSQLValue($k){$this->initSQL();return $this->treeSQL->isItem($k);}
	public function getSQLValue($k) {$this->initSQL();return $this->treeSQL->getItem($k); }
	public function isSQLStruct($k){$this->initSQL();return $this->treeSQL->isItem('struct.'.$k);}
	public function getSQLStruct($k) {$this->initSQL();return $this->treeSQL->getItem('struct.'.$k); }	//$this->_channel.'.'
	
	public function toSQLParse($sql)
	{
		$sql=rd($sql,'table.name',$this->getSQLStruct('table.name'));
		$sql=rd($sql,'table.px',$this->getSQLStruct('table.px'));
		$sql=rd($sql,'tpx',$this->getSQLStruct('table.px'));
		$sql=rd($sql,'tim.num',DCS::timer());
		return $sql;
	}
	
}
?>