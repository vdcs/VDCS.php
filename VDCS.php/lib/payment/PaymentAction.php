<?
class PaymentAction
{
	const TableName			= 'dbd_payment';
	
	
	public static function tradeno()
	{
		$times=VDCSTime::toConvert(null,10);
		$re=toSubstr($times,1,8).'-'.toSubstr($times,9);
		$re.=utilCode::getRandNum(3);
		return $re;
	}
	
	
	public static function getTree($tradeno)
	{
		$sqlQuery='tradeno='.DB::q($tradeno,1);
		$sql=DB::sqlSelect(self::TableName,'','*',$sqlQuery,'',1);
		$treeRecord=DB::queryTree($sql);
		return $treeRecord;
	}
	public static function is($tradeno)
	{
		$status=0;
		$treePayment=self::getTree($tradeno);
		if($treePayment->getCount()>0) $status=1;
		return $status;
	}
	
	
	public static function create($tData)
	{
		$status=0;
		$sql=DB::sqlInsert(self::TableName,'',$tData);
		$isexec=DB::exec($sql);
		if($isexec) $status=1;
		return $status;
	}
	
	public static function set($tradeno,$sets)
	{
		$status=0;
		$sqlQuery='tradeno='.DB::q($tradeno,1);
		if(isTree($sets)){
			$sql=DB::sqlUpdate(self::TableName,'',$sets,$sqlQuery);
		}
		else{
			$sql='update '.self::TableName.' set '.$sets.' where '.$sqlQuery;
		}
		$isexec=DB::exec($sql);
		if($isexec) $status=1;
		return $status;
	}
	
	
	public static function parserReturn($tradeno)
	{
		$statis=0;
		$treePayment=self::getTree($tradeno);
		//debugTree($treePayment);
		if($treePayment->getCount()<1){
			return $status;
		}
		
		if($treePayment->getItemInt('ispay')<1){
			$sets=newTree();
			$sets->addItem('ispay',1);
			$sets->addItem('pay_tim',DCS::timer());
			self::set($tradeno,$sets);
		}
		
		if($treePayment->getItemInt('ishandle')<1){
			$module=$treePayment->getItem('module');
			$type=$treePayment->getItem('type');
			$objectpa='PaymentHandle'.ucfirst($module);
			if(_autoload_::isReal($objectpa)){
				$funcname='parser'.ucfirst($type);
				//method_exists,is_callable
				if(method_exists($objectpa,$funcname)){
					$objectpa::$funcname($treePayment);
					self::set($tradeno,'ishandle=1');
				}
			}
		}
		
		$statis=1;
		return $status;
	}
	
}
