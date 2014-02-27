<?
class TMlogAction extends TMlog
{
	
	public static function like($ua,$id)
	{
		$_status=TLike::like($ua,$id);
		if($_status==1) self::likeSet($ua,$id);
		return $_status;
	}
	public static function likeSet($ua,$id)
	{
		$_status=0;
		$sqlQuery=self::FieldID.'='.$id;
		$sql='update '.self::TableName.' set total_like=total_like+1 where '.$sqlQuery;
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
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
	
	public static function getIsFollowData($tableData,$uid)
	{
		$tableData->doAppendFields('is_follow');
		$followids=DB::queryTable('select uuid2 from dbu_follow where uuid='.$uid.' and status=1')->getValues('uuid2');
		if(!$followids) return $tableData;
		$followidsAry=explode(',',$followids);
		$tableData->doBegin();
		while($tableData->isNext()){
			if(in_array($tableData->getItemValue('uuid'),$followidsAry) || $tableData->getItemValue('uuid')==$uid){
				$tableData->setItemValue('is_follow','yes');
			}else{
				$tableData->setItemValue('is_follow','no');
			}
		}
		return $tableData;
	}
	
	
	public static function setTotalViewTimes($dataTable)
	{
		$dataTable->doBegin();
		while($dataTable->isNext()){
			$id=$dataTable->getItemValue('id');
			//DB::query('update db_mlog set total_view=total_view+1 where id='.$id.'');
		}
	}
	
	
}
?>