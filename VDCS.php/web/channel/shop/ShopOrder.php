<?php
class ShopOrder
{
	const TableName			= 'db_shop_order';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'orderno,uurc,uuid,realname,linkid,linkman,email,mobile,company,address,postcode,phone,fax,contacts,message,feetype,discount,moneys,money,emoney,points,score,con_emoney,con_points,con_score,con_gift,payment,shipping,dtime,shipping_type,shipping_no,shipping_price,shipping_emoney,shipping_points,shipping_score,shipping_message,shipping_status,shipping_explain,ispayment,ispay,pay_tim,type,status,date,tim,tim_up,explain,trans,trans_tim,trans_message';
	const DataTableName		= 'db_shop_orderi';
	const DataTableFields		= 'orderno,resid,topic,subtopic,info,serial,prop1,prop2,prop3,prop4,prop5,amount,discount,multiple,feetype,moneys,money,emoney,points,score,con_emoney,con_points,con_score,con_gift,shipping,type,status,tim,tim_up,explain';
	
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null)
	{
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}else if($treeRS->getItemInt('uuid')!=$ua->id)
		{
			$_status=6;//没权限
		}
		return $_status;
	}
	
	
	public static function add(&$tData)
	{
		$_status=0;
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		$orderid=DB::insertid();
		$tData->addItem('orderid',$orderid);
		return $_status;
	}
	
	protected function addDetails($tData)
	{
		$_status=0;
		//添加orders
		$ua=Ua::instance();
		$ua->setID($tData->getItem('uuid'));
		$tableData=ShopCart::query($ua,'','id desc');
		unset($ua);
		$tableData->doAppendFields('orderno');
		$tableData->doBegin();
		//插入orders
		while($tableData->isNext()){
			$tableData->setItemValue('orderno',$tData->getItem('orderno'));
			$treeData=$tableData->getItemTree();
			$price=$treeData->getItemNum('price');
			$treeData->addItem('tim',DCS::timer());
			$treeData->addItem('tim_up',DCS::timer());
			
			//折扣
			$discount=0;
			$cid=$treeData->getItem('cid');
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
			$treeData->addItem('discount',$discount);
			
			
			$amount=$treeData->getItemInt('amount');
			$moneys=$price*$amount;
			$treeData->addItem('moneys',$moneys);
			$money=$moneys-$discount;
			$treeData->addItem('money',$money);
			
			$sql=DB::sqlInsert(self::DataTableName,self::DataTableFields,$treeData);
			$isexec=DB::exec($sql);
		}
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function addData($ua,&$tData)
	{
		$_status=0;
		$cid=$tData->getItem('cid');
		if($cid) self::getCoupon($tData); //代金券
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		$tData->addItem('status',0);
		$isexec=self::add($tData);
		if($isexec){
			$_status=self::addDetails($tData);
		}
		return $_status;
	}
	
	public static function getTree($id,$sqlTerm='')
	{
		$treeRS=newTree();
		//$sqlQuery=$idField.'='.$id;
		$sqlQuery=DB::sqla($sqlTerm,self::FieldID.'='.$id);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRS=DB::queryTree($sql);
		$treeRS=self::doFilterTree($treeRS);
		return $treeRS;
	}
	
	public static function getDetailsTree($sqlTerm)
	{
		$treeRS=newTree();
		//$sqlQuery=$idField.'='.$id;
		if(!$sqlTerm) return $treeRS;
		$sql=DB::sqlSelect(self::DataTableName,'','*',$sqlTerm,'',1);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}

	protected function doFilterTree($treeRS)
	{
		$tim=$treeRS->getItem('tim');
		$pay_tim=$treeRS->getItem('pay_tim');
		$treeRS->addItem('time',datei('Y-m-d',$tim));
		if($pay_tim>0){
			$pay_time=datei('Y-m-d',$pay_tim);	
		}else{
			$pay_time='未付款';	
		}
		$treeRS->addItem('pay_time',$pay_time);
		$status=$treeRS->getItem('status');
		//0=待付款 1=成功 2=付款确认中 3=已付款,待发货 4=已发货，待收货 5=已确认收货，待评价 9=订单取消
		switch($status){
			case 1:
				$sstatus='成功';
				break;	
			case 0:
				$sstatus='待付款';
				break;
			case 2:
				$sstatus='付款确认中';
				break;
			case 3:
				$sstatus='已付款,待发货';
				break;
			case 4:
				$sstatus='已发货，待收货';
				break;
			case 5:
				$sstatus='已确认收货，待评价';
				break;
			case 8:
				$sstatus='无效订单';
				break;
			case 9:
				$sstatus='订单已取消';
				break;
		}
		$treeRS->addItem('status.name',$sstatus);
		$payment=$treeRS->getItem('payment');
		switch($payment){
			case 'balance':
				$spayment='余额支付';
				break;	
			case 'onlinepay':
				$spayment='在线支付';
				break;
			case 'transfer':
				$spayment='银行转账';
				break;
			case 'remit':
				$spayment='邮局汇款';
				break;
			case 'cash':
				$spayment='现金支付';
				break;
			default:
				$spayment='未付款';
				break;
		}
		$treeRS->addItem('payment.name',$spayment);
		//debugTree($treeRS);
		return $treeRS;
		
	}
	
	public static function getTotal($sqlTerm='',$order='',$limit=0)
	{
		$sql=DB::sqlSelect(self::TableName,'count','*',$sqlTerm,$order,$limit);
		return DB::queryInt($sql);
	}
	
	public static function query($sqlTerm='',$order='',&$p)
	{
		$tableData=newTable();
		
		$total=self::getTotal($sqlTerm,$order);//获取总条数
		if($total==0) return $tableData;
		
		if(!iso($p)){
			$p=new libPaging();
			$p->setListNum(self::RowDef);//设置每页显示条数
		}
		$p->setTotal($total);
		$p->doParse();
		$row=$p->getListNum();
		$page=$p->getPage();
		
		$sql=DB::sqlQuery(self::TableName,null,$sqlTerm,$order,$limit);
		
		if($row>0) $sql.=' limit '.(($page-1)*$row).','.$row;//添加分页的条件
		
		$tableData=DB::queryTable($sql);
		$tableData->doAppendFields('time,pay_time,status.name,payment.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$treeRes=$tableData->getItemTree();
			$treeRes=self::doFilterTree($treeRes);
			//$tableData->setItem();
			$tableData->setItemValue('time',$treeRes->getItem('time'));
			$tableData->setItemValue('pay_time',$treeRes->getItem('pay_time'));
			$tableData->setItemValue('status.name',$treeRes->getItem('status.name'));
			$tableData->setItemValue('payment.name',$treeRes->getItem('payment.name'));
		}
		return $tableData;
	}
	
	
	
	public static function queryDetails($sqlQuery='',$order='',$limit=0)
	{
		$tableData=newTable();
		
		$sql=DB::sqlQuery(self::DataTableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		//$tableData=self::doFilterData($tableData);
		return $tableData;
	}
	
	protected function doFilterData($tableData)
	{
 		$tableData->doAppendFields('name,time,status.name');
 		$tableData->doBegin();
		while($tableData->isNext()){
			$settings=$tableData->getItemValue('settings');
			$settings_arr=VDCSData::deCode($settings,true);//转化成数组的形式
			$tableData->setItemValue('name',$settings_arr['base']['name']);
			$status=$settings_arr['base']['status'];
			switch($status){
				case 0:
					$tableData->setItemValue('status.name','未付款');
					break;
				default:
					$tableData->setItemValue('status.name','已付款');
					break;	
			}
			$tableData->setItemValue('time',datei('Y-m-d',$tableData->getItemValue('tim')));
		}
		return $tableData;
	}
	
	
	public static function editDetailStatus($id)
	{
		if(!$id) return 0;
		$tableUpfields='status,tim_up';
		$tData=newTree();
		$_status=0;
		$tData->addItem('status',1);
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery='id='.$id;
		$sql=DB::sqlUpdate(self::DataTableName,$tableUpfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;	
	}
	
	public static function edit($ua,$id,$tData)
	{
		$tableUpfields='payment,ispay,status,pay_tim,tim_up';
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$tableUpfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;	
	}
	
	public static function editPrice($id,$tData)
	{
		$tableUpfields='money,tim_up';
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$tableUpfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;	
	}
	
	public static function editStatus($id,$status)
	{
		$tableUpfields='status,tim_up';
		$tData=newTree();
		$_status=0;
		$tData->addItem('status',$status);
		$tData->addItem('tim_up',DCS::timer());
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$tableUpfields,$tData,$sqlQuery);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;	
	}
	
	//删除
	public static function delete($ua,$id)
	{
		$treeRS=newTree();
		$_status=self::isCheck($ua,$id,$treeRS);//权限判断应该需要改变
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//取消订单
	public static function cancel($id)
	{
		$tData=newTree();
		$_status=self::editStatus($id,9);
		return $_status;
	}
	
	//更改某个具体明细的价格
	public static function editData($id,$tData)
	{
		$sqlQuery='id='.$id;
		$sql=DB::sqlQuery(self::DataTableName,null,$sqlQuery);
		$treeRes=DB::queryTree($sql);
		$rootid=$treeRes->getItemInt('rootid');
		$oldPrice=$treeRes->getItemNum('price');//原价格
		
		$newPrice=$tData->getItemNum('price');//新价格
		
		$sql=DB::sqlQuery(self::TableName,'total_price',self::FieldID.'='.$rootid.'');
		$price=DB::queryTree($sql)->getItemNum('total_price')+$newPrice-$oldPrice;
		
		$tableUpfields='price,tim_up';
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sql=DB::sqlUpdate(self::DataTableName,$tableUpfields,$tData,$sqlQuery);
		$isexec=DB::exec($sql);
		if($isexec){
			//更改order
			$tim_up=DCS::timer();
			$sql='update '.self::TableName.' set total_price='.$price.',tim_up='.$tim_up.' where '.self::FieldID.'='.$rootid.'';
		
			$isexec=DB::exec($sql);
			if($isexec) $_status=1;
		}
		return $_status;
	}
	
	
	public static function getCoupon(&$tData)
	{
		$cid=$tData->getItemInt('cid');
		if(!$cid) return;
		$discount=0;
		$cTree=newTree();
		$cTree=ShopCoupon::getTree('id='.DB::q($cid,1));
		$date_begin=$cTree->getItem('date');
		$date_expire=$cTree->getItem('date_expire');
		$timer=DCS::timer();
		if($timer<=strtotime($date_expire) && $timer>=strtotime($date_begin)){
			$discount=$cTree->getItemNum('money');
		}
		$tData->addItem('discount',$discount);
	}
	
	public static function getOrderno($type='num',$total=6)
	{
		$arr=[];
		switch($type){
			case 'num':
				$arr=range(0,9);
				break;
			case 'letter':
				$arr=range('a','z');
				break;
			default:
				$arr1=range(0,9);
				$arr2=range('a','z');
				$arr=array_merge($arr1,$arr2);
				break;	
		}
		$no='';
		for($i=0;$i<$total;$i++){
			$key=rand(0,count($arr)-1);
			$no.=$arr[$key];	
		}
		$no=datei('Ymd').$no;
		return $no;
	}
}

?>