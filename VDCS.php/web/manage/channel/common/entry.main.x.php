<?
class PagePortal extends PortalCommonBaseX
{
	
	protected function parseStat()
	{
		$ua=Ua::instance(APP_UA);
		$uTable=$ua->TableName;
		unset($ua);
		$total_account=self::getStatistics($uTable,'*');
		$total_assets=self::getStatistics($uTable,'money','sum');
		$new_account=self::getStatistics($uTable,'*','count','tim',DCS::today());
		//$total_products=self::getStatistics('db_products','*');
		//$new_products=self::getStatistics('db_products','*','count','tim',DCS::today());
		$total_amount=self::getStatistics('dbu_bill','moneys','sum',0,0,0,'module='.DB::q('products',1));
		$actual_amount=self::getStatistics('dbu_bill','money','sum',0,0,0,'module='.DB::q('products',1));
		if($total_amount) $per_outer_amount=round((1-$actual_amount/$total_amount),4)*100;
		else $per_outer_amount=0;
		$total_amount_today=self::getStatistics('dbu_bill','moneys','sum','tim',DCS::today(),0,'module='.DB::q('products',1));
		$actual_amount_today=self::getStatistics('dbu_bill','money','sum','tim',DCS::today(),0,'module='.DB::q('products',1));
		if($total_amount_today) $per_outer_amount_today=round((1-$actual_amount_today/$total_amount_today),4)*100;
		else $per_outer_amount_today=0;
		$tree=newTree();
		$tree->addItem('total_account',$total_account);
		$tree->addItem('total_assets',$total_assets);
		$tree->addItem('new_account',$new_account);
		$tree->addItem('total_products',$total_products);
		$tree->addItem('new_products',$new_products);
		$tree->addItem('total_amount',$total_amount);
		$tree->addItem('actual_amount',$actual_amount);
		$tree->addItem('per_outer_amount',$per_outer_amount);
		$tree->addItem('total_amount_today',$total_amount_today);
		$tree->addItem('actual_amount_today',$actual_amount_today);
		$tree->addItem('per_outer_amount_today',$per_outer_amount_today);
		$this->addVarTree($tree,'stat.');
		
		$this->setSucceed();
	}
	
	//获取总数
	public static function getStatistics($table,$getfield,$act='count',$timfield=0,$date_begin=0,$date_end=0,$sqlTerm='')
	{
		$sqlQuery=$sqlTerm;
		if($timfield && $date_begin && !$date_end) $sqlQuery=DB::sqla($sqlTerm,$timfield.'> UNIX_TIMESTAMP('.DB::q($date_begin,1).')');//>begin
		if($timfield && $date_end &&!$date_begin) $sqlQuery=DB::sqla($sqlTerm,$timfield.'< UNIX_TIMESTAMP('.DB::q($date_end,1).')');//<end
		if($timfield && $date_begin && $date_end) $sqlQuery=DB::sqla($sqlTerm,$timfield.' between UNIX_TIMESTAMP('.DB::q($date_begin,1).') and UNIX_TIMESTAMP('.DB::q($date_end,1).')');
		$sql=DB::sqlSelect($table,$act,$getfield,$sqlQuery);
		$total=DB::queryNum($sql);
		return $total;
	}
	
	
}
