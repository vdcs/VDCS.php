<?
class ChannelForumView extends ChannelForumBase
{
	public $treeTopic,$tableData;
	public $s,$p;
	protected $_pagenum=5,$_listnum=10;
	
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->treeTopic,$this->tableData);
	}
	
	
	public function doLoad()
	{
		$this->TopicTableName=$this->cfg->vp('topic:table.name');
		$this->TopicTablePX=$this->cfg->vp('topic:table.px');
		$this->DataTableName=$this->cfg->vp('data:table.name');
		$this->DataTablePX=$this->cfg->vp('data:table.px');
		
		$this->id=queryi('id');
		ForumPost::setTotal($this->id,'view');
		
		$this->loadTopic();
		if(!$this->isTopic()) $this->doError('topic');
		
		$this->classid=$this->getTopic('classid');
		$this->loadClass(1);
		
		
		$this->cfg->setTitle('sub',$this->getTopic('topic'));
		
		$this->theme->setPre('id',$this->id);
		$this->theme->setPre('classid',$this->classid);
		
		$this->mode='detail';
		$this->isDetail=$this->mode=='detail';
		$this->theme->setModule($this->mode);
	}
	
	public function doParse()
	{
		$this->_var['url']=$this->cfg->getConfigValue('url.view.page');
		$this->_var['url']=rd($this->_var['url'],'id',$this->id);
		//$this->_var['query']=$cfg->chn->getSQLStruct('list.query');
		$this->_var['query']='rootid={$rootid}';
		$this->_var['query']=rd($this->_var['query'],'rootid',$this->id);
		
		$this->p=new libPaging();
		//$this->p->setTemplatePath('xxx');
		//$this->p->setTemplateTree(VDCSDTML::getConfigCacheTree($this->p->_templatePath.'div'));
		//$this->p->loadTemplate('easy');
		$this->p->setPageNum($this->_pagenum);
		$this->p->setListNum($this->_listnum);
		//$this->p->setListNum(2);
		$this->p->setConfig('url',$this->_var['url']);
		$this->p->setDB('table',$this->DataTableName);
		$this->p->setDB('id','d_id');
		$this->p->setDB('field','*');
		$this->p->setDB('query',$this->_var['query']);
		$this->p->setDB('order','d_tim asc');
		$this->p->setTotal(DB::queryInt($this->p->getSQL('count')));
		$this->p->doParse();
		$this->tableData=$this->p->toTable();
		$this->tableData->doFilter($this->DataTablePX);
		//$this->doDataFilter($this->tableData);
		//debugTable($this->tableData);
	}
	
	protected function doDataFilter(&$tableData)
	{
		if($tableData->getRow()<1) return;
		$nowPage=toInt($this->p->getPage());
		$nowPage=($nowPage-1)*$this->_listnum;
		$tableData->doAppendFields('_floor,uname');
		//##########
		$uids=$tableData->getValues('uid');
		if($this->isDetail){
			$FieldcScore='money,emoney,points,exp';
			$FieldcInfo='grade,level,rank,strength,total_data,total_topic,total_reply';
			$FieldsInfo='name,gender,birthday,email,names,groupid,location,marks,online,onlines,tim,tim_last';
			$FieldsUser=$FieldcScore.','.$FieldcInfo.','.$FieldsInfo;
			$arFields=toSplit($FieldsUser,',');
			$tableu=$this->userc->getInfoTable($uids,$FieldcInfo,$FieldsInfo);//StructUser里面的$this->ua不存在
			$tableData->doAppendFields($FieldsUser);
		}
		else{
			$treeu=$this->userc->getNameTree($uids);
		}
		//##########
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('_floor',$tableData->getI()+$nowPage);
			$tableData->setItemValue('remark',VDCSCodes::toCodes($tableData->getItemValue('remark'),$tableData->getItemValueInt('sp_code')));
			$uid=$tableData->getItemValue('uuid');
			if($this->isDetail){
				$treeu=$tableu->getFieldItemTree('uuid','=',$uid);
				//debugTree($treeu);
				$tableData->setItemValue('uname',$treeu->getItem('name'));
				foreach($arFields as $field){
					$tableData->setItemValue($field,$treeu->getItem($field));
				}
			}
			else{
				$tableData->setItemValue('uname',$treeu->getItem($uid));
			}
		}
		unsetr($treeu,$tableu);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		$this->theme->doCacheFilterTree('topic','cpo.treeTopic');
		$this->theme->doCacheFilterLoop('datas','cpo.tableData');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
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
	
}
?> 