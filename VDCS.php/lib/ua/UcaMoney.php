<?
class UcaMoney
{
	public static function recharge($ua,$money,$params=array())
	{
		if(!$params['module']) $params['module']='recharge';
		$params['money']=$money;
		$params['type']=1;
		$params['typechar']='+';
		$_status=self::modify($ua,$params);
		return $_status;
	}
	
	public static function consume($ua,$money,$params=array())
	{
		$balancemoney=self::balance($ua);
		if($money<0.01 || $money>$balancemoney) return false;
		$params['money']=$money;
		$params['type']=2;
		$params['typechar']='-';
		$_status=self::modify($ua,$params);	
		return $_status;
	}
	
	//获取账户余额
	public static function getRest($ua){ 
		$balancemoney=self::balance($ua);
		return $balancemoney; 
	}
	public static function balance($ua){
		$balancemoney=DB::queryNum('select money from '.$ua->TableName.' where '.$ua->FieldID.'='.DB::q($ua->id,1));
		return $balancemoney;
	}

	//改变账户余额，同时记录
	protected static function modify($ua,$params){
		$changemoney=$params['money']?$params['money']:$params['changemoney'];
		$type=$params['type'];
		$typechar=$params['typechar'];
		$module=$params['module'];
		$rootid=$params['rootid'];
		$_status=self::edit($ua,$changemoney,$typechar);
		
		if($_status){
			$tData=newTree();
			$tData->addItem('money',$changemoney);
			$tData->addItem('module',$module);
			$tData->addItem('rootid',$rootid);
			$tData->addItem('type',$type);
			$balancemoney=self::balance($ua);
			$tData->addItem('balance',$balancemoney);//现金
			$tData->addItem('payment',$params['payment']);
			$_status=UcaTransaction::add($ua,$tData);	//添加记录
		}
		return $_status;
	}
	
	public static function edit($ua,$money,$typechar){
		$_status=0;
		$sql='update '.$ua->TableName.' set money = money'.$typechar.$money.' where '.$ua->FieldID.'='.DB::q($ua->id,1);
		$_status=DB::exec($sql);
		return $_status;
	}
}

?>