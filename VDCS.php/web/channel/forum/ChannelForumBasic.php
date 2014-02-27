<?
class ChannelForumBasic extends WebPortalBase
{
	const ClassIDDel=999999999;
	public $classid=-1;
	public $tableClass,$treeClass;
	
	public function __construct(){}
	public function __destruct()
	{
		unsetr($this->tableClass,$this->treeClass);
	}
	
	
	/*
	########################################
	########################################
	*/
	/*
	public function initer()
	{
		//debugTable($this->tableClass);
	}
	*/
	public function doAuth()
	{
		parent::doAuth();
		//$this->initUser();
	}
	
	public function initBasic()
	{
		//$this->cfg->setTitle('chn',$cfg->v('title'),$cfg->url('index'));
		
		$this->initClass();
	}
	
	public function doThemeCacheBasic()
	{
		//debugTable($this->tableClass);
		//debugTree($this->treeClass);
		$this->theme->doCacheFilterLoop('allclass','cpo.tableClass');
		$this->theme->doCacheFilterTree('class','cpo.treeClass');
		if($this->userc){
			$this->theme->output=$this->userc->toDTMLCache($this->theme->output,'cpo');
		}
	}
	
	
	/*
	####################################
	####################################
	*/
	public function initClass()
	{
		global $cfg;
		if($this->isClassInit) return;$this->isClassInit=true;
		$channel=$cfg->getChannel();
		$arys=VDCSCache::getCache('channel.'.$channel.'.class','config',false);
		if(isAry($arys)){
			$this->tableClass=newTable();
			$this->tableClass->setArray($arys);
			return;
		}
		$sql='SELECT * From dbs_class where status=1 and channel="forum" order by rootid asc,orderid asc,id asc';
		$this->tableClass=DB::queryTable($sql);
		//if(!$reTable->isObj()){ $reTable->setFields('id,topic,total_view'); }
		ModelClassExtend::doTableFilter($this->tableClass,$channel);
		$this->doFilterLast($this->tableClass);
		//debugTable($this->tableClass);
		VDCSCache::setCache('channel.'.$channel.'.class',$this->tableClass->getArray(),'config');
	}
	
	protected function doFilterLast(&$tableData)
	{
		global $cfg;
		$tableData->doAppendFields('reply_by,reply_tim');
		$tableData->doBegin();
		while($tableData->isNext()){
			$classid=$tableData->getItemValueInt('classid');
			$sql='select * from '.$cfg->vp('topic:table.name').' where classid='.$classid.' order by t_reply_tim desc limit 1';
			$tree=DB::queryTree($sql);
			$reply_by=$tree->getItem('t_reply_by');
			$reply_tim=$tree->getItem('t_reply_tim');
			if($reply_tim) $time=datei('Y-m-d',$reply_tim);
			else $time='0000-00-00';
			$tableData->setItemValue('reply_by',$reply_by);
			$tableData->setItemValue('reply_tim',$time);
		}
		UaExtend::appendInfo($tableData,'reply_by');
	}
	
	public function loadClass($check=0)
	{
		global $cfg;
		if($this->classid<0) $this->classid=queryi('classid');
		$this->treeClass=$this->getClassTree($this->classid);
		if($this->treeClass->getCount()>0){
			$this->_isClass=true;
			$this->theme->setPre('classid',$this->classid);
			$cfg->setTitle('class',$this->getClass('name'),$this->getClass('linkurl'));
		}
		else{
			if($check) $this->doError('class');
		}
	}
	public function isClass(){return $this->_isClass;}
	public function getClass($k){return $this->treeClass->getItem($k);}
	
	
	public function getClassTree($id)
	{
		return $this->tableClass->getFieldItem('classid','=',$id);
	}
	public function getClassSubTable($id)
	{
		return ModelClassExtend::toTable($this->tableClass,$id);
	}
	
	
	/*
	####################################
	####################################
	*/
	public function initUser()
	{
		$this->userc=new ForumUser();
		$this->userc->doInit();
	}
	
	
	/*
	####################################
	####################################
	*/
	public function doError($key,$params='')
	{
		$url=$this->cfg->toLinkURL('message');
		$url=urlLink($url,'key='.$key);
		$url=urlLink($url,$params);
		go($url);
	}
	
}
?>