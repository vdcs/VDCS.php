<?
class DBCommonBase
{
	
	public static function q($re,$q=0)
	{
		//$re=addslashes($re);
		//if(get_magic_quotes_gpc()){
			$re=str_replace('\\','\\\\',$re);
			$re=str_replace('`','\\`',$re);
			$re=str_replace('\'','\\\'',$re);
		//}
		if($q==1) $re='\''.$re.'\'';
		return $re;
	}
	//public static function toSQL($re,$q=0){return self::q($re,$q);}
	
	public static function sqlLimit($start,$num=false)
	{
		return ' LIMIT '.($start<=0?0:(int)$start).($num? ','.abs($num):'');
	}
	//public static function toSQLLimit($start,$num=false){return self::sqlLimit($start,$num);}
	
	
	public static function sqla($re,$query,$term='')
	{
		if(strlen($query)>0){
			if(!$term) $term='and';
			$re=(strlen($re)>0)?$re.' '.$term.' '.$query:$query;
		}
		return $re;
	}
	public static function sqlAppend($re,$query,$term=''){return self::sqla($re,$query,$term);}
	public static function sqlLink($re,$query,$term=''){return self::sqla($re,$query,$term);}
	//public static function toSQLAppend($re,$query,$term=''){return self::sqla($re,$query,$term);}
	
	
	public static function sqlValue($fkey,$fname,$cValues,$rValues=null)
	{
		return array_key_exists($fkey,$cValues)?$cValues[$fkey]:
				(array_key_exists($fname,$cValues)?$cValues[$fname]:
					(array_key_exists($fkey,$rValues)?$rValues[$fkey]:$rValues[$fname])
				);
	}
	//public static function toSQLValue($fkey,$fname,$cValues,$rValues=null){return self::sqlValue($fkey,$fname,$cValues,$rValues);}
	
	
	public static function sqlInsert($sTable,$cFields,$cValues,$rValues=null)
	{
		if(!$sTable || !$cValues) return '';
		if(isTree($cValues)) $cValues=$cValues->getArray();
		if(isTree($rValues)) $rValues=$rValues->getArray();
		if(!$rValues) $rValues=array();
		if(!$cFields) $cFields=utilString::toStr(array_keys($cValues),',');
		if(!is_array($cFields)) $cFields=explode(',',utilString::toFilterBlank($cFields));
		$fields=$values='';
		for($a=0;$a<count($cFields);$a++){
			utilString::lists($cFields[$a],$fkey,$fname,'=');$fname=$fname?$fname:$fkey;
			$fvalue=self::sqlValue($fkey,$fname,$cValues,$rValues);
			$fields.=',`'.$fname.'`';
			$values.=',\''.self::q($fvalue).'\'';
		}
		if($values){
			$fields=toSubstr($fields,2);
			$values=toSubstr($values,2);
		}
		//Replace INTO
		return 'INSERT INTO `'.$sTable.'` ('.$fields.') VALUES ('.$values.')';
	}
	//public static function toSQLInsert($sTable,$cFields,$cValues,$rValues=null){return self::sqlInsert($sTable,$cFields,$cValues,$rValues);}
	
	public static function sqlUpdate($sTable,$cFields,$cValues,$sTerm,$rValues=null)
	{
		if(!$sTerm) return '';
		return self::sqlUpdates($sTable,$cFields,$cValues,$sTerm,$rValues);
	}
	public static function sqlUpdates($sTable,$cFields,$cValues,$sTerm=null,$rValues=null)
	{
		if(!$sTable || !$cValues) return '';		// || !$cFields
		if($sTerm==null || $sTerm=='null') $sTerm='';
		if(isTree($cValues)) $cValues=$cValues->getArray();
		if(isTree($rValues)) $rValues=$rValues->getArray();
		$values='';
		if(is_array($cValues)){
			if(!$sTerm) $sTerm=$cValues[VDCSDB::SQL_TERM_KEY];
			if(!$cFields) $cFields=utilString::toStr(array_keys($cValues),',');
			if(!is_array($cFields)) $cFields=explode(',',utilString::toFilterBlank($cFields));
			if(!$rValues) $rValues=array();
			for($a=0;$a<count($cFields);$a++){
				utilString::lists($cFields[$a],$fkey,$fname,'=');$fname=$fname?$fname:$fkey;
				$fvalue=self::sqlValue($fkey,$fname,$cValues,$rValues);
				//debugx($fkey.'='.$fname.'='.$fvalue);
				$values.=',`'.$fname.'`='.self::q($fvalue,1);
			}
			if($values) $values=toSubstr($values,2);
		}
		else{
			$values=$cValues;
		}
		$re='UPDATE `'.$sTable.'` SET '.$values;
		if($sTerm) $re.=' WHERE '.$sTerm;
		return $re;
	}
	//public static function toSQLUpdate($sTable,$cFields,$cValues,$sTerm=null,$rValues=null){return self::sqlUpdate($sTable,$cFields,$cValues,$sTerm,$rValues);}
	
