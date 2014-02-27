<?
class OauthsCode extends Oauths
{
	const TABLE_CODE_FIELDS_ADD		= 'sid,uurc,uuid,appid,code,token,state,value1,value2,value3,value4,value5,ip,session,timer,status,tim';
	
	
	public static function getTree($code)
	{
		$sqlQuery='status=1 and code='.DB::q($code,1);
		$sql='select * from '.self::TABLE_CODE_NAME.' where '.$sqlQuery;
		//debugx($sql);
		//dcsLog('Oauth2Action::getCodeTree',$sql);
		$treeDat=DB::queryTree($sql);
		return $treeDat;
	}
	public static function used($code,$opt=null)
	{
		if(!$code) return;
		if(!$opt) $opt=array();
		$opt['tim']=DCS::tim();
		$tData=newTree();
		$tData->addItem('status',2);
		$tData->addItem('tim_use',$opt['tim']);
		$sqlQuery='code='.DB::q($code,1).'';
		$sql=DB::sqlUpdate(self::TABLE_CODE_NAME,'',$tData,$sqlQuery);
		DB::exec($sql);
		return true;
	}
	
	public static function add($tData,$opt)
	{
		$sqlQuery='tim<'.($opt['tim']-3600).' or (uurc='.DB::q($opt['uurc'],1).' and uuid='.DB::q($opt['uuid'],1).' and appid='.DB::q($opt['appid'],1).')';
		$sql=DB::sqlDelete(self::TABLE_CODE_NAME,$sqlQuery);
		DB::exec($sql);
		$sql=DB::sqlInsert(self::TABLE_CODE_NAME,self::TABLE_CODE_FIELDS_ADD,$tData);
		//debugx($sql);
		DB::exec($sql);
		return DB::insertid();
	}
	
}
