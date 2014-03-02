<?
class MQueue
{
	const TABLE_NAME='mq_queue';

	public static function preHandler($send,&$qtree=null,&$arrVars=null)
	{
		if($qtree && $arrVars) return true;
		$qtree=newTree();
		if(!iso($send)){
			$qid=toInt($send);
			$qtree=DB::queryTree('select * from '.self::TABLE_NAME.' where id='.DB::q($qid,1));
			if($qtree->getCount()<1) return false;
		}else{
			$qtree=$send;
			$qid=$qtree->getItem('id');
		}
		$vars=$qtree->getItem('vars');
		$arrVars=array();
		$arrVars=VDCSData::deCode($vars,true);
		return true;
	}

}