	public static function sqlDeletes($sTable,$sTerm='')
	{
		$re='DELETE FROM '.$sTable;
		if($sTerm) $re.=' WHERE '.$sTerm;
		return $re;
	}
	public static function sqlDelete($sTable,$sTerm=''){return $re='DELETE FROM '.$sTable.' WHERE '.$sTerm;}
	//public static function toSQLDelete($sTable,$sTerm=''){return self::sqlDelete($sTable,$sTerm);}
	
	public static function sqlQuery($sTable,$sFields='',$sTerm='',$sOrder='',$limit=0)
	{
		if(!$sFields) $sFields='*';
		$re='SELECT '.$sFields.' FROM '.$sTable;
		if($sTerm) $re.=' WHERE '.$sTerm;
		if($sOrder) $re.=' ORDER BY '.$sOrder;
		if($limit>0) $re.=' LIMIT 0,'.$limit;
		return $re;
	}
	public static function sqlSelect($sTable,$act,$sFields,$sTerm='',$sOrder='',$limit=0)
	{
		$fields=$ends='';
		switch($act){
			case 'count':
			case 'sum':
			case 'max':
				$fields=$act.'('.$sFields.')';
				break;
			case 'total':
				$fields='COUNT('.$sFields.')';
				break;
			default:
				$fields=$sFields;
				if(strlen($fields)>2 && ins($fields,'(')<1){
					$fields=r($fields,',','`,`');
					$fields=r($fields,'=','`=`');
					$fields=r($fields,' as ','` as `');
					$fields='`'.$fields.'`';
				}
				if($limit>0) $ends=' LIMIT 0,'.$limit;
				break;
		}
		//debugx($fields);
		$re='SELECT '.$fields.' FROM '.$sTable;
		if($sTerm) $re.=' WHERE '.$sTerm;
		if($sOrder) $re.=' ORDER BY '.$sOrder;
		$re.=$ends;
		return $re;
	}
	//public static function toSQLSelect($sTable,$act,$sFields,$sTerm='',$sOrder='',$limit=0){return self::sqlSelect($sTable,$act,$sFields,$sTerm,$sOrder,$limit);}
	
	
	public static function sqlSerachKeyword($sql,$field,$keyword,$term)
	{
		$re='';
		$sql=trim($sql);
		if($sql){
			if(right($sql,3)=='and' || right($sql,2)=='or') $re.='';
			else $sql.=' and ';
		}
		if($term=='exact'){
			$re=$field.'=\''.self::toSQL($keyword).'\'';
		}
		else{
			$sqlTerm=($term=='or') ? 'or' : 'and';
			$aryKeyword=explode(' ',$keyword);
			for($k=0;$k<count($aryKeyword);$k++){
				$aryKeyword[$k]=trim($aryKeyword[$k]);
				if(len($aryKeyword[$k])>0){
					$_query=$field.' LIKE \'%'.self::toSQL($aryKeyword[$k]).'%\'';
					if(!$re) $re=$_query;
					else $re.=' '.$sqlTerm.' '.$_query;
				}
			}
			$re='('.$re.')';
		}
		if(len($sql)>0 && len($re)>0) $re=$sql.$re;
		return $re;
	}
	//public static function toSQLSerachKeyword($sql,$field,$keyword,$term){return self::sqlSerachKeyword($sql,$field,$keyword,$term);}
	
