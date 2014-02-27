<?
class UaExtend
{
	/*
	public static function queryID($name){return $GLOBALS['ua']->getInfoID($name);}
	public static function queryName($id){return $GLOBALS['ua']->getInfoName($id);}
	*/
	
	public static function toSearchQuery($term,$mode=1,$rc=APP_UA)
	{
		global $uu;Ua::init($rc);
		$re='uuid in (select uid from '.$uu[$rc]->TableName.' where '.r($term,'{field}',$uu[$rc]->struct('FieldName')).')';
		return $re;
	}
	
	public static function appendInfo(&$tableData,$opt=[],$rc=APP_UA){self::appendTableInfo($tableData,$opt,$rc);}
	public static function appendTableInfo(&$tableData,$opt=[],$rc=APP_UA)
	{
		global $uu;
		Ua::init($rc);
		if(!$opt) $opt=[];
		if(!$opt['px']) $opt['px']='u';
		if(!$opt['relateid']) $opt['relateid']='uuid';		//$uu[$rc]->FieldID;
		$opt['relateids']=$tableData->getValues($opt['relateid']);
		//debuga($opt);
		if(len($opt['relateids'])>0){
			$FieldID=$uu[$rc]->FieldID;
			$TableName=$uu[$rc]->TableName;
			//$uu[$rc]->struct('FieldName')
			$fields=$FieldID.',name,email,mobile,names';
			if($opt['fields']) $fields.=','.$opt['fields'];
			$tableU=DB::queryTable('select '.$fields.' from '.$TableName.' where '.$uu[$rc]->FieldID.' in ('.$opt['relateids'].')');
			
			$afields='names';
			if($opt['infok']) $afields.=',name,email,mobile';
			if($opt['fields']) $afields.=','.$opt['fields'];
			$afielda=toSplit($afields,',');
			$afieldar=[];
			foreach($afielda as $afield){
				array_push($afieldar,$opt['px'].$afield.$opt['sx']);
			}
			$tableData->doAppendFields(implode(',',$afieldar));
			$tableData->doBegin();
			while($tableData->isNext()){
				$_id=$tableData->getItemValueInt($opt['relateid']);
				$_names='['.$_id.']';
				$treeU=newTree();
				$tableU->doBegin();
				while($tableU->isNext()){
					if($tableU->getItemValueInt($FieldID)==$_id){
						$treeU=$tableU->getItemTree();
						break;
					}
				}
				if($treeU->getCount()>0){
					$_names=UaU::toNames($treeU,$FieldID);
				}
				$treeU->addItem('names',$_names);
				foreach($afielda as $afield){
					$tableData->setItemValue($opt['px'].$afield.$opt['sx'],$treeU->getItem($afield));
				}
			}
		}
	}

	//UaExtend::appendTreeInfo($reTree,['px'=>'u','relateid'=>'uuid','fields'=>'*']);
	public static function appendTreeInfo(&$treeData,$opt=[],$rc=APP_UA){
		global $uu;
		Ua::init($rc);
		if(!$opt) $opt=[];
		if(!$opt['px']) $opt['px']='u';
		if(!$opt['fields']) $opt['fields']='uid,name,email,mobile';
		if(!$opt['relateid']) $opt['relateid']='uuid';		//$uu[$rc]->FieldID;
		$opt['relateid']=isInt($opt['relateid'])?$opt['relateid']:$treeData->getItem($opt['relateid']);
		//debuga($opt);
		if(toInt($opt['relateid'])>0){
			$FieldID=$uu[$rc]->FieldID;
			$treeUA=DB::queryTree('select '.$opt['fields'].' from '.$uu[$rc]->TableName.' where '.$uu[$rc]->FieldID.' = '.$opt['relateid'].'');
			$treeUA->addItem('names', UaU::toNames($treeUA,$FieldID));
			$treeData->doAppendTree($treeUA,$opt['px']);
		}
	}
	
}
?>