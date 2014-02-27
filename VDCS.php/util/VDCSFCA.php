<?
class VDCSFCA
{

	public static function querier(&$p=null,$params=array())
	{
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum($params['listnum']?$params['listnum']:($params['listnum_def']?$params['listnum_def']:10));
			$p->setPage(queryi('page'));
		}
		if($params['url']) $p->setConfig('url',$params['url']);
		if($params['page']) $p->setPage($params['page']);
		if($params['fieldid']) $p->setDB('id',$params['fieldid']);
		if($params['fields']) $p->setDB('field',$params['fields']);
		$sqlQuery=DB::sqla($p->getDB('query'),$params['query']);
		$p->setDB('query',$sqlQuery);
		$p->setDB('order',$params['order']);
		$p->setDB('table',$params['table']);
	}
	
}
?>