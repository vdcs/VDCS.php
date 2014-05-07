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
		global $uu;Ua::init($rc);
		$params=[];
		$params['field.id']=$uu[$rc]->FieldID;
		$params['table.name']=$uu[$rc]->TableName;
		$params['opt.px']='u';
		$params['opt.relateid']='uuid';
		$params['opt.fields']='name,email,mobile,names';
		$params['on.names']=true;
		StructExtend::appendTable($tableData,$opt,$params);
	}
	
	//UaExtend::appendTreeInfo($reTree,['px'=>'u','relateid'=>'uuid','fields'=>'*']);
	public static function appendTreeInfo(&$treeData,$opt=[],$rc=APP_UA)
	{
		global $uu;Ua::init($rc);
		if(!$opt) $opt=[];
		if(!$opt['px']) $opt['px']='u';
		if(!$opt['fields']) $opt['fields']='uid,name,email,mobile';
		if(!$opt['relateid']) $opt['relateid']='uuid';		//$uu[$rc]->FieldID;
		$opt['relateid']=isInt($opt['relateid'])?$opt['relateid']:$treeData->getItem($opt['relateid']);
		//debuga($opt);
		if(len($opt['relateid'])>0){
			$FieldID=$uu[$rc]->FieldID;
			$treeUA=DB::queryTree('select '.$opt['fields'].' from '.$uu[$rc]->TableName.' where '.$FieldID.' = '.$opt['relateid'].'');
			$treeUA->addItem('names',Ua::toNames($treeUA,$FieldID));
			$treeData->doAppendTree($treeUA,$opt['px']);
		}
	}
	
}
?>