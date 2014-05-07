<?php
class ShopCart
{
	const TableName			= 'db_shop_cart';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'no,uurc,uuid,uusid,resid,topic,subtopic,info,serial,amount,price,summary,coupon,prop1,prop2,prop3,prop4,prop5,settings,status,tim,tim_up';
	
	public static function sid($value=null)
	{
		$key='product_sid';
		if(!is_null($value)){
			if($value=='del') DCS::cookieDel($key);
			else DCS::cookieSet($key,$value);
			return '';
		}
		return DCS::cookieGet($key,$value);
	}
	
	public static function add($ua,&$tData)
	{
		$_status=0;
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		if(!$ua->id){
			$tData->addItem('uusid',$ua->sid());
			if(!self::sid()) self::sid($ua->sid());
		}
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('tim_up',DCS::timer());
		
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
		if(!$ua->id) $sqlQuery=DB::sqla($sqlTerm,'uusid='.DB::q($ua->sid(),1));
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
	
	public static function changeAmount($ua,$id,&$tData)
	{
		$_status=0;
		$tData->addItem('tim_up',DCS::timer());
		$sqlTerm=self::FieldID.'='.DB::q($id,1);
		$sql=DB::sqlUpdate(self::TableName,'tim_up,amount',$tData,$sqlTerm);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;
	}
	
	public static function getMoneys($ua,$sqlTerm='')
	{
		//$sqlQuery=DB::sqla($sqlTerm,'uuid='.DB::q($ua->id,1));
		$moneys=0;
		$table=self::query($ua,$sqlTerm);
		$table->doBegin();
		while($table->isNext()){
			$amount=$table->getItemValue('amount');
			$price=$table->getItemValue('price');
			$moneys+=$amount*$price;
		}
		return $moneys;
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
	
	public static function query($ua,$sqlTerm='',$order='',$limit=0)
	{
		if($ua->id && self::sid()){
			$sql='update '.self::TableName.' set uuid='.$ua->id.' where uusid='.DB::q(self::sid(),1);
			DB::exec($sql);
			self::sid('del');
		}
		$tableData=newTable();
		
		$sqlQuery=DB::sqla($sqlTerm,'uuid='.DB::q($ua->id,1));
		if(!$ua->id) $sqlQuery=DB::sqla($sqlTerm,'uusid='.DB::q($ua->sid(),1));
		
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		self::doFilterData($tableData);
		return $tableData;
	}
	
	public static function doFilterData(&$tableData)
	{
		$money=0;
		$moneys=0;
		$tableData->doAppendFields('money,moneys');
		$tableData->doBegin();
		while($tableData->isNext()){
			$amount=$tableData->getItemValue('amount');
			$price=$tableData->getItemValue('price');
			$money=$price*$amount;
			$moneys+=$money;
			$tableData->setItemValue('money',$money);
			$tableData->setItemValue('moneys',$moneys);
		}
	}
	
	public static function queryCoupon($sqlTerm='',$order='',$limit=0)
	{
		$tableData=newTable();
		$sqlQuery=$sqlTerm;
		$sql=DB::sqlQuery(self::TableName,null,$sqlQuery,$order,$limit);
		$tableData=DB::queryTable($sql);
		ShopCoupon::getCoupons($tableData);
		return $tableData;
	}
	
	public static function addCoupon($id,$coupon)
	{
		$_status=0;
		$tData=newTree();
		$sql='update '.self::TableName.' set `coupon`='.DB::q($coupon,1).' where id='.DB::q($id,1);
		$isexec=DB::exec($sql);
		if($isexec) $_status=1;
		return $_status;	
	}
}
?>