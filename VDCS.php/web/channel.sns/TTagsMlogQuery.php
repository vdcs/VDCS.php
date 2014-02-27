<?
class TagsMlogQuery extends TTagsMlog
{
	const RowDef			= 5;
	const RowMin			= 1;
	const RowMax			= 20;
	
	
	/*
	########################################
	########################################
	*/
	public static function getHome($tagid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='tagid='.$tagid;
		return self::get($query,$mode,$value,$row);
	}
	
	public static function getByRID($ids)	//有回复的
	{
		$query='id in ('.$ids.')';
		return self::get($query,'',0,0);
	}
	
	public static function get($query,$mode='',$value=0,$row=self::RowDef)
	{
		$sqlQuery='status=1';
		$sqlOrder='tim desc';
		$sqlQuery=$query;
		//debugx($query);
		//debugx($mode.','.$value);
		//if($uid>0) $sqlQuery=DB::sqla($sqlQuery,'uuid='.$uid);
		if($value){
			switch($mode){
				case 'new':
					$sqlQuery=DB::sqla($sqlQuery,'tim>'.$value);
					$row=0;
					break;
				case 'next':
				default:
					$sqlQuery=DB::sqla($sqlQuery,'id<'.$value);
					break;
			}
		}
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		//dcsLog('query',$sqlQuery);
		//debugx($sqlQuery);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,$sqlOrder,$row);
		//dcsLog('sql',$sql);
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	public static function doDataFilter(&$tableData)
	{
		$tableData->doAppendFields('time,time_up,fromname,fromurl');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('time',VDCSTime::toString($tableData->getItemValueInt('tim')));
			$tableData->setItemValue('time_up',VDCSTime::toString($tableData->getItemValueInt('tim_up')));
			$froms=TFrom::getInfo($tableData->getItemValueInt('fromid'));
			$tableData->setItemValue('fromname',$froms['name']);
			$tableData->setItemValue('fromurl',$froms['url']);
			$tableData->setItemValue('message',utilCode::toHTMLExplain($tableData->getItemValue('message')));
		}
	}
	
	public static function getLikeData($tableData,$uid)
	{
		$tableData->doAppendFields('is_like');
		$rootids=DB::queryTable('select rootid from db_mlike where uuid='.$uid.'')->getValues('rootid');
		if(!$rootids) return $tableData;
		$rootidsAry=explode(',',$rootids);
		$tableData->doBegin();
		while($tableData->isNext()){
			if(in_array($tableData->getItemValue('id'),$rootidsAry)){
				$tableData->setItemValue('is_like','yes');
			}else{
				$tableData->setItemValue('is_like','no');
			}
		}
		return $tableData;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function viewInfo($id)
	{
		$treeRS=self::getTree($id);
		if($treeRS->getCount()>0){
			$treeRS->doAppendTree(self::viewContent($id));
		}
		return $treeRS;
	}
	public static function viewContent($id,$isDraft='')
	{
		$contentTableName=self::ContentTableName;
		if($isDraft=='draft') $contentTableName='db_mlog_draft';
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect($contentTableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		//debugTree($treeRS);
		if(len($treeRS->getItem('contents'))<1){
			$treeRS->addItem('contents',self::contentParser($treeRS->getItem('content')));
		}
		return $treeRS;
	}
	public static function contentParser($re)
	{
		$re=TCode::toTransContent($re);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toRow($row=self::RowDef)
	{
		if($row>self::RowMax||$row<self::RowMin) $row=self::RowDef;
		return $row;
	}	
}
?>