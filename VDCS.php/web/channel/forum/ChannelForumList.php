<?
class ChannelForumList extends ChannelForumBase
{
	public $tableList,$tableListTop;
	public $tableClassSub;
	public $s,$p;
	protected $_pagenum=5,$_listnum=20;
	
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableList,$this->tableListTop);
		unsetr($this->tableClassSub);
	}
	
	public function setNum($pagenum,$listnum)
	{
	    if($pagenum>0) $this->_pagenum=$pagenum;
	    if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		global $cfg;
		$this->loadClass();
		
		$this->TopicTableName=$cfg->vp('topic:table.name');
		$this->TopicTablePX=$cfg->vp('topic:table.px');
		
	}
	
	public function doParse()
	{
		global $cfg;
		if(!$this->isClass()) return;
		//$theme->setModule('class');
		
		//$this->doParseTopTopic();
		
		$this->_var['url']=$cfg->getConfigValue('url.list.page');
		$this->_var['url']=rd($this->_var['url'],'classid',$this->classid);
		//$this->_var['query']=$cfg->chn->getSQLStruct('list.query');
		$this->_var['query']='';
		$this->_var['query']=DB::sqla($this->_var['query'],'classid='.$this->classid,'');
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->TopicTableName);
		$this->p->setDB('id','t_id');
		$this->p->setDB('field','*');
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order','orderid desc,t_tim desc');
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->TopicTablePX);
		$this->doListFilter($this->tableList);
		//debugTable($this->tableList);
	}
	
	public function doParseTopTopic()
	{
		global $cfg;
		$arys=VDCSCache::getCache('channel.'.$cfg->getChannel().'.topic.top','config',false);
		if(isAry($arys)){
			$this->tableListTop=newTable();
			$this->tableListTop->setArray($arys);
			return;
		}
		
		$tmpTablePx = $cfg->chn->getSQLStruct('topic:table.px');
		$sql='SELECT '.$cfg->chn->getSQLStruct('list.fields').' From '.$cfg->chn->getSQLStruct('topic:table.name').' where '.$tmpTablePx.'istop>0 order by '.$tmpTablePx.'istop desc,'.$tmpTablePx.'id desc';
		$this->tableListTop=DB::queryTable($sql);
		if(!$this->tableListTop->isObj()){
			$this->tableListTop->setFields('id,topic,total_view,total_reply');
		}else{
			$this->tableListTop->doFilter($tmpTablePx,'');
			$this->doListFilter($this->tableListTop);
			//debugTable($this->tableListTop);
		}
		VDCSCache::setCache('channel.'.$cfg->getChannel().'.topic.top',$this->tableListTop->getArray(),'config');
	}
	
	protected function doListFilter(&$tableData)
	{
		global $cfg;
		if($tableData->getRow()<1) return;
		$linkurl=$cfg->toLinkURL('view');
		//##########
		/*
		$uids=$tableData->getValues('uuid').','.$tableData->getValues('last_uid');
		$treeUsers=$this->userc->getNameTree($uids);
		*/
		//##########
		UaExtend::appendInfo($tableData,'uid');
		$lastUsers=newTable();
		$last_uids=$tableData->getValues('reply_by');
		if($last_uids) $lastUsers=DB::queryTable('select names,uid from db_user where uid in('.$last_uids.')' );
		
		$tableData->doAppendFields('linkurl,uname,last_uname');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('linkurl',rd($linkurl,'id',$tableData->getItemValue('id')));
			$reply_by=$tableData->getItemValue('reply_by');
			$last_uname=$lastUsers->getTermsValue('uid='.$reply_by,'names');
			if(!$last_uname) $last_uname='暂无帖子';
			$tableData->setItemValue('last_uname',$last_uname);
			//$rootid=$tableData->getItemValue('id');
			/*
			$uid=$tableData->getItemValue('uuid');
			$tableData->setItemValue('uname',$treeUsers->getItem($uid));
			$lastid=$tableData->getItemValueInt('last_uid');
			if($lastid<1) $lastid=$uid;
			$tableData->setItemValue('last_uname',$treeUsers->getItem($lastid));
			*/
		}
		//unsetr($treeUsers);
	}
	
	public function doThemeCache()
	{
		global $cfg,$theme;
		//debugTable($this->tableList);
		$theme->doCacheFilterLoop('subclass','cpo.tableClassSub');
		$theme->doCacheFilterLoop('toplist','cpo.tableListTop');
		$theme->doCacheFilterLoop('list','cpo.tableList');
		$theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
?>