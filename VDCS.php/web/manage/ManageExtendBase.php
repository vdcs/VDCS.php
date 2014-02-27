<?
class ManageExtendBase
{
	/*
	public static function toUaID($name,$email=''){return $GLOBALS['ua']->getInfoID($name,$email);}
	public static function toUaName($id){return $GLOBALS['ua']->getInfoName($id);}
	*/
	
	protected static function toUserSearchQuery($key='username',$querydef=false)
	{
		global $mpo;
		$re='';
		if(!$key) $key='username';
		if($mpo->s->field==$key){
			$re='uuid in (select uid from '.self::userRes('table.name').' where '.r($mpo->s->getQuery(),$key,'name').')';
		}
		else{
			if($querydef) $re=$mpo->s->getQuery();
		}
		return $re;
	}
	
	
	public static function isUpdateTotalTerm($params='')
	{
		global $mpo;
		$re=false;
		if($mpo->action=='list'){
			$queryString=queryString();
			if(len($params)>0) $params='&'.$params;
			$basePortal='portals='.$mpo->getChannel();
			if(len($mpo->_p_)>0) $basePortal.='&portal='.$mpo->_p_;
			$qstr=$basePortal.$params;
			//debugx(($queryString);
			//debugx(($qstr);
			if($queryString==$qstr) $re=true;
			else{
				$qstr=$basePortal.'&action=list'.$params;
				//debugx(($qstr);
				if($queryString==$qstr) $re=true;
			}
		}
		return $re;
	}
	
	
	
}
