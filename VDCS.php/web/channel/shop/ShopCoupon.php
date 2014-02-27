<?php
class ShopCoupon
{
	const TableName			= 'db_coupon';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'no,uurc,uuid,type,code,p_id,p_type,money,price_lowest,limit,isuse,status,date,expire_date,use_tim,use_type,use_value';
	
	public static function add($ua,&$tData)
	{
		$_status=0;
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('tim',DCS::time());
		$tData->addItem('tim_up',DCS::time());
		
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		
		$id=DB::insertid();
		$tData->addItem(self::FieldID,$id);
		
		if($isexec) $_status=1;
		return $_status;	
	}
	
	public static function del($ua,$sqlTerm='')
	{
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.DB::q($ua->id,1));
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function edit($tData,$fields,$sqlTerm)
	{
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlUpdate(self::TableName,$fields,$tData,$sqlTerm);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	
	public static function getTree($sqlTerm)
	{
		$treeRs=newTree();
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlTerm,'',1);
		$treeRs=DB::queryTree($sql);
		self::doFilterTree($treeRs);
		if($treeRs->getCount()<1) return newTree();
		return $treeRs;
	}
	
	public static function doFilterTree(&$treeRs)
	{
		
	}
	
	public static function getTotal($sqlTerm='',$order='',$limit=0)
	{
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlTerm,$order,$limit);
		return DB::queryInt($sql);
	}
	
	public static function query($sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		debugx($sql);
		$tableData=DB::queryTable($sql);
		//self::doFilterData($tableData);
		return $tableData;
	}
	
	public static function doFilterData(&$tableData)
	{
		
	}
	
	public static function queryData($sqlTerm='',$order='',&$p)
	{
		$tableData=newTable();
		$total=self::getTotal($sqlTerm);//获取总条数
		if($total==0) return $tableData;
		
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
		}	
		$p->setTotal($total);	
		$p->doParse();
		$row=$p->getListNum();
		$page=$p->getPage();
		
		$sqlQuery=$sqlTerm;
		if($order) $sqlQuery.=' order by '.$order;
		if($row>0) $sqlQuery.=' limit '.(($page-1)*$row).','.$row;//添加分页的条件
		
		$tableData=self::query($sqlQuery);
		return $tableData;
	}
	
	public static function check($id,$code,$cartid,$sqlTerm='')
	{
		$sqlQuery=DB::sqla($sqlTerm,'code='.DB::q($code,1));
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlQuery);
		$_exists=DB::queryInt($sql);
		if($_exists) ShopCart::addCoupon($cartid,$id);
		return $_exists;
	}
	
	public static function checkOrder($id,$code)
	{
		$sqlQuery=DB::sqla('id='.DB::q($id,1),'code='.DB::q($code,1));
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlQuery);
		$_exists=DB::queryInt($sql);
		return $_exists;
	}
	
	
	public static function getCoupons(&$tableData)
	{
		$tableData->doAppendFields('discount');
		$tableData->doBegin();
		while($tableData->isNext()){
			$discount=0;
			$cid=$tableData->getItemValue('cid');
			if($cid){
				$cTree=newTree();
				$cTree=ShopCoupon::getTree('id='.DB::q($cid,1));
				$date_begin=$cTree->getItem('date');
				$date_expire=$cTree->getItem('date_expire');
				$timer=DCS::timer();
				if($timer<=strtotime($date_expire) && $timer>=strtotime($date_begin)){
					$discount=$cTree->getItemNum('money');
				}
			}
			$tableData->setItemValue('discount',$discount);
		}	
	}
	
}
?>