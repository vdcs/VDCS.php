<?
class VDCSDBA
{
	
	
	/*
	########################################
	########################################
	*/
	public static function toSQLFilterBlank($re)
	{
		$re=str_replace("\t",'',$re);		//制表符
		$re=str_replace("\n",'',$re);		//换行
		$re=str_replace("\r",'',$re);		//回车
		return $re;
	}
	
	public static function toSQLFilterItems($re)
	{
		$re=str_replace("\t",'',$re);		//制表符
		$re=str_replace("\n",'',$re);		//换行
		$re=str_replace("\r",'',$re);		//回车
		return $re;
	}
	
	public static function toSQLFilterBatch($re)
	{
		$re=str_replace("\t",'',$re);		//制表符
		$re=str_replace("\n",'',$re);		//换行
		$re=str_replace("\r",'',$re);		//回车
		return $re;
	}
	
	public static function toSQLFilterVar(&$db,$re)
	{
		//$re=r($re,"{DATESYMBOL}",$db->DateSymbol);
		//$re=r($re,"{DATEFUNCNAME}",$db->DateFunctionName);
		$re=r($re,"{CRLF}",NEWLINE);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toFieldRelationTable($relations)
	{
		$reTable=newTable();
		$reTable->setFields('field,key,type,param');
		$tmpAry=toSplit(self::toSQLFilterItems($relations),';');
		for($a=0;$a<count($tmpAry);$a++){
			if(len($tmpAry[$a])>0){
				utilString::lists($tmpAry[$a],$tmpField,$tmpKey,'=');
				$tmpType='';$tmpParam='';
				if(ins($tmpKey,'!')>0){
					list($tmpKey,$tmpType,$tmpParam)=explode('!',$tmpKey,3);
				}
				$treeItem=newTree();
				$treeItem->addItem('field',$tmpField);
				$treeItem->addItem('key',$tmpKey);
				$treeItem->addItem('type',$tmpType);
				$treeItem->addItem('param',$tmpParam);
				$reTable->addItem($treeItem);
			}
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function doExecBatch(&$db,$query)
	{
		if($db) $db->doExecBatch($query);
	}
	
	public static function doExecuteInsert(&$db,$sTable,$cFields,$cValues)
	{
		
	}
	
}
?>