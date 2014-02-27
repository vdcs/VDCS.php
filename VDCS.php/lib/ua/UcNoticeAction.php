<?
class UcNoticeAction extends UcNoticeBase
{
	
	/*
	########################################
	########################################
	*/
	public static function count($ua,$params)		//uid	Notice
	{
		$uid=$params['uid']?$params['uid']:$ua->id;
		//dcsLog('uuid',$uid.','.$params['uid']);
		self::sets($ua,'{tpx}new_notice={tpx}new_notice+1',toi($uid));
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function initParamsQuery($ua,&$params)
	{
		if(!$ua) return false;
		$params=$params||[];
		isset($params['uurc']) || $params['uurc']=$ua->rc;
		isset($params['uuid']) || $params['uuid']=$ua->id;
		return true;
	}
	
	public function send($ua,$message,$params=null)
	{
		if(!self::initParamsQuery($ua,$params)) return false;
		if(!$message) return 2;
		isset($params['status']) || $params['status']=1;
		isset($params['tim']) || $params['tim']=DCS::tim();
		$re=false;
		$tData=newTree();
		$tData->addItem('sorts',$params['sorts']);
		$tData->addItem('types',$params['types']);
		$tData->addItem('uurc',$params['uurc']);
		$tData->addItem('uuid',$params['uuid']);
		$tData->addItem('icon',$params['icon']);
		$tData->addItem('message',$message);
		$tData->addItem('status',$params['status']);
		$tData->addItem('tim',$params['tim']);
		//debugTree($tData);
		$tableFields='sorts,types,uurc,uuid,icon,message,status,tim';
		$sql=DB::sqlInsert(self::TableName,$tableFields,$tData);
		//debugx($sql);
		//dcsLog('UcNoticeAction::send',$sql);
		$_status=DB::exec($sql);
		if($_status){
			self::count($ua,['uid'=>$params['uuid']]);
			$re=1;
		}
		return $re;
	}
	
	
	public function status($ua,$type,$ids,$params=null)
	{
		if(!self::initParamsQuery($ua,$params)) return false;
		if(!$ids) return 2;
		$re=false;
		$query='uuid='.$params['uuid'].' and '.self::FieldID.' in ('.$ids.')';
		switch($type){
			case 'read':		$sets='tim_read='.DCS::tim().'';break;
			case 'new':		$sets='tim_read=0';break;
		}
		if(!$sets) return 3;
		$sql=DB::sqlUpdate(self::TableName,'*',$sets,$query);
		//debugx($sql);
		$_status=DB::exec($sql);
		if($_status){
			$re=1;
		}
		//debugx($re);
		return $re;
	}
	
	public function delete($ua,$ids,$params=null)
	{
		if(!self::initParamsQuery($ua,$params)) return false;
		if(!$ids) return 2;
		$re=false;
		$query='uuid='.$params['uuid'].' and '.self::FieldID.' in ('.$ids.')';
		$sql=DB::sqlDelete(self::TableName,$query);
		//debugx($sql);
		$_status=DB::exec($sql);
		if($_status){
			$re=1;
		}
		//debugx($re);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	/*
	public static function set($ua,$fields,$values,$uid=1)
	{
		return $ua->set($fields,$values,$uid);
	}
	public static function setx($ua,$fields,$values=0,$uid=1)
	{
		$ua->setData($fields,$value);
		if(is_string($fields)){
			$field='{tpx}'.$fields;
			$field=r($field,'{tpx}',$ua->TablePX);
			$fields=[$field];
			$values=[$field=>$values];
		}
		return self::set($ua,$fields,$values,$uid);
	}
	*/
	public static function sets($ua,$sets,$uid=1)
	{
		return $ua->update($sets,$uid);
	}
	
}
