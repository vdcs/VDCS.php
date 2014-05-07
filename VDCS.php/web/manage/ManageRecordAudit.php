<?
class ManageRecordAudit extends StructData
{
	const TableName			= 'dbd_record_audit';
	const TablePX			= '';
	const FieldID			= 'id';
	
	public static function create($ma,$tData)
	{
		$tData->addItem('umrc',$ma->rc);
		$tData->addItem('umid',$ma->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('status',1);
		return self::add($tData);
	}
	
	public static function view($sqlTerm=''){
		$treeRS=newTree();
		if(!$sqlTerm) return $treeRS;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlTerm,'',1);
		$treeRS=DB::queryTree($sql);
		self::doFilterTree($treeRS);
		return $treeRS;
	}
	public static function doFilterTree(&$treeRS)
	{
		if($treeRS->getCount()<0) return;
		$tim=$treeRS->getItem('tim');
		$treeRS->addItem('time',datei('Y-m-d H:i:s',$tim));
		$umid=$treeRS->getItemInt('umid');
		$umrc=$treeRS->getItem('umrc');
		//$maname=DB::queryValue('select name from dbs_manager where id='.$umid);
		$ary=[];
		$ary=DB::queryAry('select m.name,s.names from dbs_manager m left join dba_staff s on m.uuid=s.staffid where m.id='.$umid);
		//$treeRS->addItem('manname',$maname);
		$treeRS->addItem('manname',$ary['name']);//管理员名
		$treeRS->addItem('staffnames',$ary['names']);//员工名
	}
	

	public static function query($sqlTerm='',$order='',$limit=0){
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=self::doFilterData(DB::queryTable($sql));
		return $tableData;
	}
	public static function doFilterData($tableData)
	{
		$tableData->doAppendFields('name,time,status.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$umid=$tableData->getItemValue('umid');
			$name=DB::queryTree('select name from dbs_manager where id='.$umid.'')->getItem('name');
			$tableData->setItemValue('name',$name);
			$tim=$tableData->getItemValue('tim');
			$tableData->setItemValue('time',datei('Y-m-d',$tim));
			$status=$tableData->getItemValue('status');
			switch($status){
				case 0:
					$sstatus='待审核';
					break;
				case 2:
					$sstatus='审核不通过';
					break;
				default:
					$sstatus='审核通过';
					break;			
			}
			$tableData->setItemValue('status.name',$sstatus);
		}
		return $tableData;
	}
	
}

?>