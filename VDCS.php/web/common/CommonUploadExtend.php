<?
class CommonUploadExtend extends CommonUpload
{
	
	public static function getUpid(){return post('_upid');}
	
	
	/*
	########################################
	########################################
	*/
	public static function toQuerySQL($channel,$root=0,$dataid=-1)
	{
		
	}
	
	public static function doParseRelate($channel,$rootid=0,$dataid=0,$upid='')
	{
		if(len($upid)<1) $upid=self::toIDS(post('_upid'));
		if(len($upid)>0){
			$dataid=toi($dataid);
			$sql='update '.self::TableName.' set channel='.DB::q($channel,1).',rootid='.$rootid.'';
			if($dataid>0) $sql.=',dataid='.$dataid.'';
			$sql.=',status=1 where '.self::FieldID.' in ('.$upid.')';
			DB::exec($sql);
		}
	}
	
	public static function doParseDelete($channel,$rootid,$dataid='')
	{
		$rootids=self::toIDS($rootid);
		if(len($rootids)>0){
			//##########
			$sqlQuery='channel='.DB::q($channel,1).' and rootid in ('.$rootids.')';
			$datas=self::toIDS($dataid);
			if(len($datas)>0) $sqlQuery.=' and dataid in ('.$datas.')';
			//##########
			$sql='select uurc,uuid,path from '.self::TableName.' where '.$sqlQuery;
			$tableItems=DB::queryTable($sql);
			self::parseDeleted($tableItems);
			unsetr($tableItems);
			$sql='delete from '.self::TableName.' where '.$sqlQuery;
			DB::exec($sql);
		}
	}
	public static function parseDeleted($tableData)
	{
		$basePath=appDirPath('upload');
		$tableData->doBegin();
		while($tableData->isNext()){
			$filepath=$basePath.$tableData->getItemValue('path');
			utilFile::doFileDel($filepath);
		}
	}


	public static function doFileDelete($path)
	{
		$basePath=appDirPath('upload');
		$filepath=$basePath.$path;
		utilFile::doFileDel($filepath);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toIDS($s)
	{
		$re='';
		$ary=toSplit($s,',');
		for($a=0;$a<count($ary);$a++){
			if(isInt($ary[$a])) $re.=','.$ary[$a];
		}
		if(len($re)>0) $re=substr($re,1);
		return $re;
	}
	
}