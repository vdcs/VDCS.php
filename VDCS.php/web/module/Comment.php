 <?php
class ProductsComment{
	const TableName			= 'db_comment';
	const TablePX			= '';
	const FieldID			= 'c_id';
	const TableFields		='uuid,uurc,productsid,c_content,c_topic,type,c_score,c_status,c_tim';
	
	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		if($id<1) return $treeRS;
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery);
		$treeRS=DB::queryTree($sql);
		//$treeRS=self::doFilterTree($treeRS);
		return $treeRS;
	}
	
	protected function doFilterTree($treeRS)
	{
		
	}
	//添加
	public static function addData($ua,&$tData)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('c_status',1);
		$tData->addItem('c_tim',DCS::timer());
		//$tData->addItem('q_priority',0);
		$_status=self::add($tData);
		return $_status;
	}
	
	//添加
	public static function add(&$tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		
		if($isexec) $_status=1;
	
		return $_status;
	}
	public static function getTotal($sqlTerm='',$order='',$limit=0)
	{
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlTerm,$order,$limit);
		return DB::queryInt($sql);
	}
	//查询
	public static function query($sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		//debugx($sql);
		$tableData=DB::queryTable($sql);
		//self::doFilterData($tableData,$ua);
		return $tableData;
		
	}
	
	//查询
	public static function queryData($ua,$sqlTerm='',$order='',&$p)
	{
		$tableData=newTable();

		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$total=self::getTotal($sqlQuery);//获取总条数
		if($total==0) return $tableData;
		
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
		}	
		$p->setTotal($total);	
		$p->doParse();
		$row=$p->getListNum();
		$page=$p->getPage();
		if($order) $sqlQuery.=' order by '.$order;
		if($row>0) $sqlQuery.=' limit '.(($page-1)*$row).','.$row;//添加分页的条件
		$tableData=self::query($sqlQuery);
		return $tableData;
	}
	/*protected function doFilterData(&$tableData){
		$tableData->doAppendFields('process.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$q_process=$tableData->getItemValue('q_process');
			switch($q_process){
				case 1:
					$process='已回答';
					break;
				case 0:
					$process='待回答';
					break;
				case 2:
					$process='已补充';
					break;
				default:
					$process='问题结束';
					break;
			}
				$tableData->setItemValue('process.name',$process);
		}
		return $tableData;
	}

	public static function changeProcess($id,$process)
	{
		$_status=0;
		$tData=newTree();
		$tData->addItem('q_process',$process);
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,'q_process',$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;
	}*/

}
?>