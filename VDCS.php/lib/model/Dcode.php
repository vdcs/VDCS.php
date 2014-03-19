<?
class Dcode
{
	const TABLE_NAME				= 'dbd_dcode';
	
	
	public static function create($types,$module,$code,$value,$params=null)
	{
		if(!$params) $params=newTree();
		$re=0;
		$tData=newTree();
		$tData->addItem('types',$types);
		$tData->addItem('module',$module);
		$tData->addItem('uurc',$params->getItem('uurc'));
		$tData->addItem('uuid',$params->getItem('uuid'));
		$tData->addItem('code',$code);
		$tData->addItem('value',$value);
		$tData->addItem('value1',$params->getItem('value1'));
		$tData->addItem('value2',$params->getItem('value2'));
		$tData->addItem('value3',$params->getItem('value3'));
		$tData->addItem('value4',$params->getItem('value4'));
		$tData->addItem('value5',$params->getItem('value5'));
		$tData->addItem('sp_ip',DCS::ip());
		$tData->addItem('sp_agent',DCS::agent());
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$sql=DB::sqlInsert(self::TABLE_NAME,'',$tData);
		$isexec=DB::exec($sql);
		if($isexec) $re=1;
		return $re;
	}
	
	public static function getTree($types,$module,$code,$value='',$params=null)
	{
		if(!$params) $params=newTree();
		$sqlQuery='types='.DB::q($types,1);
		$sqlOrder='tim desc';
		if($module) $sqlQuery=DB::sqla($sqlQuery,'module='.DB::q($module,1));
		if($code) $sqlQuery=DB::sqla($sqlQuery,'code='.DB::q($code,1));
		if($value) $sqlQuery=DB::sqla($sqlQuery,'value='.DB::q($value,1));
		$sql=DB::sqlSelect(self::TABLE_NAME,'','*',$sqlQuery,$sqlOrder,1);
		return DB::queryTree($sql);
	}
	
	
	public static function used($types,$module,$code,$value='',$params=null)
	{
		if(!$params) $params=newTree();
		$re=0;
		$sqlQuery='types='.DB::q($types,1);
		if($module) $sqlQuery=DB::sqla($sqlQuery,'module='.DB::q($module,1));
		if($code) $sqlQuery=DB::sqla($sqlQuery,'code='.DB::q($code,1));
		if($value) $sqlQuery=DB::sqla($sqlQuery,'value='.DB::q($value,1));
		$tData=newTree();
		$tData->addItem('status',2);
		$tData->addItem('tim_use',DCS::timer());
		$sql=DB::sqlUpdate(self::TABLE_NAME,'',$tData,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec==1) $re=1;
		return $re;
	}
	
}
?>