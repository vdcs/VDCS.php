<?
class UcaCredit
{
	const TableName			= 'dbu_credit';
	const TablePX			= '';
	const FieldID			= 'id';
	const TableFields		= 'uurc,uuid,money,type,balance,summary,status,tim,explain';
	
	//添加
	protected static function add($ua,&$tData){
		$tData->addItem('uurc',$ua->rc);
		$tData->addItem('uuid',$ua->id);
		$tData->addItem('tim',DCS::timer());
		$tData->addItem('status',1);
		$sql=DB::sqlInsert(self::TableName,self::TableFields,$tData);
		$isexec=DB::exec($sql);
		$id=DB::insertid();
		$tData->addItem('rootid',$id);
		return $isexec;
	}
	
	//查询
	public static function querier($ua,&$p=null,$params=array())
	{
		$tableData=newTable();
		$params['query']=DB::sqla($params['query'],'uuid='.$ua->id);//$params['query'] 额外条件
		$params['table']=self::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		//self::doFilterData($tableData);
		return $tableData;
	}
	
	
	
	
	//设置信用总额
	public static function setCredits($ua,$credits)
	{
		$_status=0;
		$sql='update '.$ua->TableName.' set credits = '.$credits.' where '.$ua->FieldID.'='.DB::q($ua->id,1);
		$_status=DB::exec($sql);
		return $_status;
	}
	
	//提升信用总额
	public static function increaseCredits($ua,$credits)
	{
		$_status=0;
		$sql='update '.$ua->TableName.' set credits=credits+'.$credits.' where '.$ua->FieldID.'='.DB::q($ua->id,1);
		$_status=DB::exec($sql);
		return $_status;
	}
	
	//还信用
	public static function recharge($ua,$money,$params=array())
	{
		//判断账户余额
		$balance_money=UcaMoney::balance($ua);
		if($balance_money<$money) return false;
		
		$params['money']=$money;
		$params['type']=2;
		$params['typechar']='-';
		$balance_credit=self::balance($ua);
		$balance=$balance_credit+$money;//剩余可用信用
		$params['balance']=$balance;
		$_status=self::modify($ua,$params);
		return $_status;
	}
	
	//使用信用
	public static function consume($ua,$money,$params=array())
	{
		$balance_credit=self::balance($ua);
		if($money>$balance_credit) return false;
		$params['money']=$money;
		$params['type']=1;
		$params['typechar']='+';
		$balance=$balance_credit-$money;//剩余可用信用
		$params['balance']=$balance;
		$_status=self::modify($ua,$params);	
		return $_status;
	}

	//获取可用信用
	public static function balance($ua){
		$creditAry=DB::queryAry('select credits,credit from '.$ua->TableName.' where '.$ua->FieldID.'='.DB::q($ua->id,1));
		$credits=$creditAry['credits'];//信用总额
		$credit=$creditAry['credit'];//已使用信用
		$balancemoney=$credits-$credit;
		return $balancemoney;
	}

	//改变已使用信用，同时记录
	protected static function modify($ua,$params){
		$changemoney=$params['money']?$params['money']:$params['changemoney'];
		$type=$params['type'];
		$typechar=$params['typechar'];
		$_status=self::editCredit($ua,$changemoney,$typechar);
		
		//记录下
		if($_status){
			$tData=newTree();
			$tData->addItem('money',$changemoney);
			$tData->addItem('type',$type);
			$tData->addItem('balance',$params['balance']);//剩余可用信用
			$tData->addItem('summary',$params['summary']);//备注
			$_status=self::add($ua,$tData);//添加记录
			if($_status){
				//credir-->money
				if($type==1){
					UcaMoney::recharge($ua,$changemoney,[
						'module'=>'credit','rootid'=>$tData->getItem('rootid'),
						'payment'=>'信用充值'
					]);
				}else if($type==2){
					UcaMoney::consume($ua,$changemoney,[
						'module'=>'credit','rootid'=>$tData->getItem('rootid'),
						'payment'=>'偿还信用'
					]);
				}	
			}
		}
		return $_status;
	}
	
	//修改credit
	public static function editCredit($ua,$money,$typechar){
		$_status=0;
		$sql='update '.$ua->TableName.' set credit = credit'.$typechar.$money.' where '.$ua->FieldID.'='.DB::q($ua->id,1);
		$_status=DB::exec($sql);
		return $_status;
	}
	
}

