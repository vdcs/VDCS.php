<?
class OauthsToken extends Oauths
{
	const TABLE_TOKEN_FIELDS_ADD		= 'sid,uurc,uuid,appid,key,encrypt,token,token2,scope,value1,value2,value3,value4,value5,ip,session,timer,status,tim';
	
	
	public static function add($tData,$opt)
	{
		$sqlQuery='tim<'.($opt['tim']-3600).' or (uurc='.DB::q($opt['uurc'],1).' and uuid='.DB::q($opt['uuid'],1).' and appid='.DB::q($opt['appid'],1).')';
		$sql=DB::sqlDelete(self::TABLE_TOKEN_NAME,$sqlQuery);
		DB::exec($sql);
		$sql=DB::sqlInsert(self::TABLE_TOKEN_NAME,self::TABLE_TOKEN_FIELDS_ADD,$tData);
		//debugx($sql);
		DB::exec($sql);
		return DB::insertid();
	}
	
	
}