	public static function sqlSerachTime($field,$fieldType,$date1,$date2='')
	{
		$re='';
		$value1=$date1;$value2=$date2;
		if($date1 || $date2){
			if($value1 && !isInt($value1)) $value1=VDCSTime::toNumber($value1);
			if($value2 && !isInt($value2)) $value2=VDCSTime::toNumber($value2);
			switch($fieldType){
				case 'date':
					if($value1) $value1=VDCSTime::toString($value1,'date');
					if($value2) $value2=VDCSTime::toString($value2,'date');
					break;
				case 'time':
					if($value1) $value1=VDCSTime::toString($value1);
					if($value2) $value2=VDCSTime::toString($value2);
					break;
				case 'tim':
				default:
					$fieldType='int';
					break;
			}
		}
		if($field && $value1){
			//if(inp('date,time',$fieldType)>0){
			if($fieldType=='int' && $value2) $value2+=(24*60*60-1);
			$re=$value2? '(`'.$field.'` BETWEEN \''.$value1.'\' AND \''.$value2.'\')' : '(`'.$field.'`=\''.$value1.'\')';
		}
		return $re;
	}
	//public static function toSQLSerachTime($field,$fieldType,$date1,$date2=''){return self::sqlSerachTime($field,$fieldType,$date1,$date2);}
	
	
	
	public static $db;
	public static function &dbo(){return static::$db;}
	public static function debug(){debugx(self::dbo()->getTotal());}
	
	// struct
	public static function cfg($k,$v=null){return self::dbo()->cfg($k,$v);}
	public static function cfgTree(){return self::dbo()->cfgTree();}
	
	
	/*
	########################################
	########################################
	*/
	public static function connect($imsg=true){return self::dbo()->doConnect($imsg);}
	public static function disconnect(){return self::dbo()->doDisconnect();}
	
	
	/*
	########################################
	########################################
	*/
	public static function query($sql){return self::dbo()->getQuery($sql);}
	public static function getQuery($sql){return self::dbo()->getQuery($sql);}
	
	public static function getQueryRS($sql,$type=0){return self::dbo()->getQueryRS($sql,$type);}
	public static function queryAry($sql){return self::dbo()->getQueryAry($sql);}
	public static function getQueryAry($sql){return self::dbo()->getQueryAry($sql);}
	
	public static function queryTree($sql){return self::dbo()->getQueryTree($sql);}
	public static function getQueryTree($sql){return self::dbo()->getQueryTree($sql);}
	public static function getQueryTrees($sql,$type=0){return self::dbo()->getQueryTrees($sql,$type);}
	
	public static function queryTable($sql){return self::dbo()->getQueryTable($sql);}
	public static function getQueryTable($sql){return self::dbo()->getQueryTable($sql);}
	
	public static function queryValue($sql,$row=0){return self::dbo()->getQueryValue($sql,$row);}
	public static function getQueryValue($sql,$row=0){return self::dbo()->getQueryValue($sql,$row);}
	public static function queryInt($sql,$row=0){return self::dbo()->getQueryInt($sql,$row);}
	public static function getQueryInt($sql,$row=0){return self::dbo()->getQueryInt($sql,$row);}
	public static function queryNum($sql,$row=0){return self::dbo()->getQueryNum($sql,$row);}
	public static function getQueryNum($sql,$row=0){return self::dbo()->getQueryNum($sql,$row);}
	
	public static function toQueryInt($sTable,$act,$sFields='',$sTerm=''){return self::dbo()->toQueryInt($sTable,$act,$sFields,$sTerm);}
	public static function toQueryNum($sTable,$act,$sFields='',$sTerm=''){return self::dbo()->toQueryNum($sTable,$act,$sFields,$sTerm);}
	
	
	/*
	########################################
	########################################
	*/
	public static function exec($sql){return self::dbo()->doExec($sql);}
	//public static function doExec($sql){return self::dbo()->doExec($sql);}
	//public static function doExecute($sql){return self::dbo()->doExec($sql);}
	public static function isExec($sql){return self::dbo()->isExec($sql);}
	
	public static function execBatch($sql,$sym=null){return self::dbo()->doExecBatch($sql,$sym);}
	//public static function doExecBatch($sql,$sym=null){return self::dbo()->doExecBatch($sql,$sym);}
	
	public static function execInsert($table,$fields,$cDatas,$rDatas=null){return self::dbo()->doExecuteInsert($table,$fields,$cDatas,$rDatas);}
	//public static function doExecuteInsert($table,$fields,$cDatas,$rDatas=null){return self::dbo()->doExecuteInsert($table,$fields,$cDatas,$rDatas);}
	public static function execUpdate($table,$fields,$cDatas,$term='',$rDatas=null){return self::dbo()->doExecuteUpdate($table,$fields,$cDatas,$term,$rDatas);}
	//public static function doExecuteUpdate($table,$fields,$cDatas,$term='',$rDatas=null){return self::dbo()->doExecuteUpdate($table,$fields,$cDatas,$term,$rDatas);}
	
	
	public static function execInsertx($table,$fields,$cDatas,$rDatas=null,$rid=false)
	{
		$re=false;
		$sql=self::sqlInsertx($table,$fields,$cDatas,$rDatas,$rid);
		//if($sql) dcsLog('execInsertx',$sql);
		if($sql) $re=DB::exec($sql);
		return $re;
	}
	public static function execUpdatex($table,$fields,$cDatas,$term='',$rDatas=null,$rid=false)
	{
		$re=false;
		$sql=self::sqlUpdatex($table,$fields,$cDatas,$term,$rDatas,$rid);
		//if($sql) dcsLog('execUpdatex',$sql);
		if($sql) $re=DB::exec($sql);
		return $re;
	}
	
