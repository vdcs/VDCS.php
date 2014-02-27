<?
class TComment extends TMlog
{
	const CommentTableName		= 'db_mcomment';
	const CommentTablePX		= '';
	const CommentFieldID		= 'id';
	
	const RowDef			= 10;
	
	
	/*
	########################################
	########################################
	*/
	public static function getByRID($ids)
	{
		$query='id in ('.$ids.')';
		return self::get($query,'',0,0);
	}
	
	public static function getByAtRID($ids,$mode='',$value='',$row='',$uid='')
	{
		$query='id in ('.$ids.')';
		$tableData=self::get($query,$mode,$value,$row);
		if($uid) $tableData=TMlogQuery::getIsFollowData($tableData,$uid);
		return $tableData;
	}
	
	//获取评论
	public static function getComments($type,$uid,$mode='',$value='',$row='')
	{
		switch($type){
			case 'receive':
				$query='(rootid in (select id from '.self::TableName.' where uuid='.$uid.') or urid='.$uid.')  and uuid!='.$uid.'';
				break;
			case 'send':
				$query='uuid='.$uid.'';
				break;
		}
		$tableData=self::get($query,$mode,$value,$row);
		$tableData=TMlogQuery::getIsFollowData($tableData,$uid);
		return $tableData;
	}
	
	public static function get($query,$mode='',$value=0,$row=self::RowDef)
	{
		$sqlQuery='';	//'status=1'
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
		$sql=DB::sqlSelect(self::CommentTableName,'','*',$sqlQuery,$sqlOrder,$row);
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
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function querier($type,$uid,&$p)
	{
		$total=self::getTotal($type,$uid);
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getList($type,$uid,$p->getPage(),$p->getListNum());
		return $tableData;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getTotal($type,$uid)
	{
		if($type=='issue'){	//发出
			$sql='select count(*) from '.self::CommentTableName.' where uuid='.$uid.'';
		}
		else{			//收到 receive
			$sql='select count(*) from '.self::CommentTableName.' where rootid in (select id from '.self::TableName.' where uuid='.$uid.')';
			//urid='.$uid.' or 
		}
		//debugx($sql);
		return DB::queryInt($sql);
	}
	public static function getList($type,$uid,$page,$row=self::RowDef)
	{
		/*
		$sql='select mlog.*,mcomment.content as comt_content,mcomment.contents as comt_contents,mcomment.tim as comt_tim,mcomment.status as comt_status';
		if($type=='issue'){
			$sql.=' from '.self::TableName.' as mlog,'.self::CommentTableName.' as mcomment where mcomment.uuid='.$uid.' and mlog.id=mcomment.rootid order by mcomment.tim desc';
		}
		else{
			$sql.=' from '.self::TableName.' as mlog,'.self::CommentTableName.' as mcomment where (mcomment.replyid='.$uid.' or mcomment.rootid in (select id from '.self::TableName.' where uuid='.$uid.')) and mlog.id=mcomment.rootid order by mcomment.tim desc';
		}
		*/
		if($type=='issue'){
			$sql='select * from '.self::CommentTableName.' where uuid='.$uid.' order by tim desc';
		}
		else{
			$sql='select * from '.self::CommentTableName.' where rootid in (select id from '.self::TableName.' where uuid='.$uid.') order by tim desc';
			//urid='.$uid.' or 
		}
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function updateStatus($ids,$status=2)
	{
		if($ids){
			switch($status){
				case 'read':		$status=2;break;
				case 'new':		$status=1;break;
				case 'del':		$status=0;break;
			}
			if(!is_int($status)) $status=1;
			$sql='update '.self::CommentTableName.' set status='.$status.' where id in ('.$ids.')';
			$isexec=DB::exec($sql);
		}
		return $isexec;
	}
	
}
?>