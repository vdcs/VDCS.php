<?
class ModelClassCache
{
	
	/*
	########################################
	########################################
	*/
	public static function toDTMLCache($re)
	{
		//####################
		$tableChannel=null;	//$this->getTable();
		$tableRoot=null;	//$this->getTableRoot();
		//####################
		$_pattern='<dtml-loop:'.PATTERN_FLAG_VAR.'.class.root'.PATTERN_FLAG_OPTION.'>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		//debugAry($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			$_channel=$_matches[1][$m];
			if(!$tableRoot[$_channel]){
				$modClass=new ModelClass();
				$modClass->setChannel($_channel);
				$modClass->doInit();
				$tableRoot[$_channel]=$modClass->getTableRoot();
			}
			if(len($_matches[3][$m])>0){
				$patternRoot=rd(VDCSDTML::PATTERN_DTML_ITEMS_KEY,'key',$_matches[3][$m]);
			}
			else{
				$patternRoot=VDCSDTML::PATTERN_DTML_ITEMS;
			}
			$rFlagValue='';
			$tableRoot[$_channel]->doItemBegin();
			for($t=0;$t<$tableRoot[$_channel]->getRow();$t++){
				$treeItem=$tableRoot[$_channel]->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=VDCSDTML::toReplaceEncodeFilter($_matches[4][$m],$treeItem,$patternRoot);
				$tableRoot[$_channel]->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		$_pattern='<dtml-loop:'.PATTERN_FLAG_VAR.'.class>'.PATTERN_FLAG_CONTENT.'<\/dtml-loop>';
		$_matches=utilRegex::toMatches($re,$_pattern);
		for($m=0;$m<count($_matches[0]);$m++){
			$_channel=$_matches[1][$m];
			if(!$tableChannel[$_channel]){
				$modClass=new ModelClass();
				$modClass->setChannel($_channel);
				$modClass->doInit();
				$tableChannel[$_channel]=$modClass->getTable();
			}
			$rFlagValue='';
			$tableChannel[$_channel]->doItemBegin();
			for($t=0;$t<$tableChannel[$_channel]->getRow();$t++){
				$treeItem=$tableChannel[$_channel]->getItemTree();
				CommonTheme::doItemAppend($treeItem,$t+1);
				$treeItem->addItem('url',$treeItem->getItem('linkurl'));
				$rFlagValue.=utilRegex::toReplaceRegex($_matches[2][$m],$treeItem,VDCSDTML::PATTERN_DTML_ITEM);
				$tableChannel[$_channel]->doItemMove();
			}
			$re=r($re,$_matches[0][$m],$rFlagValue);
		}
		//####################
		unsetr($_matches);
		unsetr($tableChannel,$tableRoot);
		//####################
		return $re;
	}
}
?>