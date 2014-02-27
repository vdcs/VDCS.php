<?
class ModelSpecial
{
	const ModType='channel';
	const TableName='dbs_special';
	
	private $_isCache=true;
	private $_channel;
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
	}
	
	public function getTable(){return $this->tableChannel;}
	public function getTableRoot(){return $this->toTable($this->tableChannel);}
	
	public function getValue($id,$key){return utilTableExtend::getItem($this->tableChannel,'specialid='.$id,$key);}
	public function getName($id){return $this->getChannelName($this->_channel,$id);}
	
	public function getIDS($id){return $this->toIDS($this->tableChannel,$id);}
	public function getAtt(){return utilTableExtend::getAtt($this->tableChannel,'specialid','name','star','--');}
	
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
			$_id=$this->tableChannel->getItemValueInt('specialid');
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
	
	public function toLinkURL($id,$page='special')
	{
		global $cfg;
		return $cfg->getLinkURL($this->_channel,$page,'special='.$id.'&page=0');
	}

	
	/*
	########################################
	########################################
	*/
	public function getChannelTable($channel)
	{
		if($this->_isCache){
			$arys=VDCSCache::getCache(self::ModType.'.'.$channel.'.special','config',false);
			if(isAry($arys)){
				$reTable=newTable();
				$reTable->setArray($arys);
				return $reTable;
			}
		}
		//$sql=dcs.getConfigTree('common.config/data/config').getItem('model.special.sql')
		if(len($sql)<1) $sql='select channel,specialid,name,popedom from {$tablename} where channel=\'{$channel}\' order by orderid,tim';
		$sql=rd($sql,'tablename',self::TableName);
		$sql=rd($sql,'channel',$channel);
		$reTable=DB::queryTable($sql);
		$table2=newTable();
		$table2->setArray($reTable->getArray());
		//##########
		$treeConfig=CommonChannelExtend::getTree($channel,'configure',true);
		$urlPage=$treeConfig->getItem('url.list');
		$treeSQL=CommonChannelExtend::getTree($channel,'sql',true);
		$tableName=$treeSQL->getItem('struct.list.table');
		if(len($tableName)<1) $tableName=$treeSQL->getItem('struct.table.name');
		unsetr($treeSQL);
		//##########
		$reTable->doAppendFields('id,level,ids,linkurl,space,total');
		$reTable->doItemBegin();
		for($t=0;$t<$reTable->getRow();$t++){
			$v_specialid=$reTable->getItemValueInt('specialid');
			$v_ids=$this->toIDS($table2,$v_specialid);
			$reTable->setItemValue('id',$v_specialid);
			$reTable->setItemValue('level',$reTable->getItemValueInt('levelid'));
			$reTable->setItemValue('ids',$v_ids);
			$reTable->setItemValue('linkurl',rd($urlPage,'specialid',$v_specialid));
			$v_space='';
			for($s=0;$s<$reTable->getItemValueInt('levelid');$s++){
				$v_space.='&nbsp; ';
			}
			$reTable->setItemValue('space',$v_space);
			$sql='select count(*) from '.self::TableName.' where specialid in ('.$v_ids.')';
			$reTable->setItemValue('total',DB::queryInt($sql));
			$reTable->doItemMove();
		}
		//##########
		if($this->_isCache){
			VDCSCache::setCache(self::ModType.'.'.$channel.'.special',$reTable->getArray(),'config');
		}
		return $reTable;
	}
	
	public function getChannelTree($channel)
	{
		$re=new utilTree();
		$table=$this->getChannelTable($channel);
		$table->doItemBegin();
		for($t=0;$t<$table->getRow();$t++){
			$re->addItem($table->getItemValue('specialid'),$table->getItemValue('name'));
			$table->doItemMove();
		}
		$re;
	}
	
	public function getChannelValue($channel,$id,$key){return utilTableExtend::getItem($this->getChannelTable($channel),'specialid='.$id,$key);}
	public function getChannelName($channel,$id){return $this->getChannelTree($channel)->getItem($id);}
	public function getChannelAtt($channel){return $this->getTableAtt($this->getChannelTable($channel),'specialid','name','levelid','--');}
	public function getChannelIDS($channel,$id){return $this->toIDS($this->getChannelTable($channel),$id);}
	
	public function doChannelUpdate($channel)
	{
		VDCSCache::delCache(self::ModType.'.'.$channel.'.special','config');
		//VDCSCache::delCache(self::ModType.'.'.$channel.'.specialtree','config');
		//VDCSCache::delCache(self::ModType.'.specialtree','config');
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toTable($tableData)
	{
		$re=new utilTable();
		if(isTable($tableData)){
			$re->setFields($tableData->getFields());
			$tableData->doItemBegin();
			for($t=0;$t<$tableData->getRow();$t++){
				if($tableData->getItemValueInt('fatherid')==0) $re->addItem($tableData->getItemTree());
				$tableData->doItemMove();
			}
		}
		return $re;
	}
	
	public static function toIDS($tableData,$id)
	{
		$re='0';
		if(isTable($tableData)){
			$isreal=false;
			$rootid=0;
			$levelid=0;
			$tableData->doItemBegin();
			for($t=0;$t<$tableData->getRow();$t++){
				if($isreal){
					if($tableData->getItemValueInt('rootid')!=$rootid) break;
					if($tableData->getItemValueInt('levelid')>$levelid) $re.=','.$tableData->getItemValueInt('specialid');
				}
				else{
					if($tableData->getItemValueInt('specialid')==$id){
						$_id=$tableData->getItemValueInt('specialid');
						$rootid=$tableData->getItemValueInt('rootid');
						$levelid=$tableData->getItemValueInt('levelid');
						$re=$id;
						$isreal=true;
					}
				}
				$tableData->doItemMove();
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTML($re)
	{
		/*
		$_channel=$this->getChannel();
		$tableChannel=$this->getTable();
		$tableRoot=$this->getTableRoot();
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
			$tableRoot->doItemBegin();
			for($t=0;$t<$tableRoot->getRow();$t++){
				$treeItem=$tableRoot->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				//$treeItem->addItem('url',$cfg->getLinkURL($_channel,'list','specialid='.$treeItem->getItem('specialid')));
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=VDCSDTML::toReplaceEncodeFilter($_matches[3][$m],$treeItem,$patternRoot);
				$tableRoot->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<dtml-loop:class>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$rFlagValue='';
			$tableChannel->doItemBegin();
			for($t=0;$t<$tableChannel->getRow();$t++){
				$treeItem=$tableChannel->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				//$treeItem->addItem('url',$cfg->getLinkURL($_channel,'list','specialid='.$treeItem->getItem('specialid')));
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=utilRegex::toReplaceRegex($_matches[1][$m],$treeItem,VDCSDTML::PATTERN_DTML_ITEM);
				$tableChannel->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unsetr($_matches);
		unsetr($tableChannel,$tableRoot);
		//####################
		*/
		return $re;
	}
}
?>