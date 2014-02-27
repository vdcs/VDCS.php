<?
class TMlogQuery extends TMlog
{
	const RowDef			= 10;//默认加载行数
	const RowMin			= 1;
	const RowMax			= 30;
	
	
	/*
	########################################
	########################################
	*/
	public static function getPlaza($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='';
		return self::get($query,$mode,$value,$row);
	}
	public static function getHome($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='(uuid='.$uid.' or uuid in (select uuid2 from dbu_follow where uuid='.$uid.') or id in (select rootid from db_mtags where tagid in (select tagid from db_tags_follow where uuid='.$uid.')))';
		//if($uid==10001) $query='uuid>0';
		$dataTable=self::get($query,$mode,$value,$row);
		//$dataTable=self::getLikeData($dataTable,$uid);
		//$dataTable=self::getIsFollowData($dataTable,$uid);
		//debugTable($dataTable);
		//self::setTotalViewTimes($dataTable);
		return $dataTable;
	}
	/*
	public static function getAt($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='id in (select rootid from db_mat where uuid='.$uid.')';
		return self::get($query,$mode,$value,$row);
	}
	*/
	public static function getByTagID($tagid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='id in (select rootid from db_mtags where tagid='.$tagid.')';
		return self::get($query,$mode,$value,$row);
	}
	public static function getByUID($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='uuid='.$uid.'';
		return self::get($query,$mode,$value,$row);
	}
	public static function getByRID($ids,$uid='')
	{
		$query='id in ('.$ids.')';
		$dataTable=self::get($query,'',0,0);
		//if($uid) $dataTable=self::getIsFollowData($dataTable,$uid);
		return $dataTable;
	}
	
	public static function getAtByRID($ids,$mode='',$value='',$row='',$uid='')
	{
		$query='id in ('.$ids.')';
		$dataTable=self::get($query,$mode,$value,$row);
		//if($uid) $tableData=self::getIsFollowData($dataTable,$uid);
		return $dataTable;
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
		//debugx($tableData->getRow());
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
