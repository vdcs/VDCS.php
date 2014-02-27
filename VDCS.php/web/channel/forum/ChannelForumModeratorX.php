<?
class ChannelForumModeratorX extends ChannelForumBaseX
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
		if ($this->getModeratorGrade() < 1) $this->doError('moderator');
		if ($this->getModeratorGrade() < 9) $this->loadForumClass();
		$cfg->setTitle($cfg->v('title.moderator'));
		
	}
	
	
	##############################################
	##############################################
	public function doParse()
	{
		$this->doLoadDTMLAtt();
		$cfg->setPage(false);
		$this->doParsePage();
	}
	
	
	public function doParsePage()
	{
		if (inp('list,view,recycle,event',$ctl->module)<1) $ctl->module='list';
		###########
		$tmpSelectHandle=post('_select_handle');
		if ($tmpSelectHandle){
			$tmpSelectHandleTitle=utilString::toTree($ctl->getDTML('att.select.' . $ctl->module),'|',':')->getItem($tmpSelectHandle);
			if (!$tmpSelectHandleTitle) $tmpSelectHandle='';
			if ($tmpSelectHandle){
				$tmpSelectIDs=getPostsID('_select_id');
				$tmpSelectDataIDs=getPostsID('_select_dataid');
				if (len($tmpSelectIDs)>0 || len($tmpSelectDataIDs)>0) $isSelectHandle=true;
			}
		}
		###########
		$listnum=queryi('listnum');
		if (toInt($listnum)>100 || toInt($listnum)<5) $listnum=0;
		if (toInt($listnum)<1) $listnum=10;
		
		$uid=0;
		$username=queryx('username');
		if (!utilCheck::isName($username)) $username='';
		if (len($username)>0) $uid=$this->ua->queryField('id','name='.DB::q($username,1));
		
		switch($ctl->module){
		case 'view':
			$ctl->treeDat = DB::queryTree('select * from '.$this->ChannelPreTree->getItem('topic:table.name').' where t_id=' . $ctl->id);
			if ($ctl->treeDat->getCount()>1){
				$ctl->treeDat->doFilter ('t_');
				###########
				if ($isSelectHandle){ $this->doParseViewHandle($tmpSelectHandle,$tmpSelectHandleTitle,$tmpSelectIDs,$tmpSelectDataIDs); }
				###########
				$tmpQuery='rootid=' . $ctl->id;
				$tmpOrder='asc';
				$tmpURL='?portal='.$ctl->portal.'&format='.$ctl->format.'&module='.$ctl->module.'&';
				###########
				$s=new libSearch();
				$s->doInit();
				$s->setField('t_topic');
				$s->setFields('t_topic=6');
				$s->doParse();
				$tmpQuery=$s->toAppendQuery($tmpQuery,'');
				if($this->ua->id>0) $tmpQuery=DB::sqla($tmpQuery,'uid=' . $this->ua->id,'');
				###########
				$this->p=new libPaging();
				$this->p->setPageNum(5);
				$this->p->setListNum($listnum);
				$this->p->setConfig('url',$tmpURL);
				$this->p->setDB('table',$cfg->chn->getSQLStruct('data:table.name'));
				$this->p->setDB('id','d_id');
				$this->p->setDB('field',$cfg->chn->getSQLStruct('view.fields'));
				$this->p->setDB('query',$tmpQuery);
				$this->p->setDB('order',$cfg->chn->getSQLStruct('view.order'));
				$this->p->setDB('orders',$cfg->chn->getSQLStruct('view.orders'));
				$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
				$this->p->doParse();
				$this->tableList=$this->p->toTable();
				$this->tableList->doFilter($this->ChannelPreTree->getItem('data:table.px'),'');
			}
			break;
		case 'event':
			/*
			if ($isSelectHandle){
				$this->doParseEventHandle($tmpSelectHandle,$tmpSelectHandleTitle,$tmpSelectIDs,$tmpSelectDataIDs);
			}
			###########
			if ($this->forumid>0) $tmpQuery='classid=' . $this->forumid;
			$tmpOrder='e_id desc';
			###########
			Set s=newSearch()
			s.setField('e_remark')
			s.setFields('e_remark=6')
			s.doParse
			tmpQuery=s.toAppendQuery(tmpQuery,'')
			if clng(uid)>0 then tmpQuery=DB::sqla(tmpQuery,'username like '%'&username&'%'','')
			'##########
			ctl.p.setPageNum (5)
			ctl.p.setListNum (listnum)
			ctl.p.setDataCfg 'url', '?'&theme.portalKey&'='&theme.portal&'&'&theme.formatKey&'='&theme.format&'&module='&ctl.module&'&'
			ctl.p.setDataDB 'table', 'db_bbs_event'
			ctl.p.setDataDB 'id', 'e_id'
			ctl.p.setDataDB 'field', '*'
			ctl.p.setDataDB 'query', tmpQuery
			ctl.p.setDataDB 'order', tmpOrder
			ctl.p.doParses(dcs.db)
			set tableList=ctl.p.toTable(dcs.db)
			*/
			break;
		default:
			if (inp('list,view,recycle,event',$ctl->module)<1) $ctl->module='list';
			if (len($ctl->module)<1) $ctl->module='list';
			###########
			if ($isSelectHandle){
				$this->doParseListHandle($tmpSelectHandle,$tmpSelectHandleTitle,$tmpSelectIDs,$tmpSelectDataIDs);
			}
			###########
			if ($ctl->module=='recycle'){
				if ($this->forumid>0) $tmpQuery='sp_classid=' . $this->forumid;
				$tmpOrder='t_id desc';
			}else{
				if ($this->forumid>0) $tmpQuery='classid=' . $this->forumid;
				$tmpOrder='t_orderid desc';
			}
			###########
			$s=new libSearch();
			$s->doInit();
			$s->setField('t_topic');
			$s->setFields('t_topic=6');
			$s->doParse();
			$tmpQuery=$s->toAppendQuery($tmpQuery,'');
			if ($this->ua->id>0) $tmpQuery=DB::sqla($tmpQuery,'uid=' . $this->ua->id,'');
			###########
			$tmpURL='?portal='.$ctl->portal.'&format='.$ctl->format.'&module='.$ctl->module.'&';
			
			$this->p=new libPaging();
			$this->p->setPageNum(5);
			$this->p->setListNum($listnum);
			$this->p->setConfig('url',$tmpURL);
			$this->p->setDB('table',$cfg->chn->getSQLStruct('topic:table.name'));
			$this->p->setDB('id','d_id');
			$this->p->setDB('field',$cfg->chn->getSQLStruct('list.fields'));
			$this->p->setDB('query',$tmpQuery);
			$this->p->setDB('order',$cfg->chn->getSQLStruct('list.order'));
			$this->p->setDB('orders',$cfg->chn->getSQLStruct('list.orders'));
			$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
			$this->p->doParse();
			$this->tableList=$this->p->toTable();
			$this->tableList->doFilter($this->ChannelPreTree->getItem('topic:table.px'),'');
		}
		
		switch($ctl->module){
		case 'view':
			$this->doAppenduid($this->tableList, 0);
			break;
		case 'event':
		default:
			$this->doAppenduid($this->tableList, 1);
			break;
		}
		$this->doAppendFields();
		
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
		
		$this->addVar('_status',$ctl->getDTML('_status'));
		$this->addVar('_action',$ctl->getDTML('_action'));
		$this->addVar('_handle',$ctl->getDTML('_handle'));
		$this->addVar('_form.string',$ctl->getDTML('_form.string'));
		$this->addVar('_handle.status',$ctl->getDTML('_handle.status'));
		$this->addVar('_handle.title',$ctl->getDTML('_handle.title'));
		$this->addVar('_handle.total',$ctl->getDTML('_handle.total'));
		$this->addVar('_handle.total.succeed',$ctl->getDTML('_handle.total.succeed'));
		$this->addVar('_handle.total.failed',$ctl->getDTML('_handle.total.failed'));
		
		$this->setTable($this->tableList);
		
	}
	
	###########################################
	###########################################
	public function doParseListHandle($strSelectHandle,$strSelectHandleTitle,$strSelectIDs,$strSelectDataIDs)
	{
		initUsere();
		
		$isMessage=false;
		
		$tmpCause=utilString::toTree($ctl->getDTML('att.handle.list.cause'),'|',':')->getItem(post('cause'));
		
		$tmpCauseExplain=postc('cause_explain',50);
		$tmpToForumID=posti('toforumid');
		$tmppoints=posti('sp_points');
		$tmpExp=posti('sp_exp');
		if (post('ismessage')=='yes') $isMessage=true;
		$tmpMessage=postc('message',100);
		
		$tmpRemark=$strSelectHandleTitle . '主题贴《{$topic}》, 原因:';
		if (len($tmpCause)>0) $tmpRemark.=' [' . $tmpCause . ']';
		if (len($tmpCauseExplain)>0) $tmpRemark.=' ' . utilCode::toSQL($tmpCauseExplain);
		$tmpSQLEvent='insert into db_forum_event(classid,rootid,uid,e_emoney,e_points,e_explain,e_ip,e_tim) ';
		$tmpSQLEvent.= 'values({$classid},{$rootid},' . $this->ua->id .',' . $tmppoints . ',' . $tmpExp . ',\'' . $tmpRemark . '\',\'' . VDCS_Request::getBrowseIP() . '\',' . DCS::timer() . ')';
		
		if ($ctl->module=='recycle'){
			$tmpQuery='ciassid=' . $this->DeleteForumid;
			if ($this->forumid>0) $tmpQuery.=' and sp_classid=' . $this->forumid;
		}else{
			$tmpQuery='classid=' . $this->forumid;
		}
		
		$tmpTopicTable = $this->ChannelPreTree->getItem('topic:table.name');
		$tmpClassTable = $this->ChannelPreTree->getItem('class:table.name');
		$tmpDataname=$this->ChannelPreTree->getItem('data:table.name');
		
		$tmpSQL='select * from '.$tmpTopicTable.' where ' . $tmpQuery . ' and t_id in (' . $strSelectIDs . ')';
		$tmpTable=DB::queryTable($tmpSQL);
		
		$tmpTable->doBegin();
		while($tmpTable->isNext()){
			$t=$tmpTable->getI();
			
			$tmpID=$tmpTable->getItemValueInt('t_id');
			$tmpForumID=$tmpTable->getItemValueInt('classid');
			if ($ctl->module=='recycle'){ $tmpForumID=$tmpTable->getItemValueInt('sp_classid'); }
			$tmpTopOld=$tmpTable->getItemValueInt('t_istop');
			$tmpTopNew=0;
			$tmpTopic=$tmpTable->getItemValue('t_topic');
			$tmpuid=$tmpTable->getItemValueInt('uid');
			//$tmpUsername=$tmpTable->getItemValue('username');
			
			$tmpQueryUser='';
			
			$usere->setID($tmpuid);
			$usere->doAppendNum(0,0,$tmppoints,$tmpExp,0);
			
			$tmpisUpdateCache=true;
			
			switch($strSelectHandle){
			case 'restores':	//彻底修复
				$tmpNum=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('update '.$tmpTopicTable.' set t_total_reply=' . $tmpNum . ',t_istop=0,t_isgood=0,t_islock=0 where t_id=' . $tmpID);
				break;
			case 'restore':		//修复
				$tmpNum=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('update '.$tmpTopicTable.' set t_total_reply=' . $tmpNum . ' where t_id=' . $tmpID);
				break;
			case 'rap':		//奖惩
				$tmpisUpdateCache=false;
				break;
			case 'move':		//转移
				if (toInt($tmpToForumID)>0){
					//DB::exec('update ' . $tmpDataname . ' set f_id='&tmpToForumID&' where t_id=' . $tmpID);
					DB::exec('update '.$tmpTopicTable.' set classid='&tmpToForumID&',t_istop=0 where t_id=' . $tmpID);
					$tmpDataTatol=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
					DB::exec('update '.$tmpClassTable.' set total_topic=total_topic-1,total_data=total_data-' . $tmpDataTatol . ' where classid=' . $tmpForumID);
					DB::exec('update '.$tmpClassTable.' set total_topic=total_topic+1,total_data=total_data+' . $tmpDataTatol . ' where classid=' . $tmpToForumID);
				}
				break;
			case 'istop':
			case 'istopz':
			case 'istops':
			case 'untop':
			case 'isgood':
			case 'ungood':
			case 'islock':
			case 'unlock':		//固顶'区固顶'总固顶'精华'锁定
				$tmpQuery='';
				switch($strSelectHandle){
				case 'istop'		: $tmpQuery='t_istop=1'; $tmpTopNew=1; break;
				case 'istopz'		: $tmpQuery='t_istop=2'; $tmpTopNew=2; break;
				case 'istops'		: $tmpQuery='t_istop=3'; $tmpTopNew=3; break;
				case 'untop'		: $tmpQuery='t_istop=0'; break;
				case 'isgood'		: $tmpQuery='t_isgood=1'; break;
				case 'ungood'		: $tmpQuery='t_isgood=0'; break;
				case 'islock'		: $tmpQuery='t_islock=1'; break;
				case 'unlock'		: $tmpQuery='t_islock=0'; break;
				}
				if (len($tmpQuery)>0){
					DB::exec('update '.$tmpTopicTable.' set ' . $tmpQuery . ' where t_id=' . $tmpID);
				}
				break;
			case 'reduce':		//还原
				$tmpisUpdateCache=false;
				DB::exec('update '.$tmpTopicTable.' set classid=sp_classid,sp_classid=0,t_istop=0 where t_id=' . $tmpID);
				$tmpDataTatol=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('update '.$tmpClassTable.' set total_topic=total_topic+1,total_data=total_data+' . $tmpDataTatol . ' where id=' . $tmpForumID);
				break;
			case 'clear':		//清除
				$tmpisUpdateCache=false;
				/*
				if $tmpTable->getItemValueInt('t_isvote')=1 then
					DB::exec('delete from db_bbs_vote where t_id=' . $tmpID);
				end if
				*/
				DB::exec('delete from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('delete from '.$tmpTopicTable.' where t_id=' . $tmpID);
				$tmpDataTatol=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('update '.$tmpClassTable.' set total_topic=total_topic-1,total_data=total_data-' . $tmpDataTatol . ' where classid=' . $tmpForumID);
				$tmpQueryUser='u_bbs_delete=u_bbs_delete+1';
				break;
			case 'delete':		//删除
				DB::exec('update '.$tmpTopicTable.' set classid=' . $this->DeleteForumid . ',sp_classid=' . $tmpForumID . ',t_istop=0 where t_id=' . $tmpID);
				$tmpDataTatol=DB::queryNum('select count(*) from ' . $tmpDataname . ' where rootid=' . $tmpID);
				DB::exec('update '.$tmpClassTable.' set total_topic=total_topic-1,total_data=total_data-' . $tmpDataTatol . ' where classid=' . $tmpForumID);
				$tmpQueryUser='u_bbs_delete=u_bbs_delete+1';
				break;
			}
			
			$usere->doUpdate($tmpQueryUser);
			if ($tmpisUpdateCache){
				//debug tmpTopOld&'-'&tmpTopNew&'-'&tmpForumID
				if (toInt($tmpTopOld)>0) $this->doUpdateCache($tmpTopOld,$tmpForumID);
				if (toInt($tmpTopNew)>0) $this->doUpdateCache($tmpTopNew,$tmpForumID);
			}
			$tmpSQL=$tmpSQLEvent;
			
			$tmpSQL=rd($tmpSQL,'classid',$tmpForumID);
			$tmpSQL=rd($tmpSQL,'rootid',$tmpID);
			$tmpSQL=rd($tmpSQL,'username',$tmpUsername);
			$tmpSQL=rd($tmpSQL,'topic',utilCode::toSQL(utilCode::toCut($tmpTopic,150)));
			
			DB::exec($tmpSQL);
			
			/*
			if isMessage=true and clng(tmpuid)>0 Then
				tmpSQL='insert into db_user_message(sendid,sendname,inceptid,inceptname,m_icon,m_topic,m_remark,sp_code,m_tim,m_type,m_status,m_issys) ' & _
					'values ('&user.uid&',''&user.username&'','&tmpuid&',''&tmpUsername&'',0,'系统短信:论坛主题贴处理',''&dcs.code.toSQL(dcs.code.toCut(tmpTopic,150)&', 附加信息: '&tmpMessage&'')&'',1,''&dcs.time.getNow()&'',1,1,1)'
				call DB::exec(tmpSQL)
				DB::exec('update db_user set u_mail_new=u_mail_new+1 where uid='&tmpuid)
			End If
			*/
		}
		
		if ($tmpTable->getRow() > 0){
			switch($strSelectHandle){
			case 'delete':
				$cfg->setData('total.forum.topic',$cfg->getDataInt('total.forum.topic')-$tmpTable->getRow());
				break;
			case 'reduce':
				$cfg->setData('total.forum.topic',$cfg->getDataInt('total.forum.topic')+$tmpTable->getRow());
				break;
			}
			//dcs.server.delCache('sys.forum.class')
		}
		
		$ctl->addDTML('_handle.total',$tmpTable->getRow());
		$ctl->addDTML('_handle.total.succeed',$tmpTable->getRow());
		$ctl->addDTML('_handle.total.failed',0);
		
		/*
		if $strSelectHandle='clear' then
			dcsLoadUploadExtend()
			call upExtend.doParseDelete($cfg->getChannel(),$strSelectIDs)
		end if
		*/
		
		$ctl->addDTML('_action',$ctl->action);
		$ctl->addDTML('_handle',$strSelectHandle);
		$ctl->addDTML('_handle.title',$strSelectHandleTitle);
		$ctl->addDTML('_handle.status','succeed');
	}
	
	
	public function doParseViewHandle($strSelectHandle,$strSelectHandleTitle,$strSelectIDs,$strSelectDataIDs)
	{
		$isMessage=false;
		
		$tmpCause=utilString::toTree($ctl->getDTML('att.handle.view.cause'),'|',':')->getItem(post('cause'));
		$tmpCauseExplain=postc('cause_explain',50);
		$tmppoints=posti('sp_points');
		$tmpExp=posti('sp_exp');
		if (post('ismessage')=='yes') $isMessage=true;
		$tmpMessage=postc('message',100);
		
		$tmpRemark=$strSelectHandleTitle . '主题贴《{$topic}》, 原因:';
		if (len($tmpCause)>0) $tmpRemark.=' [' . $tmpCause . ']';
		if (len($tmpCauseExplain)>0) $tmpRemark.=' ' . utilCode::toSQL($tmpCauseExplain);
		$tmpSQLEvent='insert into db_forum_event(classid,rootid,uid,e_emoney,e_points,e_explain,e_ip,e_tim) ';
		$tmpSQLEvent.= 'values({$classid},{$rootid},' . $this->ua->id .',' . $tmppoints . ',' . $tmpExp . ',\'' . $tmpRemark . '\',\'' . VDCS_Request::getBrowseIP() . '\',' . DCS::timer() . ')';
		
		$tmpTopicTable = $this->ChannelPreTree->getItem('topic:table.name');
		$tmpClassTable = $this->ChannelPreTree->getItem('class:table.name');
		$tmpDataname=$this->ChannelPreTree->getItem('data:table.name');
		
		$tmpSQL='select * from '.$tmpDataname.' where d_id in (' . $strSelectIDs . ')';
		
		$tmpTable=DB::queryTable($tmpSQL);
		
		$tmpTable->doBegin();
		while($tmpTable->isNext()){
			$t=$tmpTable->getI();
			
			$tmpID=$tmpTable->getItemValueInt('d_id');
			$tmpForumID=$tmpTable->getItemValueInt('classid');
			$tmpTopic=$tmpTable->getItemValue('t_topic');
			$tmpuid=$tmpTable->getItemValueInt('uid');
			//$tmpUsername=$tmpTable->getItemValue('username');
			
			$tmpQueryUser='';
			
			$usere->setID($tmpuid);
			$usere->doAppendNum(0,0,$tmppoints,$tmpExp,0);
			
			switch($strSelectHandle){
			case 'restores':	//彻底修复
				DB::exec('update ' . $tmpDataname . ' set d_isgood=0 where d_id=' . $tmpID);
				break;
			case 'restore':
			case 'reduce':		//修复 还原
				//DB::exec('update ' . $tmpDataname . ' set d_isshield=0 where d_id=' . $tmpID);
				break;
			case 'rap':		//奖惩
				break;
			case 'isgood':
			case 'ungood':
				$tmpQuery='';
				switch($strSelectHandle){
				case 'isgood'		: $tmpQuery='d_isgood=1'; break;
				case 'ungood'		: $tmpQuery='d_isgood=0'; break;
				}
				if (len($tmpQuery)>0){
					DB::exec('update ' . $tmpDataname . ' set ' . $tmpQuery . ' where d_id=' . $tmpID);
				}
				break;
			case 'lock':		//锁定
				DB::exec('update ' . $tmpDataname . ' set d_islock=1 where d_id=' . $tmpID);
				break;
			case 'del':		//删除
				//DB::exec('update '&DataTableName&' set d_isshield=9 where d_id=' . $tmpID);
				DB::exec('update '.$tmpClassTable.' set total_topic=total_topic-1,total_data=total_data-1 where classid=' . $tmpForumID);
				$tmpQueryUser='u_bbs_delete=u_bbs_delete+1';
				break;
			case 'delete':		//彻底删除
				DB::exec('delete from ' . $tmpDataname . ' where d_id=' . $tmpID);
				DB::exec('update '.$tmpClassTable.' set total_data=total_data-1 where classid=' . $tmpForumID);
				$tmpQueryUser='u_bbs_delete=u_bbs_delete+1';
				break;
			}
			
			$usere->doUpdate($tmpQueryUser);
			
			$tmpSQL=$tmpSQLEvent;
			$tmpSQL=rd($tmpSQL,'classid',$tmpForumID);
			$tmpSQL=rd($tmpSQL,'rootid',$tmpID);
			$tmpSQL=rd($tmpSQL,'username',$tmpUsername);
			$tmpSQL=rd($tmpSQL,'topic',utilCode::toSQL(utilCode::toCut($tmpTopic,150)));
			
			DB::exec($tmpSQL);
			
			/*
			if isMessage=true and clng(tmpuid)>0 Then
				tmpSQL='insert into db_user_message(sendid,sendname,inceptid,inceptname,m_icon,m_topic,m_remark,sp_code,m_tim,m_type,m_status,m_issys) ' & _
					'values ('&user.uid&',''&user.username&'','&tmpuid&',''&tmpUsername&'',0,'系统短信:论坛主题贴处理',''&dcs.code.toSQL(dcs.code.toCut(tmpTopic,150)&', 附加信息: '&tmpMessage&'')&'',1,''&dcs.time.getNow()&'',1,1,1)'
				call DB::exec(tmpSQL)
				DB::exec('update db_user set u_mail_new=u_mail_new+1 where uid='&tmpuid)
			End If
			*/
		}
		
		if ($tmpTable->getRow() > 0){
			switch($strSelectHandle){
			case 'delete':
				$cfg->setData('total.forum.topic',$cfg->getDataInt('total.forum.topic')-$tmpTable->getRow());
				break;
			case 'reduce':
				$cfg->setData('total.forum.topic',$cfg->getDataInt('total.forum.topic')+$tmpTable->getRow());
				break;
			}
			//dcs.server.delCache('sys.forum.class')
		}
		
		$ctl->addDTML('_action',$ctl->action);
		$ctl->addDTML('_handle',$strSelectHandle);
		$ctl->addDTML('_handle.title',$strSelectHandleTitle);
		$ctl->addDTML('_handle.status','succeed');
		$ctl->addDTML('_handle.total',$tmpTable->getRow());
		$ctl->addDTML('_handle.total.succeed',$tmpTable->getRow());
		$ctl->addDTML('_handle.total.failed',0);
	}
	
	###########################################
	###########################################
	protected function doAppendFields()
	{
		switch($ctl->module){
		case 'view':
			$nowPage=toInt($this->p->getPage());
			$nowPage=($nowPage-1)*$this->_listnum;
			
			$this->tableList->doAppendFields('floor,ip,isshield,forumid,remark');
			$this->tableList->doBegin();
			while($this->tableList->isNext()){
				$t=$this->tableList->getI();
				$this->tableList->setItemValue('forumid',0);
				$this->tableList->setItemValue('remark',VDCSCodes::toCodes($this->tableList->getItemValue('content'),1));
				
				$this->tableList->setItemValue('floor',$t+$nowPage);
				$this->tableList->setItemValue('ip',$this->tableList->getItemValue('ip'));
			}
			break;	
		default:
			$this->tableList->doAppendFields('re.username,re.tim,total.view,total.reply');
			$this->tableList->doBegin();
			while($this->tableList->isNext()){
				$t=$this->tableList->getI();
				$this->tableList->setItemValue('tim',VDCSTIME::toConvert($this->tableList->getItemValue('tim'),13));
				$this->tableList->setItemValue('re.username',$this->tableList->getItemValue('last_username'));
				$this->tableList->setItemValue('re.tim',VDCSTIME::toConvert($this->tableList->getItemValue('last_tim'),13));
				$this->tableList->setItemValue('total.view',$this->tableList->getItemValue('total_view'));
				$this->tableList->setItemValue('total.reply',$this->tableList->getItemValue('total_reply'));
			}
		}
		
	}
	
	protected function doUpdateCache($strTop,$strForumID)
	{
		global $cfg;
		if ($strForumID < 1) return;
		VDCSCache::delCache('channel.'.$cfg->getChannel().'.topic.top','config');
	}
	
	
}
?>