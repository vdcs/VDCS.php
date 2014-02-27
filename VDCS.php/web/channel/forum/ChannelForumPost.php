<?
class ChannelForumPost extends ChannelForumPostBase
{
	
	public function doLoad()
	{
		//锁定用户
		if($this->userc->isLock()) $this->doError('user.lock');
		
		$this->loadVcode($this->_chn_.'.'.$this->_p_);
		$this->loadShield();
		
		switch($this->action){
			case 'reply':
			case 'edit':
				$this->loadTopic();
				if(!$this->isTopic()) $this->doError('topic');
				
				$this->classid=$this->getTopic('classid');
				$this->loadClass(1);
				
				//
				if($this->action=='edit' || $this->mode=='quote'){
					$this->loadData();
					$_remark=$this->getData('remark');
					if($this->action=='edit'){
						if(!$this->isData()) $this->doError('data');
						if(!$this->isDataOwner()) $this->doError('edit.owner');
						
						$this->treeData->addItem('d_topic',$this->getData('topic'));
						$this->treeData->addItem('d_icon',$this->getData('icon'));
						$this->treeData->addItem('d_isgood',$this->getData('isgood'));
						$_remark=$this->toFilterRemark($_remark,$this->action);
					}
					
					if($this->mode=='quote'){
						$_remark=$this->toFilterRemark($tmpRemark,'quote');
						$_remark='[quote='.$this->getData('uuid').','.$this->getData('tim').']'.$_remark.'[/quote]';
					}
					
					$this->treeData->addItem('d_remark',$_remark);
					unset($_remark);
				}
				break;
			default:
				$this->action='new';
				$this->loadClass(1);
				if($this->isClassReadonly()) $this->doError('class.readonly');
				break;
			
		}
		
		$this->userc->setData('moderator.is',$this->isModerator()?'yes':'no');
		$this->userc->setData('moderator.grade',$this->getModeratorGrade());
		
		$title=$this->cfg->v('title.post.'.$this->action);
		if(len($title)<1) $title=$this->cfg->v('title.post');
		$this->cfg->setTitle('sub',$title);
	}
	
	
	public function doParse()
	{
		$this->theme->setPage('post');
		/*
		switch($this->action){
			case 'reply':
				$theme->setPage($this->action);
				$this->doParseReply();
				break;
			case 'edit':
				$theme->setPage($this->action);
				$this->doParseEdit();
				break;
			default:
				$this->action='new';
				$this->doParseNew();
				break;
		}
		*/
		$this->doParseForm();
		if($this->isForm){
			$this->doParseSave();
		}
	}
	
	
	########################################
	########################################
	public function doParseForm()
	{
		$this->treeData->addItem('classid',$this->classid);
		$this->treeData->addItem('sp_classid',0);
		
		if(!isFormPost()){
			if($this->action=='edit'){
				$this->treeData->addItem('t_istop',$this->getTopic('istop'));
				$this->treeData->addItem('t_isgood',$this->getTopic('isgood'));
				$this->treeData->addItem('t_islock',$this->getTopic('islock'));
			}
			else{
				$this->treeData->addItem('d_icon',0);
				if($this->action=='new'){ $this->treeData->addItem('t_istop',0); }
				if($this->mode=='vote'){
					$this->treeData->addItem('v_type',0);
					$this->treeData->addItem('v_day',0);
				}
			}
		}
		
		if(isFormPost()){
			$this->isForm=true;
			
			$this->treeData->addItem('orderid',0);
			$this->treeData->addItem('uuid',$this->userc->id);
			$this->treeData->addItem('d_topic',postc('d_topic',200));
			$this->treeData->addItem('d_icon',posti('d_icon'));
			//$this->treeData->addItem('d_isubb',posti('d_isubb'));
			//$this->treeData->addItem('d_issign',posti('d_issign'));
			$this->treeData->addItem('d_remark',postc('d_remark',10000));
			$this->treeData->addItem('sp_code',1);
			
			switch($this->action){
				case 'reply':
				case 'edit':
					$this->treeData->addItem('rootid',$this->id);
					
					if($this->action=='edit'){
						if($this->isModerator()){
							$this->treeData->addItem('d_isgood',posti('d_isgood'));
							$this->treeData->addItem('_iseditinfo',posti('_iseditinfo'));
						}
						else{
							$this->treeData->addItem('d_isgood',$this->getData('isgood'));
						}
						if($this->treeData->getItem('_iseditinfo')==1){
							$this->treeData->addItem('d_edit_tim',DCS::now());
							$this->treeData->addItem('d_edit_uid',$this->userc->id);
						}
						else{
							$this->treeData->addItem('d_edit_tim',$this->getData('edit_tim'));
							$this->treeData->addItem('d_edit_uid',$this->getData('edit_uid'));
						}
					}
					break;
				default:
					$this->treeData->addItem('tipid',posti('tipid'));
					$this->treeData->addItem('t_topic',$this->treeData->getItem('d_topic'));
					$this->treeData->addItem('t_subtopic',postc('t_subtopic',250));
					$this->treeData->addItem('t_icon',$this->treeData->getItem('d_icon'));
					
					$this->treeData->addItem('_color',postc('_color',6));
					$this->treeData->addItem('_bb',posti('_bb'));
					
					//状态数据处理
					$this->treeData->addItem('t_isdigest',0);
					$this->treeData->addItem('t_isgood',0);
					$this->treeData->addItem('t_istop',0);
					$this->treeData->addItem('t_islock',0);
					$this->treeData->addItem('t_status',1);
					break;
			}
			
			if($this->action=='edit' || $this->action=='new'){
				if($this->isModerator()){
					$this->treeData->addItem('t_isdigest',posti('t_isdigest'));
					$this->treeData->addItem('t_isgood',posti('t_isgood'));
					$_num=posti('t_istop');
					if(ins(';'.$this->treeDTML->getItem('att.top'),';'.$_num)<1) $_num=0;
					$this->treeData->addItem('t_istop',$_num);
					$this->treeData->addItem('t_islock',posti('t_islock'));
				}
			}
		}
		
	}
	
	
	public function doParseSave()
	{
		if($this->action=='new' || $this->action=='reply'){
			$this->treeData->addItem('sp_ip',DCS::ip());
			$this->treeData->addItem('sp_agent',DCS::agent());
			if($this->action=='new'){
				$this->treeData->addItem('d_isroot',1);
			}
			else{
				$this->treeData->addItem('d_isroot',0);
			}
			$this->treeData->addItem('d_isgood',0);
			$this->treeData->addItem('d_islock',0);
			$this->treeData->addItem('d_status',0);
			$this->treeData->addItem('d_tim',DCS::timer());
			//$this->treeData->addItem('t_isshield',0);
		}
		
		//if($this->ua->getDataInt('onlines')<$cfg->cfgi('post.min.online')) $this->e->addItem('您的在线时间小于 '.$cfg->cfgi('post.min.online').' 分钟，因此禁止发贴回贴操作！');
		
		if(len($this->treeData->getItem('d_remark'))>50000) $this->e->addItem('贴子的 内容 太长了！超过5万字节！');
		$isUpdateCache=true;
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'d_topic,t_topic,d_remark');
		
