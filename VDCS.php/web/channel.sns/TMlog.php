<?
class TMlog
{
	const TableName			= 'db_mlog';
	const TablePX			= '';
	const FieldID			= 'id';
	//id,appid,tagid,relayid,uurc,uuid,uuno,uuname,message,summarys,more,tagids,source,pics,pic,ispic,poll_agree,poll_oppose,fromid,status,tim,tim_up,total_view,total_relay,total_comment
	
	const ContentTableName		= 'db_mcontent';
	const TagsTableName		= 'db_mtags';
	const AtTableName		= 'db_mat';
	
	
	public static function getTree($id)
	{
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$re=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1) $re=-1;
		elseif($treeRS->getItemInt('uuid')!=$ua->id) $re=-2;
		return $re;
	}
	
}
?>