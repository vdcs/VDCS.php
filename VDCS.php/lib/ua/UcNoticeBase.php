<?
class UcNoticeBase
{
	const KEY			= 'notice';
	const TableName			= 'dbu_notice';
	const TablePX			= 'u_';
	const FieldID			= 'id';
	//id,sorts,types,uurc,uuid,sendrc,sendid,sendname,channel,module,action,rootid,dataid,icon,message,sp_templat,sp_datas,status,tim,tim_read
	
	
	public static function getTree($id)
	{
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
}
