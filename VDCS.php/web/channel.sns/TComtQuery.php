<?
class TComtQuery extends TComt
{
	
	/*
	########################################
	########################################
	*/
	public static function total($rootid,$query='')
	{
		$sqlQuery='status=1';
		$sqlQuery='rootid='.$rootid;
		if($query>0) $sqlQuery=DB::sqla($sqlQuery,$query);
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlQuery);
		$re=DB::queryInt($sql);
		return $re;
	}
	
	public static function getRoot($rootid,$nid,$row)
	{
		$query='rootid='.$rootid;
		return self::get($query,$nid,$row);
	}
	
	public static function get($query,$nid=0,$row=self::RowDef)
	{
		$sqlQuery='status=1';
		$sqlOrder='ground desc,tim asc';		//tim desc
		$sqlQuery=$query;
		//if($uid>0) $sqlQuery=DB::sqla($sqlQuery,'uuid='.$uid);
		if($nid>0) $sqlQuery=DB::sqla($sqlQuery,'id<'.$nid);
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		//dcsLog('query',$sqlQuery);
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
			//$tableData->setItemValue('summarys',utilCode::toHTMLExplain($tableData->getItemValue('summary')));
			//if(len($tableData->getItemValue('contents'))<1) $tableData->setItemValue('contents',$tableData->getItemValue('content'));
		}
		return $tableData;
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
