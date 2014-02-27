<?
class ChannelForumPostBase extends ChannelForumActionBase
{
	public $treeTopic,$treeData;
	
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->treeTopic,$this->treeData);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadPre()
	{
		parent::doLoadPre();
		$this->TopicTableName=$this->cfg->vp('topic:table.name');
		$this->TopicTablePX=$this->cfg->vp('topic:table.px');
		$this->DataTableName=$this->cfg->vp('data:table.name');
		$this->DataTablePX=$this->cfg->vp('data:table.px');
		
		$this->TopicFieldsNew		= 'orderid,classid,tipid,uuid,t_topic,t_subtopic,t_icon,t_style,sp_keyword,sp_dataname,t_isdigest,t_isgood,t_istop,t_islock,t_status,t_tim,t_last_tim,t_last_uuid';
		$this->DataFieldsNew		= 'rootid,uuid,d_topic,d_icon,d_remark,sp_code,sp_ip,sp_agent,d_isroot,d_isgood,d_islock,d_status,d_tim';
		$this->DataFieldsReply		= 'rootid,uuid,d_topic,d_icon,d_remark,sp_code,sp_ip,sp_agent,d_isroot,d_isgood,d_islock,d_status,d_tim';
		$this->DataFieldsEdit		= 'd_topic,d_icon,d_remark,d_isgood,d_last_tim,d_last_uuid';
		
		$this->id=queryi('id');
		$this->dataid=queryi('dataid');
		
		$this->treeTopic=newTree();
		$this->treeData=newTree();
		
		$this->userc->inite();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadTopic()
	{
		$sql='select * from '.$this->TopicTableName.' where classid<>'.self::ClassIDDel.' and t_id='.$this->id.' limit 1';
		$this->treeTopic=DB::queryTree($sql);
		if($this->treeTopic->getCount()>0){
			$this->_isTopic=true;
			$this->treeTopic->doFilter($this->TopicTablePX);
		}
	}
	public function isTopic(){return $this->_isTopic;}
	public function getTopic($k){return $this->treeTopic->getItem($k);}
	public function getTopicInt($k){return $this->treeTopic->getItemInt($k);}
	public function getTopicNum($k){return $this->treeTopic->getItemNum($k);}
	
	public function loadData()
	{
		$sql='select * from '.$this->DataTableName.' where d_id='.$this->dataid.' and rootid='.$this->id.' limit 1';
		$this->treeData=DB::queryTree($sql);
		if($this->treeData->getCount()>0){
			$this->_isData=true;
			$this->treeData->doFilter($this->DataTablePX);
			$this->_isDataTopic=($this->getDataInt('istopic')==1);
		}
	}
	public function isData(){return $this->_isData;}
	public function isDataTopic(){return $this->_isDataTopic;}
	public function getData($k){return $this->treeData->getItem($k);}
	public function getDataInt($k){return $this->treeData->getItemInt($k);}
	public function getDataNum($k){return $this->treeData->getItemNum($k);}
	
	
	public function isTopicLock()		//主题是否锁定
	{
		$re=false;
		if($this->getTopicInt('islock')==1){
			if(!$this->isModerator()) $re=true;	//$this->doError('topic.lock');
		}
		return $re;
	}
	
	public function isTopicOvertime()		//主题是否超时
	{
		$re=false;
		if(!$this->isModerator()){
			$tmpOverDay= toNum(DCS::timer())- toNum($this->getTopic('tim'));
			$tmpOverDay = ceil($tmpOverDay/60/60/24);
			
			if($ctl->action=='edit'){		//修改超时
				if(toInt($tmpOverDay)>toInt($cfg->cfg('post.overtime.edit'))) $re=true;		//$this->doError('post.overtime.edit');
			}
			else{					//回复超时
				if(toInt($tmpOverDay)>toInt($cfg->cfg('post.overtime.reply'))) $re=true;	//$this->doError('post.overtime.reply');
			}
		}
		return $re;
	}
	
	public function isDataOwner()			//贴子是否自己的
	{
		$re=false;
		if(!$this->isModerator()){
			if($this->getDataInt('uuid')!=$this->userc->id) $re=true;		//$this->doError('edit.user');
		}
		return $re;
	}
	
	
	public function isClassPost(){return $this->treeClass->getItemInt('ispost')==1;}
	public function isClassReadonly(){return $this->getClass('mode')=='readonly';}
	
	public function isModerator(){return $this->userc->isModerator();}
	public function getModeratorGrade(){return $this->userc->getGrade();}
	
	
	/*
	########################################
	########################################
	*/
	public function loadAtt()
	{
		if($ctl->action=='new' || $ctl->action=='edit'){
			if($this->isModerator()){
				switch($this->getModeratorGrade()){
					case 1:
					case 2:
					case 3:
						$ctl->addDTML('att.top','0=不固顶;1=固顶');
						break;
					case 5:
					case 6:
						$ctl->addDTML('att.top','0=不固顶;1=固顶;2=区固顶');
						break;
					case 9:
					case 10:
						$ctl->addDTML('att.top','0=不固顶;1=固顶;2=区固顶;3=总固顶');
						break;
				}
			}
		}
		switch($this->getModeratorGrade()){
			case 9:
			case 10:
				$ctl->addDTML('att.select.list','restore=修复;restores=彻底修复;rap=奖惩;move=转移;istop=固顶;istopz=区固顶;istops=总固顶;untop=取消固顶;isgood=精华;ungood=取消精华;islock=锁定;unlock=取消锁定;delete=删除');
				$ctl->addDTML('att.select.view','lock=锁定;shield=屏弊;del=删除;reduce=还原;restores=彻底修复;delete=彻底删除');
				$ctl->addDTML('att.select.recycle','reduce=还原;clear=清除');
				$ctl->addDTML('att.select.event','clear=清除');
				$ctl->addDTML('att.handle.list.points','200;100;50;40;30;20;10;0;-10;-20;-30;-40;-50;-100;-200');
				$ctl->addDTML('att.handle.list.exp','200;100;50;40;30;20;10;0;-10;-20;-30;-40;-50;-100;-200');
				$ctl->addDTML('att.handle.list.cause','维护;奖励;好文章;内容不符;重复;灌水;广告');
				break;
			default:
				$ctl->addDTML('att.select.list','restore=修复;restores=彻底修复;rap=奖惩;move=转移;istop=固顶;istopz=区固顶;istops=总固顶;untop=取消固顶;isgood=精华;ungood=取消精华;islock=锁定;unlock=取消锁定;delete=删除');
				$ctl->addDTML('att.select.view','lock=锁定;shield=屏弊;del=删除;reduce=还原;restores=彻底修复');
				$ctl->addDTML('att.select.recycle','无');
				$ctl->addDTML('att.select.event','无');
				$ctl->addDTML('att.handle.list.points','50;40;30;20;10;0;-10;-20;-30;-40;-50');
				$ctl->addDTML('att.handle.list.exp','50;40;30;20;10;0;-10;-20;-30;-40;-50');
				$ctl->addDTML('att.handle.list.cause','维护;奖励;好文章;内容不符;重复;灌水;广告');
				break;
		}
	}
	
	
	
	/*
	########################################
	########################################
	*/
	public function getMaxTopicID(){		//获取最大主题ID
		return DB::queryInt('select max(t_id) from '.$this->TopicTableName);
	}
	
	public function getOrderID(){			//获取最新的主题OrderID
		$re=DCS::timer();
		//$re=DB::queryInt('select max(orderid) from '.$this->TopicTableName)+1;
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toFilterRemark($re,$act='')
	{
		$re=utilRegex::toReplaceFilter($re,'\[edit\](.+?)\[\/edit\]','');
		$re=utilRegex::toReplaceFilter($re,'\[edit\](.+?),(.*)\[\/edit\]','');
		if($act=='quote'){
			$re=utilRegex::toReplaceFilter($re,'\[quote\](.+?)\[\/quote\]','');
			$re=utilRegex::toReplaceFilter($re,'\[quote=(.[^\],]*)\](.+?)\[\/quote\]','');
			$re=utilRegex::toReplaceFilter($re,'\[quote=(.+?),(.[^\]]*)\](.+?)\[\/quote\]','');
		}
		return $re;
	}
	
	protected function doUpdateCache()
	{
		ModelClassExtend::doCacheUpdate($this->cfg->getChannel());
	}
	
	public function doUserUpdate($type)
	{
		switch($type){
			case 'new':
				//追加用户积分值
				$usere->plusValue('money',$this->cfg->cfgn('post.new.money'));
				$usere->plusValue('emoney',$this->cfg->cfgn('post.new.emoney'));
				$usere->plusValue('points',$this->cfg->cfgi('post.new.points'));
				$usere->plusValue('exp',$this->cfg->cfgi('post.new.exp'));
				$sqlUpdate='u_total_data=u_total_data+1,u_total_topic=u_total_topic+1';
				break;
			case 'reply':
				//追加用户积分值
				$usere->plusValue('money',$this->cfg->cfgn('postreply.money'));
				$usere->plusValue('emoney',$this->cfg->cfgn('postreply.emoney'));
				$usere->plusValue('points',$this->cfg->cfgi('postreply.points'));
				$usere->plusValue('exp',$this->cfg->cfgi('postreply.exp'));
				$sqlUpdate='u_total_data=u_total_data+1,u_total_reply=u_total_reply+1';
				break;
		}
		$usere->doUpdate();
		if(sqlUpdate) $this->userc->doUpdate($sqlUpdate);
	}
	
}
?>