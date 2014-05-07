<?
class CommonExtend
{
	
	/*
	########################################
	########################################
	*/
	public static function toExtendTable(&$tbl,$relateid,$fields,$exTable,$exFields,$exQuery,$exID=null,$exValues=null)
	{
		utilString::lists($relateid,$rid,$rid2,'=');
		//list($rid,$rid2)=explode('=',$relateid);
		if(!$rid2) $rid2=$rid;
		if(!$exFields) $exFields='*';
		if($exID!=null) $exQuery=DB::sqla($exQuery,$exID.' in ('.$exValues.')');
		//$sql='select '.$exFields.' from '.$exTable.' where '.$exQuery;
		$sql=DB::sqlSelect($exTable,'',$exFields,$exQuery);
		//debugx($sql);
		$tableEx=DB::queryTable($sql);
		if(!$fields) $fields=$tableEx->getFields();
		//debugx($fields);
		$arFields=explode(';',r($fields,',',';'));
		$tbl->doAppendFields($fields);
		$tbl->doItemBegin();
		for($t=0;$t<$tbl->getRow();$t++){
			$tableEx->doItemBegin();
			for($t2=0;$t2<$tableEx->getRow();$t2++){
				//debugx($rid.'='.$tbl->getItemValue($rid).'--'.$rid2.'='.$tableEx->getItemValue($rid2));
				if($tbl->getItemValue($rid)==$tableEx->getItemValue($rid2)){
					reset($arFields);
					for($a=0;$a<count($arFields);$a++){
						$k=$arFields[$a];
						$tbl->setItemValue($k,$tableEx->getItemValue($k));
					}
					break 1;
				}
				$tableEx->doItemMove();
			}
			$tbl->doItemMove();
		}
		return $tbl;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toSafeIP($ip)
	{
		$ar=explode('.',$ip,4);
		return $ar[0].'.'.$ar[1].'.*.*';
	}
	
	
}
?>