	public static function xinit($sTable,&$tableStruct,&$tableFields,&$fieldid,$rid=false)
	{
		static $structs=array();
		if(!$structs[$sTable]){
			$structs[$sTable]=self::getStructTable($sTable);
			$isread=true;
		}
		$tableStruct=&$structs[$sTable];
		//debugTable($tableStruct);
		$tableStruct->doBegin();
		if($tableStruct->getItemValueInt('primary_key')==1) $fieldid=$tableStruct->getItemValue('name');
		$tableFields=$tableStruct->getValues('name',',');
		//dcsLog($fieldid,$tableFields);
		if(!$rid && $fieldid){
			$tableFields=','.$tableFields.',';
			$tableFields=r($tableFields,','.$fieldid.',','');
			$tableFields=trim($tableFields,',');
		}
		//if($isread) debugx($fieldid.' = '.$tableFields);
	}
	public static function xFields($sTable,&$cFields,$cValues,$rid=false)
	{
		if($cFields) return $cFields;
		if(!isTree($cValues)) return '';
		self::xinit($sTable,$tableStruct,$tableFields,$fieldid,$rid);
		$fielda=array();
		$cValues->doBegin();
		for($t=1;$t<=$cValues->getCount();$t++){
			$field=$cValues->getItemKey();
			if(inp($tableFields,$field)>0){
				array_push($fielda,$field);
			}
			$cValues->doMove();
		}
		$cFields=implode(',',$fielda);
		return $cFields;
	}
	public static function sqlInsertx($sTable,$cFields,$cValues,$rValues=null,$rid=false)
	{
		if(!$sTable || !$cValues) return '';
		$cFields=self::xFields($sTable,$cFields,$cValues,$rid);
		return DB::sqlInsert($sTable,$cFields,$cValues,$rValues);
	}
	public static function sqlUpdatex($sTable,$cFields,$cValues,$sTerm,$rValues=null,$rid=false)
	{
		if(!$sTable || !$cValues) return '';
		$cFields=self::xFields($sTable,$cFields,$cValues,$rid);
		return DB::sqlUpdate($sTable,$cFields,$cValues,$sTerm,$rValues);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function isTableExist($table){return self::dbo()->isTableExist($table);}
	
	public static function doTableClear($table){return self::dbo()->doTableClear($table);}
	public static function doTableDelete($table){return self::dbo()->doTableDelete($table);}
	
	
	/*
	########################################
	########################################
	*/
	public static function insertid(){return self::dbo()->getInsertID();}
	//public static function getInsertID(){return self::dbo()->getInsertID();}
	
	public static function getAffectedRows(){return self::dbo()->getAffectedRows();}
	
	public static function toFetchAry($query,$type=1){return self::dbo()->toFetchAry($query,$type);}
	
	public static function toFetchField($query){return self::dbo()->toFetchField($query);}
	public static function toFetchFieldAry($query){return self::dbo()->toFetchFieldAry($query);}
	public static function toFetchFields($query){return self::dbo()->toFetchFields($query);}
	
	public static function toFetchStruct($query,$iscomment=0){return self::dbo()->toFetchStruct($query,$iscomment);}
	
	public static function getStruct($table,$iscomment=0,$iscache=1){return self::dbo()->getStruct($table,$iscomment,$iscache);}
	public static function getStructTable($table,$iscomment=0,$iscache=1){return self::dbo()->getStructTable($table,$iscomment,$iscache);}
	
	
	/*
	########################################
	########################################
	*/
	public static function getErrorMsg(){return self::dbo()->getErrorMsg();}
	public static function getErrorNo(){return self::dbo()->getErrorNo();}
	
	public static function getTotal(){return self::dbo()->getTotal();}
	public static function getSQLTree(){return self::dbo()->getSQLTree();}
	
}
