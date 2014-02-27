<?
class XlogQuery extends Xlog
{
	const RowDef			= 5;
	const RowMin			= 1;
	const RowMax			= 20;
	
	
	/*
	########################################
	########################################
	*/
	public static function getHome($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='sorts='.DB::q('comm',1).' and uuid='.$uid;
		return self::get($query,$mode,$value,$row);
	}
	public static function getPage($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='sorts='.DB::q('comm',1).' and uuid='.$uid;
		return self::get($query,$mode,$value,$row);
	}
	public static function getGuide($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='sorts='.DB::q('new',1).' and uuid='.$uid;
		return self::get($query,$mode,$value,$row);
	}
	public static function getByUID($uid,$mode='',$value=0,$row=self::RowDef)
	{
		$query='uuid='.$uid.'';
		return self::get($query,$mode,$value,$row);
	}
	
	public static function get($query,$mode='',$value=0,$row=self::RowDef)
	{
		$sqlQuery='status=1';
		$sqlOrder='tim desc';
		$sqlQuery=$query;
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
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,$sqlOrder,$row);
		//dcsLog('sql',$sql);
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	public static function doDataFilter(&$tableData)
	{
		$tableData->doAppendFields('time,time_up');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('time',VDCSTime::toString($tableData->getItemValueInt('tim')));
			$tableData->setItemValue('time_up',VDCSTime::toString($tableData->getItemValueInt('tim_up')));
			//$tableData->setItemValue('message',utilCode::toHTMLExplain($tableData->getItemValue('message')));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function viewInfo($id)
	{
		$treeRS=self::getTree($id);
		if($treeRS->getCount()>0){
			
		}
		return $treeRS;
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