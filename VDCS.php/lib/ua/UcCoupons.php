<?php
class UcCoupons
{
	const TableName			= 'dbu_coupon';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'module,moduleid,uurc,uuid,no,type,code,money,price_lowest,limit,date,date_expire,isuse,use_tim,use_type,use_value,status,tim';
	const TableDataName		= 'dbu_couponi';
	const TableDataFields		= 'module,moduleid,uurc,uuid,no,moneys,money,status,tim,tim_up';
	
	public static function add($ua,&$tData)
	{
			
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
	
	
	public static function getTree($sqlQuery)
	{
		$treeRs=newTree();
		$date=DCS::today();
		//$sqlQuery=DB::sqla($sqlTerm,'date<='.DB::q($date,1).' and date_expire>='.DB::q($date,1));
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRs=DB::queryTree($sql);
		if($treeRs->getCount()<1) return newTree();
		self::doFilterTree($treeRs);
		return $treeRs;
	}
	
	public static function getCouponInfo($sqlTerm)
	{
		$treeRs=newTree();
		$date=DCS::today();
		$sqlQuery=DB::sqla($sqlTerm,'date<='.DB::q($date,1).' and date_expire>='.DB::q($date,1));
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRs=DB::queryTree($sql);
		if($treeRs->getCount()<1) return newTree();
		self::doFilterTree($treeRs);
		return $treeRs;
	}
	
	public static function doFilterTree(&$treeRs)
	{
		if($treeRs->getCount()<1) return;
	}
	
	public static function query($sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	public static function doFilterData(&$tableData)
	{
		
	}
	
	public static function check($ua,&$tData)
	{
		$_status=0;
		$type=$tData->getItem('type');
		if($type==1){
			$_status=self::checkCart($ua,$tData);
		}else if($type==2){
			$_status=self::checkOrder($ua,$tData);
		}
		return $_status;
	}
	
	public static function checkCart($ua,&$tData)
	{
		$_status=1;
		$cid=$tData->getItem('cid');
		$cartid=$tData->getItem('id');
		$code=$tData->getItem('code');
		
		//获取购物车信息
		$sql=self::getCanUseSql($ua,$tData);
		
		//获取优惠券信息
		$treeRS=newTree();
		$treeRS=DB::queryTree($sql);
		$exist=$treeRS->getCount();
		if($exist){
			$moduleid=$tData->getItem('cart.moduleid');
			$module=$tData->getItem('cart.module');
			
			//限制次数
			$no=$treeRS->getItem('no');
			$limit=$treeRS->getItemInt('limit');
			if($limit){
				$limit_sqlTerm=DB::sqla('uuid='.DB::q($ua->id,1),'no='.DB::q($no,1));
				$limit_sql=DB::sqlSelect(self::TableDataName,'count','*',$limit_sqlTerm);
				$usetimes=DB::queryInt($limit_sql);
				if($usetimes>$limit) return false;
			}
		
			$vData=newTree();
			$vData->addItem('no',$no);
			$vData->addItem('module',$module);
			$vData->addItem('moduleid',$moduleid);
			$vData->addItem('moneys',$tData->getItem('cart.moneys'));
			$vData->addItem('money',$tData->getItem('cart.moneys')-$treeRS->getItem('money'));//优惠后
			
			$tData->addItem('discount',$treeRS->getItem('money'));//返回优惠金额
			
			//已经使用过优惠券
			$used=$tData->getItem('cart.cid');
			if(!$used){
				self::addDatai($ua,$vData);
			}else{
				$dataid=DB::queryInt('select id from '.self::TableDataName.' where module='.DB::q($module,1).' and moduleid='.DB::q($moduleid,1));
				self::updatai($ua,$vData,$dataid);
			}
			
			$sql='update '.ProductCart::TableName.' set `coupon`='.DB::q($no,1).' where '.ProductCart::FieldID.'='.$cartid;
			DB::exec($sql);
		}
		
		return $exist;
	}
	
	public static function addDatai($ua,&$tData)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('status',1);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		$sql=DB::sqlInsert(self::TableDataName,self::TableDataFields,$tData);
		$_status=DB::exec($sql);
		$cdataid=DB::insertid();
		$tData->addItem('cdataid',$cdataid);
		return $_status;
	}
	
	public static function updatai($ua,$tData,$id)
	{
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$upfields='no,moneys,money,tim_up';
		$sql=DB::sqlUpdate(self::TableDataName,$upfields,$tData,'id='.DB::q($id,1));
		$_status=DB::exec($sql);
		return $_status;
	}
	
	
	public static function checkOrder($ua,&$tData,$isadd=false)
	{
		$exist=0;
		$cid=$tData->getItem('cid');
		$orderid=$tData->getItem('id');
		$code=$tData->getItem('code');
		$sql=self::getCanUseSql($ua,$tData);
		
		$treeRS=newTree();
		$treeRS=DB::queryTree($sql);
		$exist=$treeRS->getCount();
		if($exist){
			$no=$treeRS->getItem('no');
			$limit=$treeRS->getItemInt('limit');
			if($limit){
				$limit_sqlTerm=DB::sqla('uuid='.DB::q($ua->id,1),'no='.DB::q($no,1));
				$limit_sql=DB::sqlSelect(self::TableDataName,'count','*',$limit_sqlTerm);
				$usetimes=DB::queryInt($limit_sql);
				if($usetimes>$limit) return false;
			}
			
			$vData=newTree();
			$vData->addItem('no',$treeRS->getItem('no'));
			$vData->addItem('module','order');
			$vData->addItem('moduleid',$orderid);
			$vData->addItem('moneys',$tData->getItem('order.moneys'));
			$vData->addItem('money',$tData->getItem('order.moneys')-$treeRS->getItem('money'));//优惠后
			
			$tData->addItem('discount',$treeRS->getItem('money'));//返回优惠金额
			$tData->addItem('order.cid',$cid);
			
			if($isadd){//提交订单时
				self::addDatai($ua,$vData);
				$tData->addItem('cdataid',$vData->getItem('cdataid'));
			}
		}
		
		return $exist;
	}
	
	public static function getCanUseSql($ua,&$tData)
	{
		$type=$tData->getItem('type');
		$id=$tData->getItem('id');
		$code=$tData->getItem('code');
		$cid=$tData->getItem('cid');
		
		$date=DCS::today();
		$sqlTerm='date<='.DB::q($date,1).' and date_expire>='.DB::q($date,1).' and type='.DB::q($type,1);//.' and limit>(select count(*) from db_couponi where uuid)'
		
		if($code) $sqlTerm=DB::sqla('code='.DB::q($code,1),$sqlTerm);
		if($cid) $sqlTerm=DB::sqla('id='.DB::q($cid,1),$sqlTerm);
		
		if($type==1){
			$cartTree=newTree();
			$cartTree=ProductCart::getTree($id);
			$module='product';
			$moduleid=$cartTree->getItem('productid');
			$sqlQuery=DB::sqla('moduleid='.DB::q($moduleid,1),$sqlTerm);
			$sqlQuery=DB::sqla('module='.DB::q($module,1),$sqlQuery);
			
			$tData->addItem('cart.moneys',$cartTree->getItem('price'));
			$tData->addItem('cart.moduleid',$cartTree->getItem('productid'));
			$tData->addItem('cart.module',$cartTree->getItem('module'));
			$tData->addItem('cart.cid',$cartTree->getItem('cid'));
		}else{
			if($id){//续费？
				$orderTree=newTree();
				$orderTree=ProductOrder::getTree($id);
				$money=$orderTree->getItem('total_price');
			}else{
				$money=ProductCart::getTotalMoney($ua);
			}
			$sqlQuery=DB::sqla('price_lowest<='.DB::q($money,1),$sqlTerm);
			
			$tData->addItem('order.moneys',$money);
		}
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'date_expire');
		return $sql;
	}
	
	
	public static function getCoupons($ua,$tData)
	{
		$sql=self::getCanUseSql($ua,$tData);
		//debugx($sql);
		$table=newTable();
		$table=DB::queryTable($sql);
		self::doFilterLimitTable($ua,$table);
		return $table;
	}
	
	public static function doFilterLimitTable($ua,&$table)
	{
		
		if($table->getRow()<1) return;
		$table->doBegin();
		while($table->isNext()){
			$limit=$table->getItemValue('limit');
			$no=$table->getItemValue('no');
			if($limit){
				$use_times=DB::queryInt('select count(*) from '.self::TableDataName.' where uuid='.DB::q($ua->id,1).' and no='.DB::q($no,1));
				
				if($use_times>=$limit) $table->delItem();	
			}
		}
	}
	
	
}
?>