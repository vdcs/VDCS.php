<?
class ChannelCommonConsultX extends ChannelCommonBase
{
	use WebServeRefXML;
	
	public $tableList,$p;
	protected $_pagenum=5,$_listnum=10;
	protected $treeConfig;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableList,$this->p);
		unsetr($this->treeConfig);
	}
	
	public function setNum($pagenum,$listnum)
	{
		if($pagenum>0) $this->_pagenum=$pagenum;
		if($listnum>0) $this->_listnum=$listnum;
	}
	
	
	protected function loadConfig()
	{
		$this->channel=query('channel');
		if(!utilCheck::isSecure($this->channel)) $this->channel='';
		$this->rootid=queryi('rootid');
		$this->dataid=queryi('dataid');
		$this->replyid=queryi('replyid');
		$this->page=queryi('page');
		
		$this->addVar('channel',$this->channel);
		$this->addVar('rootid',$this->rootid);
		$this->addVar('dataid',$this->dataid);
		$this->addVar('replyid',$this->replyid);
		$this->addVar('page',$this->page);
		
		$this->treeConfig=VDCSDTML::getConfigTree('common.config/common/comment/config');
		//debugTree($this->treeConfig);
		
		$this->TableName=$this->cStruct('table.name');
		$this->TablePX=$this->cStruct('table.px');
		$this->FieldID=$this->cStruct('field.id');
		
		$this->_var['status']=-1;
	}
	public function cStruct($k){return $this->treeConfig->getItem('struct.'.$k);}
	public function cLang($k){return $this->treeConfig->getItem('lang.'.$k);}
	
	
	public function doLoad()
	{
		$this->loadConfig();
		
	}
	
	
	public function doParse()
	{
		switch($ctl->action){
			case 'post':
				$this->doParsePost();
				break;
			//case 'status':
			default:
				$this->doParseList();
				break;
		}
	}
	
	public function doParseStatus()
	{
		//if theme.portal='dtml' and ctl.module='status' then
			$sql='SELECT c_poll as poll_key,COUNT(c_poll) AS poll_total FROM '.$this->TableName.' WHERE '.$this->_var['query'].' GROUP BY c_poll ORDER BY c_poll DESC';
			$tablePoll=DB::queryTable($sql);
			$tablePoll->doBegin();
			while($tablePoll->isNext()){
				$this->addVar('poll'.$tablePoll->getItemValue('poll_key'),$tablePoll->getItemValue('poll_total'));
			}
			unsetr($tablePoll);
		//end if
	}
	
	public function doParseList()
	{
		$this->_var['query']=$this->cStruct('list.query');
		if($this->dataid>0) $this->_var['query']=$this->cStruct('query.data');
		if(!$this->_var['query']) $this->_var['query']='channel=\'{$channel}\' and rootid={$rootid} and c_status=1';
		$this->_var['query']=rd($this->_var['query'],'channel',$this->channel);
		$this->_var['query']=rd($this->_var['query'],'rootid',$this->rootid);
		$this->_var['query']=rd($this->_var['query'],'dataid',$this->dataid);
		
		$this->poll=query('poll');
		$this->pollvalue=$this->toPollValue($this->poll);
		if($this->pollvalue>-1) $this->_var['query']=DB::sqla($this->_var['query'],'c_poll='.$this->pollvalue);
		$this->addVar('poll',$this->poll);
		$this->addVar('pollvalue',$this->pollvalue);
		
		$this->doParseStatus();
		
		$this->_var['url']='?m='.$this->module.'&channel='.$this->channel.'&rootid='.$this->rootid.'&dataid='.$this->dataid.'&replyid='.$this->replyid.'&';
		$this->addVar('url',$this->_var['url']);
		
		$this->p=new libPaging();
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->TableName);
		$this->p->setDB('id',$this->FieldID);
		$this->p->setDB('field',$this->cStruct('list.field'));
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order',$this->cStruct('list.order'));
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableList=$this->p->toTable();
		$this->tableList->doFilter($this->TablePX);
		$this->doListFilter($this->tableList);
		
		$this->setTable($this->tableList);
		
		$this->addVar('paging.listnum',$this->p->getListNum());
		$this->addVar('paging.numend',$this->p->getNumEnd());
		$this->addVar('paging.total',$this->p->getTotal());
		$this->addVar('paging.page',$this->p->getPage());
		$this->addVar('paging.pagenum',$this->p->getPageNum());
		$this->addVar('paging.pagetotal',$this->p->getPageTotal());
		$this->addVar('paging.pagebase',$this->p->getPageBase());
	}
	protected function doListFilter(&$tableData)
	{
		UaExtend::appendInfo($tableData);
		$tableData->doAppendFields('time,trans_time');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('time',VDCSTime::toString($tableData->getItemValue('tim')));
			//debugx($tableData->getItemValue('uuid'));
		}
	}
	
	public function doParsePost()
	{
		if(!isFormPost()) return;
		$this->setStatus('ready');
		
		$ctl->treeData->addItem($this->TablePX.'realname',postc('realname',20));
		$ctl->treeData->addItem($this->TablePX.'email',postc('email',100));
		$ctl->treeData->addItem($this->TablePX.'topic',postc('topic',100));
		$ctl->treeData->addItem($this->TablePX.'icon',posti('icon'));
		$ctl->treeData->addItem($this->TablePX.'remark',postc('remark',1000));
		
		$this->poll=post('poll');
		$this->pollvalue=$this->toPollValue($this->poll);
		$ctl->treeData->addItem($this->TablePX.'poll',$this->pollvalue);
		$ctl->treeData->addItem($this->TablePX.'poll_rank',posti('poll_rank'));
		$ctl->treeData->addItem($this->TablePX.'poll_type',posti('poll_type'));
		
		if(len($this->channel)<1 || $this->rootid<1) $ctl->e->addItem($this->cLang('error.data'));
		/*
		if(!$ctl->e->isCheck()){			//禁用信息屏蔽
			if(web.isShieldIP('post')) $ctl->e->addItem($cfg->cLang('message.shield.ip.post'));
			if(web.isShieldBadword('post',$ctl->treeData->getItem($this->TablePX.'remark')) $ctl->e->addItem($cfg->cLang('message.shield.badword.post'));
			if(web.isShieldBadword('comment',$ctl->treeData->getItem($this->TablePX.'remark')) $ctl->e->addItem($cfg->cLang('message.shield.badword.post'));
		}
		*/
		if(!$ctl->e->isCheck()){
			if(!utilCheck::isName($ctl->treeData->getItem($this->TablePX.'realname'))) $ctl->e->addItem($this->cLang('error.norule.realname'));
			if(len($ctl->treeData->getItem($this->TablePX.'email'))>0){
				if(!utilCheck::isEmail($ctl->treeData->getItem($this->TablePX.'email'))) $ctl->e->addItem($this->cLang('error.norule.email'));
			}
			//if(len($ctl->treeData->getItem($this->TablePX.'topic'))<1) $ctl->e->addItem($this->cLang('error.norule.topic'));
			if(len($ctl->treeData->getItem($this->TablePX.'remark'))<1) $ctl->e->addItem($this->cLang('error.norule.remark'));
		}
		/*
		if(!$ctl->e->isCheck()){
			dcsLoadVerifyCode()
			vcp.setChannel('comment')
			if(!$vcp->isCheck()) $ctl->e->addItem($vcp->getMessage);
		}
		*/
		
		$this->addVarTree($ctl->treeData,'data.');
		
		if(!$ctl->e->isCheck()){
			if($this->_var['status']<0){
				$this->_var['status']=1;//toInt(web.getShieldValue('num.status.comment'))
			}
			
			$ctl->treeData->addItem('channel',$this->channel);
			$ctl->treeData->addItem('rootid',$this->rootid);
			$ctl->treeData->addItem('dataid',$this->dataid);
			$ctl->treeData->addItem('replyid',$this->replyid);
			$ctl->treeData->addItem('uuid',$this->ua->id);
			$ctl->treeData->addItem('sp_ip',DCS::ip());
			$ctl->treeData->addItem('sp_agent',DCS::agent());
			$ctl->treeData->addItem($this->TablePX.'status',$this->_var['status']);
			$ctl->treeData->addItem($this->TablePX.'tim',DCS::timer());
			$ctl->treeData->addItem($this->TablePX.'trans',0);
			$ctl->treeData->addItem($this->TablePX.'trans_name','');
			$ctl->treeData->addItem($this->TablePX.'trans_remark','');
			$ctl->treeData->addItem($this->TablePX.'trans_tim',0);
			
			
			$sql=DB::sqlInsert($this->TableName,$this->cStruct('fields.add'),$ctl->treeData);
			//$this->addVar('_sql',$sql);
			//debugx($sql);
			DB::exec($sql);
			
			$this->setStatus('succeed');
			if($this->_var['status']>0) $ctl->e->addItem($this->cLang('lang.handle.ok.post'));
			else $ctl->e->addItem($this->cLang('lang.handle.ok.post.audit'));
		}
		
		//if($ctl->e->isCheck()) $ctl->doRaiseError();
		$this->doRaiseError();
	}
	
	
	protected function toPollValue(&$poll)
	{
		$re=-1;
		switch($poll){
			case 'good':		$re=5;break;
			case 'bad':		$re=1;break;
			case 'neutral':		$re=0;break;
			default:		$re=-1;$poll='';break;
		}
		return $re;
	}
	
}
?>