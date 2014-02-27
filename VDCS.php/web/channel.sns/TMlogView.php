<?
class TMlogView extends TMlog
{
	
	
	/*
	########################################
	########################################
	*/
	public static function infoTree($id)
	{
		$treeRS=self::getTree($id);
		if($treeRS->getCount()>0){
			$treeRS->doAppendTree(self::contentTree($id));
		}
		return $treeRS;
	}
	public static function contentTree($id,$isDraft='')
	{
		$contentTableName=self::ContentTableName;
		if($isDraft=='draft') $contentTableName='db_mlog_draft';
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect($contentTableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		//debugTree($treeRS);
		if(len($treeRS->getItem('contents'))<1){
			$treeRS->addItem('contents',self::contentParser($treeRS->getItem('content')));
		}
		return $treeRS;
	}
	
	public static function viewContentDraft($id)
	{
		$contentTableName='db_mlog_draft';
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect($contentTableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		//debugTree($treeRS);
		if(len($treeRS->getItem('contents'))<1){
			$treeRS->addItem('contents',self::contentParser($treeRS->getItem('content')));
		}
		return $treeRS;
	}
	public static function contentParser($re)
	{
		$re=TCode::toTransContent($re);
		return $re;
	}
	
}