		switch($this->action){
			case 'reply':
				if(len($this->treeData->getItem('d_remark')) < 1) { $this->e->addItem('请填写贴子的 内容'); }
				
				if(!$this->e->isCheck()){
					$this->treeData->addItem('rootid',$this->getTopic('id'));
					$this->treeData->addItem('orderid',0);
					
					$this->treeData->addItem('t_topic',$this->getTopic('topic'));
					$this->treeData->addItem('t_istop',$this->getTopic('istop'));
					if(len($this->treeData->getItem('d_topic'))<1) { $this->treeData->addItem('d_topic',$this->getTopic('topic')); }
					$this->treeData->addItem('d_topic','Re:'.$this->treeData->getItem('d_topic'));
					
					//插入贴子数据
					DB::executeInsert($this->DataTableName,$this->DataFieldsReply,$this->treeData);
					$this->dataid=DB::insertid();
					
					//更新主题数据
					$sql='update '.$this->TopicTableName.' set orderid='.$this->getOrderID().',t_last_uid='.$this->userc->id.',t_total_reply=t_total_reply+1,t_last_tim=\''.DCS::timer().'\''.$sqlAppend.' where t_id='.$this->id;
					DB::exec($sql);
				}
				break;
			case 'edit':
				if(len($this->treeData->getItem('d_topic'))<1) $this->e->addItem('请填写贴子的 主题');
				if(len($this->treeData->getItem('d_remark'))<1) $this->e->addItem('请填写贴子的 内容');
				
				if(!$this->e->isCheck()){
					//更新贴子数据
					DB::executeUpdate($this->DataTableName,$this->DataFieldsEdit,$this->treeData,'d_id='.$this->dataid);
					
					//更新主题标题
					if($this->isDataTopic()){
						$tmpSQL='update '.$this->TopicTableName.' set t_bb='.$this->treeData->getItemInt('t_bb').',t_color=\''.utilCode::toSQL($this->treeData->getItem('t_color')).'\',t_topic=\''.utilCode::toSQL($this->treeData->getItem('d_topic')).'\'';
						if($this->isModerator()){
							$tmpSQL.=(',t_istop='.$this->treeData->getItemInt('t_istop').',t_isgood='.$this->treeData->getItemInt('t_isgood').',t_islock='.$this->treeData->getItemInt('t_islock').'');
						}
						$tmpSQL .=' where t_id='.$this->id;
						DB::exec($tmpSQL);
						
						$this->treeData->addItem('t_istop.old',$this->getTopicInt('t_istop'));
					}
					else{
						$isUpdateCache=false;
					}
				}
				break;
			default:
				if(len($this->treeData->getItem('t_topic'))<1 ) $this->e->addItem('请填写贴子的 主题');
				if(len($this->treeData->getItem('d_remark'))<1 ) $this->e->addItem('请填写贴子的 内容');
				
				if(len($this->treeData->getItem('t_color'))>0 ){
					$usere->minusValue('points',$cfg->cfg('post.color.points'));
				}
				if($this->treeData->getItemInt('t_bb')>0 ){
					$usere->minusValue('points',$cfg->cfg('post.bold.points'));
				}
				if(!$usere->isTest()) $this->e->addItem('您没有足够的积分值来设置主题特效');
				
				if(!$this->e->isCheck() ){
					$this->treeData->addItem('t_tim',DCS::timer());
					$this->treeData->addItem('t_last_uid',0);
					$this->treeData->addItem('t_last_tim',DCS::timer());
					
					//插入主题数据
					$this->treeData->addItem('orderid',$this->getOrderID());
					DB::executeInsert($this->TopicTableName,$this->TopicFieldsNew,$this->treeData);
					$this->id=DB::insertid();		//$this->getMaxTopicID();
					$this->treeData->addItem('rootid',$this->id);
					$theme->setPre('id',$this->id);
					
					//插入贴子数据
					$this->treeData->addItem('orderid',0);
					DB::executeInsert($this->DataTableName,$this->DataFieldsNew,$this->treeData);
					$this->dataid=DB::insertid();
				}
		}
		
