<?
class ChannelForumSearchX extends ChannelForumBaseX
{
	public $s,$p;
	public $tableList,$tableListTop;
	protected $_pagenum=5,$_listnum=20;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->tableList);
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
		
	}
	
	
	##############################################
	##############################################
	public function doParse()
	{
		$this->cfg->setPage(false);
		$this->doParsePage();
	}
	
	public function doParsePage()
	{
		$this->_listnum=queryi('listnum');
		if (toInt($this->_listnum)>100 || toInt($this->_listnum)<5) $this->_listnum=0;
		if (toInt($this->_listnum)<1) $this->_listnum=10;
		
		$uid=0;
		$uname=query('uname');
		$reuname=query('re_uname');
		$dataname = $this->ChannelPreTree->getItem('data:table.name');
		
		if (utilCheck::isName($uname)) $uname='';
		if (len($uname)>0) $uid=$this->ua->queryField('id','name='.DB::q($uname,1));
		
		if (toInt($this->forumid)<1){
			$tmpQuery="classid<>" . $this->DeleteForumid;
		}else{
			$tmpQuery="classid=" . $this->forumid;
		}
		
		$tmpID='t_id';
		$tmpOrder='desc';
		$tmpOrders='';
		
		switch($this->_m_){
		case 'search':
			$s=new libSearch();
			$s->doInit();
			$s->setField('t_topic');
			$s->setFields('t_topic=6');
			$s->doParse();
			$tmpQuery=$s->toAppendQuery($tmpQuery,'');
			
			$tmpQueryAppend='';
			if (toInt($this->ua->id)>0){
				$tmpQueryAppend='uid=' . $this->ua->id;
			}else{
				if (len($reuname)>0){
					if (utilCheck::isName($reuname)) $reuname='';
					if (len($reuname)>0) $uid=$this->ua->queryField('id','name='.DB::q($reuname,1));
					$tmpQueryAppend='t_id in (select rootid from ' . $dataname . ' where d_isroot=0 and uid=' . $this->ua->id .')';
				}
			}
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'order':
			$tmpID='orderid';
			$tmpOrder='desc';
			break;
		case 'hot':
			$tmpQueryAppend='t_total_reply>20';
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'good':
			$tmpQueryAppend='t_isgood=1';
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'vote':
			//$tmpQueryAppend='t_isvote=1'
			//$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'top':
			$tmpQueryAppend='t_istop>0';
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'my':
			$tmpQueryAppend='uid=' . $this->ua->id;
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		case 'myreply':
			$tmpQueryAppend='t_id in (select rootid from ' . $dataname . ' where d_isroot=0 and uid=' . $this->ua->id . ')';
			$tmpQuery=DB::sqla($tmpQuery,$tmpQueryAppend,'');
			break;
		default:
			$tmpID='t_id';
			$tmpOrder='desc';
			$tmpOrders='';
			break;
		}
		
		//$tmpURL=$cfg->getConfigValue('url.list.page');
		//$tmpURL=rd($tmpURL,'forumid',$this->forumid);
		$tmpURL='?portal='.$this->portal.'&format='.$this->format.'&module='.$this->_m_.'&';
		
		$this->_var['query']=DB::toSQLAppend($this->_var['query'],'classid='.$this->forumid,'');
		
		$statQuery=DB::getTotal();
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$tmpURL);
		$this->p->setDB('table',$this->cfg->chn->getSQLStruct('topic:table.name'));
		$this->p->setDB('id',$tmpID);
		$this->p->setDB('field',$this->cfg->chn->getSQLStruct('list.fields'));
		$this->p->setDB('query',$tmpQuery);
		$this->p->setDB('order',$tmpID .' '. $tmpOrder);
		$this->p->setDB('orders',$cfg->chn->getSQLStruct('list.orders'));
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->ChannelPreTree->getItem('topic:table.px'),'');
		
		$this->doAppenduid($this->tableList,1);
		$this->doTableParse($this->tableList);
		
		$this->addVar('paging.listnum',$this->p->getListNum());
		$this->addVar('paging.numend',$this->p->getNumEnd());
		$this->addVar('paging.total',$this->p->getTotal());
		$this->addVar('paging.pagenum',$this->p->getPageNum());
		$this->addVar('paging.page',$this->p->getPage());
		$this->addVar('paging.pagetotal',$this->p->getPageTotal());
		$this->addVar('paging.pagebase',$this->p->getPageBase());
		
		$this->addVar('_query.sql',$this->p->getSQL('query'));
		$this->addVar('_stat.exectime',dcsExecTime());
		$this->addVar('_stat.query',DB::getTotal());
		$this->setTable($this->tableList);
	}
	
	###########################################
	###########################################
	
	protected function doTableParse(&$tableData)
	{
		$tableData->doAppendFields('re.uname,re.tim,total.view,total.reply');
		$tableData->doBegin();
		while($tableData->isNext()){
			$t=$tableData->getI();
			$tableData->setItemValue('tim',VDCSTIME::toConvert($tableData->getItemValue('tim'),13));
			$tableData->setItemValue('re.uname',$tableData->getItemValue('last_uname'));
			$tableData->setItemValue('re.tim',VDCSTIME::toConvert($tableData->getItemValue('last_tim'),13));
			$tableData->setItemValue('total.view',$tableData->getItemValue('total_view'));
			$tableData->setItemValue('total.reply',$tableData->getItemValue('total_reply'));
		}
	}
	
}
?>