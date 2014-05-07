 <?php
class TicketAnswer{
	const TableName			= 'db_ticket_answer';
	const TablePX			= '';
	const FieldID			= 'a_id';
	const TableFields		='q_id,uuid,uurc,a_content,a_remark,a_field,a_status,a_tim,a_istopic,a_explain';
	

	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		if($id<1) return $treeRS;
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=self::doFilterTree(DB::queryTree($sql));
		return $treeRS;
	}
	//添加
	public static function addData($ua,&$tData)
	{
		$_status=0;
		$tim=DCS::timer();
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('a_status',1);
		$tData->addItem('a_tim',$tim);
		$_status=self::add($tData);
		
		//改变question状态
		$q_id=$tData->getItem('q_id');
		$q_process=$tData->getItem('q_process');

		$_status=TicketQuestion::changeProcess($q_id,$q_process);

		return $_status;
	}
	//添加
	public static function add(&$tData)
	{	
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
	public static function query($ua,$sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		self::doFilterData($tableData,$ua);
		return $tableData;
	}
	
	//查询
	public static function queryData($ua,$sqlTerm='',$order='',&$p)
	{
		$tableData=newTable();

		//$sqlQuery=DB::sqla($sqlTerm);
		$sqlQuery=$sqlTerm;
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
		$tableData=self::query($ua,$sqlQuery);
		return $tableData;
	}
	protected function doFilterData(&$tableData,$ua)
	{
		$tableData->doAppendFields('isme,names,pos,service');
		//$names=$ua->getNames();
		//isme
		//uuid,uurc  $ua->id $ua->rc
		//if($uuid== $ua->id && $uurc==$ua->rc)
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$uuid=$tableData->getItemValue('uuid');
			$uurc=$tableData->getItemValue('uurc');
			$tableData->setItemValue('names',$names);
			if($uuid==$ua->id && $uurc==$ua->rc){
				$tableData->setItemValue('isme','me');
				$tableData->setItemValue('pos','right');
				$tableData->setItemValue('service','用户问题:');
			}else{
				$tableData->setItemValue('isme','others');
				$tableData->setItemValue('pos','left');
				$tableData->setItemValue('service','客服回答:');
			} 
		}
		
	}
	
}

?>