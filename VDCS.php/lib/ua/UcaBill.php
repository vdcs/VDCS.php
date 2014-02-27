<?
class UcaBill
{
	const TableName			= 'dbu_bill';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'uuid,uurc,module,rootid,round,multiple,value1,value2,value3,value4,value5,moneys,money,ispay,summary,isaudit,status,tim,tim_up,explain';
	
	const TableDataName		= 'dbu_billi';
	const TableDataFields		= 'rootid,round,billround,money,payment,ispay,isaudit,status,tim,explain';


	public static function getTree($id){
		$treeRS=newTree();
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		//debugx($sql);
		$treeRS=DB::queryTree($sql);
		return $treeRS;
	}
	
	//检测记录是否存在
	public static function isCheck($ua,$id,&$treeRS=null){
		$_status=1;
		$treeRS=self::getTree($id);
		if($treeRS->getCount()<1){
			$_status=5;//不存在
		}else if($treeRS->getItemInt('uuid')!=$ua->id){
			$_status=6;//没权限
		}
		return $_status;
	}
	
	public static function build($ua,&$tData,&$ispay=0)	//moneys,money,ispay
	{
		$_status=0;;
		$_status=self::check($tData);//检测并获取round
		if($_status!=1) return $_status;
		$money=$tData->getItemNum('money');
		if($tData->isItem('ispay')){
			$ispay=$tData->getItem('ispay');
			$set_ispay=true;
		}else{
			$set_ispay=false;
		}
		
		if(!$tData->isItem('multiple')) $tData->addItem('multiple',1);//计时倍数
		if(!$tData->isItem('round')) $tData->addItem('round',0);//第一次付款
		if(!$tData->isItem('moneys')) $tData->addItem('moneys',$money);//应付总额
		if(!$tData->isItem('status')) $tData->addItem('status',1);
		$tData->addItem('money',0);//在refill中添加
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
		if($tData->getItemNum('moneys')==0) $ispay=1;
		$tData->addItem('ispay',$ispay);//在refill中添加
		$billid=0;
		$_status=self::add($tData,$billid);
		if($ispay!=1){
			$payment=$tData->getItemInt('payment');
			$_status=self::refill($ua,$billid,$money,$payment,$ispay,$set_ispay);
		}else{
			$_status=UcaBillHandle::parserReturn($billid);	
		}
		$tData->addItem('billid',$billid);	
		return $_status;
	}
	
	//多次还款
	public static function refill($ua,$rootid,$money,$payment='',&$ispay=0,$set_ispay=false)//$rootid,$money,&$ispay=0,&$rootid=0
	{
		$_status=0;
		$vData=newTree();
		if($rootid==0 || $money<0.01) return $_status;
		
		//账户余额扣款
		$_status=self::payBill($ua,$rootid,$money);
		if($_status!=1){
			UcaBillHandle::parserReturn($rootid);
			return $_status;//6,余额不足
		}
		
		if(!$set_ispay){
			$need_money=self::getNeedMoney($rootid);
			if($money==0 && $need_money>0){
				$ispay=0;
			}else if($money<$need_money){
				$ispay=2;
			}else{
				$ispay=1;
			}
		}
		
		
		$vData->addItem('ispay',$ispay);
		$vData->addItem('rootid',$rootid);
		$vData->addItem('money',$money);
		$vData->addItem('payment',$payment);
		$vData->addItem('status',1);
		$vData->addItem('tim',DCS::timer());
		$_status=self::addi($vData);//增加dbu_billi
		$_status=self::update($rootid,$money,$ispay);//更新dbu_bill
		if($_status){
			$_status=UcaBillHandle::parserReturn($rootid);
		}
		return $_status;
	}
	
	public static function payBill($ua,$rootid,$money)
	{
		$_status=0;
		$balance=UcaMoney::balance($ua);//账户余额
		if($money>$balance) return 6;//余额不足
		$_status=UcaMoney::consume($ua,$money,[
			'module'=>'bill','rootid'=>$rootid,
			'type'=>2,'payment'=>'支付账单，余额付款'
		]);
		return $_status;
	}
	
	//检测是否还款正常
	public static function check(&$tData,&$needmoney=0)
	{
		$module=$tData->getItem('module');
		$rootid=$tData->getItem('rootid');
		if(!$module || !$rootid) return 0;
		$_status=1;
		$sqlQuery=DB::sqla('module='.DB::q($module,1),'rootid='.DB::q($rootid,1));
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'id desc',1);
		$tree=DB::queryTree($sql);
		if($tree->getCount()<1) return $_status;
		$ispay=$tree->getItem('ispay');
		if($ispay!=1){ //上次同一产品账单未结束
			$_status=7;
		}else{
			$round=$tree->getItemInt('round');
			$round+=1;
			$tData->addItem('round',$round);
		}
		return $_status; 
	}
	
	//检测某个账单剩余还款
	public static function getNeedMoney($id,&$rootTree=null)
	{
		$rootTree=self::getTree($id);
		if($rootTree->getItem('id')<1) return false;
		$actual_money=$rootTree->getItem('moneys');
		$alr_moneys=$rootTree->getItem('money');
		$need_money=$actual_money-$alr_moneys;
		return $need_money;
	}
	
	//添加
	public static function add(&$tData,&$billid=0){
		$_status=0;
		
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		$billid=DB::insertid();
		return $_status;
	}
	
	public static function addi($tData)
	{
		$_status=0;
		$tData->addItem('tim',DCS::timer());
		$sql=DB::sqlInsert(self::TableDataName,self::TableDataFields,$tData);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		
		return $_status;
	}
	
	//更新dbu_bull
	public static function update($id,$money,$ispay)
	{
		$_status=0;
		$sql='update '.self::TableName.' set money=money+'.toNum($money).',ispay='.toInt($ispay).' where id='.$id;
		$_status=DB::exec($sql);
		return $_status;
	}
	
	public static function updateRelation($tData, $sqlQuery)
	{
		$_status=0;
		$updateFields='tim_up,module,rootid';
		
		$tData->addItem('tim_up',DCS::timer());
		
		$sql=DB::sqlUpdate(self::TableName,$updateFields,$tData,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}

	//查
	public static function query($ua,$sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		if(!$ua) return $tableData;
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id);
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	//查询具体的orderdata
	public static function queryData($bill_orderid,$ua,$sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		if(!$ua) return $tableData;
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.$ua->id.' and bill_orderid ='.DB::q($bill_orderid,1));
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		return $tableData;
	}
	
	public static function queryDataByRootid($rootid,$sqlTerm='',$order='',$limit=0)
	{
		if(!$rootid) return;
		if($sqlTerm) $sqlQuery=DB::sqla($sqlTerm,'rootid='.DB::q($rootid,1));
		else $sqlQuery='rootid='.DB::q($rootid,1);
		$sql=DB::sqlQuery(self::TableDataName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		self::doFilterData($tableData);
		return $tableData;
	}
	
	public static function doFilterData(&$tableData)
	{
		$tableData->doItemBegin();
		$tableData->doAppendFields('sn');
		$i=0;
		while($tableData->isNext()){
			$i++;
			$tableData->setItemValue('sn',$i);
		}
	}
	
	public static function edit($ua,$id,$tData)
	{
		$_status=0;
		$updateFields='tim_up,status,ispay,money';
		
		$tData->addItem('tim_up',DCS::timer());
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlUpdate(self::TableName,$updateFields,$tData,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	//删除
	public static function delete($ua,$id)
	{
		$_status=self::isCheck($ua,$id,$treeRS);
		if($_status!=1) return $_status;
		
		$sqlQuery=self::FieldID.'='.$id;
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
}

