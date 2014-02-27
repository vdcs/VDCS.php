<?
class ModelClass
{
	private $_isCache=true;
	private $_channel='';
	private $tableChannel=null;
	private $_id=0;
	
	public function __construct()
	{
	}
	public function __destruct()
	{
		unsetr($this->tableChannel);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setCache($b){$this->_isCache=$b;}
	
	public function setUse($s){$this->_isuse=$s;}
	public function isUse(){return $this->_isuse;}
	
	public function setChannel($s){$this->_channel=$s;}
	public function getChannel(){return $this->_channel;}
	
	public function setID($s){$this->_id=$s;}
	public function getID(){return $this->_id;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->_isinit) return;$this->_isinit=true;
		$this->tableChannel=$this->getChannelTable($this->_channel);
		//debugTable($this->tableChannel);
	}
	
	public function getTable(){return $this->tableChannel;}
	public function getTableRoot(){return ModelClassExtend::toTable($this->tableChannel);}
	public function getTableSub($id){if($id<1) $id=-1; return ModelClassExtend::toTable($this->tableChannel,$id);}
	
	public function getValue($id,$strKey){return utilTableExtend::getItem($this->tableChannel,'classid='.$id,$strKey);}
	public function getName($id){return $this->getChannelName($this->_channel,$id);}
	
	public function getIDS($id){return ModelClassExtend::toIDS($this->tableChannel,$id);}
	public function getAtt(){return utilTableExtend::getAtt($this->tableChannel,'classid','name','star','--');}
	
	public function isExsitChild($id)
	{
		$re=false;
		if($id>0){
			$this->tableChannel->doItemBegin();
			for($t=0;$t<$this->tableChannel->getRow();$t++){
				if($this->tableChannel->getItemValueInt('f')==$id){
					$re=true;
					break;
				}
				$this->tableChannel->doItemMove();
			}
		}
		return $re;
	}
	
	public function getNav($id,$space=' - ')
	{
		$rootid=-1;
		$fatherid=-1;
		$this->tableChannel->doItemEnd();
		for($t=$this->tableChannel->getRow();$t>0;$t--){
			$_id=$this->tableChannel->getItemValueInt('classid');
			if($rootid > -1){
				if($this->tableChannel->getItemValueInt('rootid')!=$rootid) break;
				if($_id==$fatherid){
					$re='<a href="'.$this->toLinkURL($_id).'">'.$this->tableChannel->getItemValue('name').'</a>'.($space).($re);
					$fatherid=$this->tableChannel->getItemValueInt('fatherid');
				}
			}
			else{
				if($_id==$id){
					$rootid=$this->tableChannel->getItemValueInt('rootid');
					$fatherid=$this->tableChannel->getItemValueInt('fatherid');
					$re='<a href="'.$this->toLinkURL($_id).'">'.$this->tableChannel->getItemValue('name').'</a>';
				}
			}
			$this->tableChannel->doItemMove(-1);
		}
		return $re;
	}
	
	public function toLinkURL($id,$page='list')
	{
		global $cfg;
		return $cfg->getLinkURL($this->_channel,$page,'classid='.$id);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getChannelTable($channel,$cachename=null,$query=null,$order=null,$limit=null)
	{
		if(!$cachename) $cachename=$channel;
		if($this->_isCache){
			$arys=ModelClassExtend::getCacheAry($cachename);
			if(isAry($arys)){
				$reTable=newTable();
				$reTable->setArray($arys);
				return $reTable;
			}
		}
		//$sql=dcs.getConfigTree('common.config/data/config').getItem('model.class.sql')
		if(!$query) $query='channel=\'{$channel}\'';
		if(!$order) $order='rootid,orderid';
		if($limit && $limit>0) $limits='limit 0,'.$limit;
		if(len($sql)<1) $sql='select channel,rootid,classid,name,levelid,fatherid,popedom,issp,sp_popedom,sp_points,sp_emoney,total from {$tablename} where '.$query.' order by '.$order.' '.$limits;
		$sql=rd($sql,'tablename',ModelClassExtend::TableName);
		$sql=rd($sql,'channel',$channel);
		$reTable=DB::queryTable($sql);
		ModelClassExtend::doTableFilter($reTable,$channel);
		//##########
		if($this->_isCache){
			ModelClassExtend::setCache($cachename,$reTable);
		}
		return $reTable;
	}
	
	public function getChannelTree($channel,$id=0)
	{
		$reTree=new utilTree();
		$table=$this->getChannelTable($channel);
		if($id>0){
			$table->doBegin();
			while($table->isNext()){
				if($table->getItemValueInt('classid')==$id){
					$reTree=$table->getItemTree();
					break;
				}
			}
		}
		else{
			$table->doBegin();
			while($table->isNext()){
				$reTree->addItem($table->getItemValue('classid'),$table->getItemValue('name'));
			}
		}
		return $reTree;
	}
	
	public function getChannelValue($channel,$id,$strKey){return utilTableExtend::getItem($this->getChannelTable($channel),'classid='.$id,$strKey);}
	public function getChannelName($channel,$id){return $this->getChannelValue($channel,$id,'name');}
	public function getChannelAtt($channel){return $this->getTableAtt($this->getChannelTable($channel),'classid','name','levelid','--');}
	public function getChannelIDS($channel,$id){return $this->toClassids($this->getChannelTable($channel),$id);}
	
	public function doChannelUpdate($channel){ModelClassExtend::doCacheUpdate($channel);}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTML($re)
	{
		$_channel=$this->getChannel();
		$tableChannel=null;$tableRoot=null;$tableSub=null;
		//####################
		$_pattern='<dtml-loop:class.root'.PATTERN_FLAG_OPTION.'>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			if(len($_matches[2][$m])>0){
				$patternRoot=rd(VDCSDTML::PATTERN_DTML_ITEMS_KEY,'key',$_matches[2][$m]);
			}
			else{
				$patternRoot=VDCSDTML::PATTERN_DTML_ITEMS;
			}
			$rFlagValue='';
			if(!$tableRoot){
				$tableRoot=$this->getTableRoot();
			}
			$tableRoot->doItemBegin();
			for($t=0;$t<$tableRoot->getRow();$t++){
				$treeItem=$tableRoot->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=VDCSDTML::toReplaceEncodeFilter($_matches[3][$m],$treeItem,$patternRoot);
				$tableRoot->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<dtml-loop:class.sub'.PATTERN_FLAG_OPTION.'>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			if(len($_matches[2][$m])>0){
				$patternRoot=rd(VDCSDTML::PATTERN_DTML_ITEMS_KEY,'key',$_matches[2][$m]);
			}
			else{
				$patternRoot=VDCSDTML::PATTERN_DTML_ITEMS;
			}
			$rFlagValue='';
			if(!$tableSub){
				$tableSub=$this->getTableSub($this->_id);
			}
			$tableSub->doItemBegin();
			for($t=0;$t<$tableSub->getRow();$t++){
				$treeItem=$tableSub->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=VDCSDTML::toReplaceEncodeFilter($_matches[3][$m],$treeItem,$patternRoot);
				$tableSub->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<dtml-loop:class>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue='';
			if(!$tableChannel){
				$tableChannel=$this->getTable();
			}
			$tableChannel->doItemBegin();
			for($t=0;$t<$tableChannel->getRow();$t++){
				$treeItem=$tableChannel->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=utilRegex::toReplaceRegex($_matches[1][$m],$treeItem,VDCSDTML::PATTERN_DTML_ITEM);
				$tableChannel->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unsetr($_matches);
		unsetr($tableChannel,$tableRoot,$tableSub);
		//####################
		return $re;
	}
}
?>