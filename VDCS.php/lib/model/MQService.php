<?
class MQService
{
	const PRIORITY_BASE			= 20;

	public static function getTree()
	{
		$sql='select * from '.self::TABLE_NAME.' where status<1 order by tim asc limit 0,1';
		return DB::queryTree($sql);
	}
	
	
	public static function push($module,$action,$vars,$timespace=0,$content=null,$tData=null,$priority=0)
	{
		if(!$tData) $tData=newTree();
		$tData->addItem('module',$module);
		$tData->addItem('action',$action);
		if($vars) $tData->addItem('vars',$vars);
		if($timespace) $tData->addItem('timespace',$timespace);
		if($content) $tData->addItem('content',$content);
		if($priority) $tData->addItem('priority',$priority);
		return self::create($tData);
	}
	
	public static function create($tData)
	{
		$ret=[];
		$ret['status']=0;
		$sql=DB::sqlInsertx(MQueue::TABLE_NAME,null,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$ret['status']=$isexec;
		$ret['newid']=DB::insertid();
		return $ret;
	}
	
	public static function setInit($pid){self::setStatus($pid,0,'tim');}
	public static function setStart($pid){self::setStatus($pid,3,'tim_start');}
	public static function setOver($pid){self::setStatus($pid,5,'tim_over');}
	public static function setStatus($pid,$status,$timField='tim_over')
	{
		$sql='update '.MQueue::TABLE_NAME.' set status='.$status.','.$timField.'='.DCS::timer();
		$sql.=' where id='.$pid;
		DB::exec($sql);
	}
	
}
?>