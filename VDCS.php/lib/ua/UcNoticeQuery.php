<?
class UcNoticeQuery extends UcNoticeAction
{
	const RowDef			= 30;//默认加载行数
	const RowMin			= 1;
	const RowMax			= 40;
	
	public static function toRow($row=self::RowDef)
	{
		if($row>self::RowMax||$row<self::RowMin) $row=self::RowDef;
		return $row;
	}
	
	public static function initParamsQuery($ua,&$params)
	{
		$params=$params?$params:[];
		$uuid=$params['uid']?$params['uid']:$ua->id;
		$query='uuid='.$uuid.'';
		$params['sql.query']=$query;
		$params['sql.order']='id desc';
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getByUa($ua,$params,$row=self::RowDef)
	{
		if(!self::initParamsQuery($ua,$params)) return false;
		$mode='';$value='';
		return self::get($ua,$params['sql.query'],$params['sql.order'],$mode,$value,$row);
	}
	
	public static function get($ua,$query,$order,$mode='',$value=0,$row=self::RowDef)
	{
		$sqlQuery='status=1';
		$sqlOrder=$order?$order:'id desc';
		$sqlQuery=$query;
		//debugx($mode.','.$value);
		if($value){
			switch($mode){
				case 'new':
					$sqlQuery=DB::sqla($sqlQuery,'tim>'.$value);
					$row=0;
					break;
				case 'next':
					$sqlQuery=DB::sqla($sqlQuery,'id<'.$value);
					break;
				default:
			}
		}
		//dcsLog('uri',$_SERVER['REQUEST_URI']);
		//dcsLog('query',$sqlQuery);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,$sqlOrder,$row);
		//dcsLog('sql',$sql);
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($ua,$tableData);
		return $tableData;
	}
	
	//过滤
	public static function doDataFilter($ua,&$tableData)
	{
		return;
		$tableData->doAppendFields('time,time_up');
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('logo',CommonTheme::toUploadURL($tableData->getItemValue('logo')));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function querier($ua,$params,&$p)
	{
		if(!self::initParamsQuery($ua,$params)) return false;
		$total=self::getTotal($params['sql.query']);
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getList($ua,$params['sql.query'],$params['sql.order'],$p->getPage(),$p->getListNum());
		return $tableData;
	}
	
	public static function getTotal($query)
	{
		$sql=DB::sqlSelect(self::TableName,'count','*',$query);
		//debugx($sql);
		return DB::queryInt($sql);
	}
	public static function getList($ua,$query,$order,$page,$row=self::RowDef)
	{
		$sql=DB::sqlSelect(self::TableName,'','*',$query,$order);
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($ua,$tableData);
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
			
		}
		return $treeRS;
	}
	
}
