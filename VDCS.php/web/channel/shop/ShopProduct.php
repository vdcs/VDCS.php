<?
class ShopProduct
{
	const TableName			= 'db_shop_product';
	const TablePX			= '';
	const FieldID			= 'p_id';
	const TableFields		= 'orderid,srvid,classid,brandid,p_key,p_topic,p_subtopic,p_name,p_brand,p_serial,p_prop1,p_prop2,p_prop3,p_prop4,p_prop5,p_price,p_price_old,p_price_market,p_price_trade,p_unit,p_weight,p_amounts,p_amount,p_discount,p_discount_group,p_discount_time_start,p_discount_time_end,p_con_emoney,p_con_points,p_con_score,p_con_gift,p_pic,p_summary,p_remark,sp_code,sp_defined,sp_keyword,sp_attr,sp_auction,sp_mode,sp_popedom,sp_emoney,sp_points,sp_score,sp_poll_agree,sp_poll_oppose,p_isnew,p_issale,p_ishot,p_isgood,p_istop,p_status,p_tim,p_tim_up,p_total_view,p_total_order,p_total_buy,p_total_day,p_total_week,p_total_month,p_total_comment,p_total_fav,p_total_regard,p_total_data';
	
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
	
	public static function getTree($v)
	{
		$sqlQuery=isInt($v)?(self::FieldID.'='.$v):$v;
		$treeRs=newTree();
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRs=DB::queryTree($sql);
		self::doFilterTree($treeRs);
		//if($treeRs->getCount()<1) return newTree();
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
	
}
?>