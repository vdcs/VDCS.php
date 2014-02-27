<?
class AppsUa
{
	const TABLE_NAME			= 'dbu_apps';
	
	public static function sqlQuery($ua,$appid,$query=null)
	{
		if(!$query) $query='uurc='.DB::q($ua->rc,1).' and uuid='.DB::q($ua->id,1);
		$sqlQuery=DB::sqla($query,'appid='.DB::q($appid,1));
		return $sqlQuery;
	}
	public static function getTree($ua,$appid,$query=null)
	{
		$reTree=newTree();
		$sqlQuery=self::sqlQuery($ua,$appid,$query);
		$sql=DB::sqlSelect(self::TABLE_NAME,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$reTree=DB::queryTree($sql);
		return $reTree;
	}

	public static function isbind($ua,$appid)
	{
		$treeUa=self::getTree($ua,$appid);
		return $treeUa->getCount()>0?true:false; 
	}
	
	public static function bind($ua,$appid,$appUa)
	{
		if(self::isbind($ua,$appid)) return 2;		//已绑定
		$tData=newTree();
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('appid',$appid);
		$tData->addItem('uid',$appUa->getItem('uid'));
		$tData->addItem('name',$appUa->getItem('name'));
		$tData->addItem('email',$appUa->getItem('email'));
		//debugTree($tData);
		$sql=DB::sqlInsert(self::TABLE_NAME,'',$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		return $isexec;
	}
	
	public static function unbind($ua,$appid)
	{
		if(!self::isbind($ua,$appid)) return -1;		//未绑定
		$sqlQuery=self::sqlQuery($ua,$appid,$query);
		$sql=DB::sqlDelete(self::TABLE_NAME,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		return $isexec;
	}
	
}
