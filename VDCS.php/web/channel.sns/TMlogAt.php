<?
class TMlogAt extends TMlog
{
	const AtTableName		= 'db_mat';
	const AtTablePX			= '';
	const AtFieldID			= 'id';
	
	const RowDef			= 10;
	
	
	/*
	########################################
	########################################
	*/
	public static function save($ua,$rootid,$talkid,$content,$ouids='')
	{
		$uidAr=array();
		//$content='hahah @test @finco hehe';
		$_matches=TAt::toMatches($content);
		//debuga($_matches);
		for($m=0;$m<count($_matches[0]);$m++){
			//$_matches[0][$m]
			$uvalue=trim($_matches[1][$m]);
			//debugs($uvalue);
			TAt::values($uvalue,$uid,$unames);
			//debugvc($uvalue.': '.$uid.'='.$unames);
			$uidre=0;
			if($uid>0) $uidre=self::add($ua,$rootid,$talkid,$uid,$unames,$ouids);
			if($uidre>0) array_push($uidAr,$uid);
		}
		return implode(',',$uidAr);
	}
	
	public static function add($ua,$rootid,$talkid,$uid,$unames,$ouids='')
	{
		$re=0;
		//debugx($uid.'=='.$ouids);
		if($uid>0 && inp($ouids,$uid,',')<1){
			$tData=newTree();
			$tData->addItem('rootid',$rootid);
			$tData->addItem('dataid',$talkid);
			$tData->addItem('uurc',$ua->rc);
			$tData->addItem('uuid',$uid);		//$ua->id
			$tData->addItem('status',1);
			$tData->addItem('tim',DCS::timer());
			$FieldsAdd='rootid,dataid,uurc,uuid,status,tim';
			$sql=DB::sqlInsert(self::AtTableName,$FieldsAdd,$tData);
			$isexec=DB::exec($sql);
			if($isexec){
				$re=$uid;
				TNoticeAction::countAt($ua,[	//发布文章或微文at时会用到
					'rootid'=>$rootid,
					'talkid'=>$talkid,
					'atuid'=>$uid,
				'-'=>'-']);
			}
			/*
			if($isexec && $talkid>0){
				$re=$uid;
				TNoticeAction::countAtComment($ua,[	//评论时at会用到
					'rootid'=>$rootid,
					'talkid'=>$talkid,
					'atuid'=>$uid,
				'-'=>'-']);
			}
			*/
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function querier($type,$uid,&$p)
	{
		$total=self::getTotal($type,$uid);
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);
			//$p->setPage($page);
		}
		$p->setTotal($total);
		$p->doParse();
		$tableData=self::getList($type,$uid,$p->getPage(),$p->getListNum());
		return $tableData;
	}
	
	/*获取at的文章和微文*/
	public static function getAttext($uid)
	{
		$sql='select * from db_mat where uuid='.$uid.' and dataid=0';
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	/*获取at我的评论*/
	public static function getAtComment($uid)
	{
		$sql='select * from db_mat where uuid='.$uid.' and dataid>0';
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getTotal($type,$uid)
	{
		switch($type){
			case 'log':
			case 'root':
				$sql='select count(*) from '.self::AtTableName.' where dataid<1 and uuid='.$uid.'';
				break;
			case 'comment':
				$sql='select count(*) from '.self::AtTableName.' where dataid>0 and uuid='.$uid.'';
				break;
			case 'all':
			default:
				$sql='select count(*) from '.self::AtTableName.' where uuid='.$uid.'';
				break;
		}
		//debugx($sql);
		return DB::queryInt($sql);
	}
	public static function getList($type,$uid,$page,$row=self::RowDef)
	{
		switch($type){
			case 'log':
			case 'root':
				$sql='select * from '.self::AtTableName.' where dataid<1 and uuid='.$uid.' order by tim desc';
				break;
			case 'comment':
				$sql='select * from '.self::AtTableName.' where dataid>0 and uuid='.$uid.' order by tim desc';
				break;
			case 'all':
			default:
				$sql='select * from '.self::AtTableName.' where uuid='.$uid.' order by tim desc';
				break;
		}
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;//每页显示数量
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getByRID($ids)
	{
		$query='rootid in ('.$ids.')';
		return self::get($query,'',0,0);
	}
	
	public static function get($query,$mode='',$value=0,$row=self::RowDef)
	{
		$sqlQuery='';	//'status=1'
		$sqlOrder='tim desc';
		$sqlQuery=$query;
		if($value){
			switch($mode){
				case 'new':
					$sqlQuery=DB::sqla($sqlQuery,'tim>'.$value);
					$row=0;
					break;
				case 'next':
				default:
					$sqlQuery=DB::sqla($sqlQuery,'id<'.$value);
					break;
			}
		}
		$sql=DB::sqlSelect(self::AtTableName,'','*',$sqlQuery,$sqlOrder,$row);
		debugx($sql);
		$tableData=DB::queryTable($sql);
		self::doDataFilter($tableData);
		return $tableData;
	}
	
	public static function doDataFilter(&$tableData)
	{
		$tableData->doAppendFields('time,time_up');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('time',VDCSTime::toString($tableData->getItemValueInt('tim')));
			$tableData->setItemValue('time_up',VDCSTime::toString($tableData->getItemValueInt('tim_up')));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function updateStatus($ids,$status=2)
	{
		if($ids){
			switch($status){
				case 'read':		$status=2;break;
				case 'new':		$status=1;break;
				case 'del':		$status=0;break;
			}
			if(!is_int($status)) $status=1;
			$sql='update '.self::AtTableName.' set status='.$status.' where id in ('.$ids.')';
			$isexec=DB::exec($sql);
		}
		return $isexec;
	}
	
}
?>