		if($this->e->isCheck()){
			$this->doRaiseError();
		}
		else{
			//论坛数据更新
			if($this->action=='new' || $this->action=='reply'){
				$cfg->setData('total.forum.new',toInt($cfg->getData('total.forum.new'))+1);
				$cfg->setData('total.forum.data',toInt($cfg->getData('total.forum.data'))+1);
				$sqlAppend='';
				if($this->action=='new'){
					$cfg->setData('total.forum.topic',toInt($cfg->getData('total.forum.topic'))+1);
					$sqlAppend='total_topic=total_topic+1,';
				}
				if($this->action=='reply'){
					$cfg->setData('total.forum.reply',toInt($cfg->getData('total.forum.reply'))+1);
					$sqlAppend='total_reply=total_reply+1,';
				}
				$newinfo=$this->treeData->getItem('username').','.$this->treeData->getItem('d_tim').','.$this->treeData->getItem('rootid').','.r($this->treeData->getItem('t_topic'),',','');
				$sql='update '.$cfg->vp('class:table.name').' set total_new=total_new+1,'.$sqlAppend.'total_data=total_data+1,newinfo=\''.utilCode::toSQL($newinfo).'\' where classid='.$this->classid;
				DB::exec($sql);
			}
			
			//更新用户积分值
			$this->doUserUpdate($this->action);
			
			//upload
			CommonUploadExtend::doParseRelate($cfg->getChannel(),$this->id,$this->dataid);
			
			//更新论坛缓存
			if($isUpdateCache) $this->doUpdateCache();
			
			$theme->setStatus('succeed');
			//$cfg->toLinkURL('list','classid='.$this->classid)
			
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		$this->theme->doCacheFilterTree('topic','cpo.treeTopic');
		$this->theme->doCacheFilterTree('tdata','cpo.treeData');
		
	}
	
}
?>