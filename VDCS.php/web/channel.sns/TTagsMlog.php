<?
class TTagsMlog
{
	const TableName			= 'db_tags_mlog';
	const TablePX			= '';
	const FieldID			= 'id';
	//id,uurc,uuid,uuno,uuname,sort,type,topic,message,summarys,content,more,pics,poll_agree,poll_oppose,fromid,status,tim,tim_up,total_view,total_relay,total_comment,total_fav,total_like
	
	
	public static function getTree($id)
	{
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
}
?>