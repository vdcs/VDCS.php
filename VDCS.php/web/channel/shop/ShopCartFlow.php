<?
class ShopCartFlow
{
	public $tableShipping=null,$tablePayment=null,$tableDtime=null,$tableAddr=null;
	
	protected $ua;
	protected $treeAddr=null;
	protected $var_KeyName;
	protected $var_AddrID,$var_ShippingID,$var_PaymentID,$var_DtimeID;
	
	public function __construct()
	{
		$this->ua=&$GLOBALS['ua'];
		
		$this->treeAddr=newTree();
	}
	public function __destruct()
	{
		unsetr($this->tableShipping,$this->tablePayment,$this->tableDtime,$this->tableAddr);
		unsetr($this->treeAddr);
	}
	
	
	public function setKeyName($v)
	{
		if(right($v,1)!='.') $v.='.';
		$this->var_KeyName=$v;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		global $dcs,$cfg;
		//$this->var_AddrID=toInt($dcs->client->getSession($this->var_KeyName.'addrid'));
		$aryTemp=$dcs->client->getSession($this->var_KeyName.'addr');
		if(isa($aryTemp)) $this->treeAddr->setArray($aryTemp);
		$this->var_ShippingID=$dcs->client->getSession($this->var_KeyName.'shippingid');
		$this->var_PaymentID=$dcs->client->getSession($this->var_KeyName.'paymentid');
		$this->var_DtimeID=$dcs->client->getSession($this->var_KeyName.'dtimeid');
		
		$this->tableShipping=$cfg->getTable('data.order.shipping');
		$this->tablePayment=$cfg->getTable('data.order.payment');
		$this->tableDtime=$cfg->getTable('data.order.dtime');
		$this->tableAddr=newTable();
		
		if($this->ua->isLogin()){
			//$this->tableAddr=DB::queryTable('select * from '.$this->TableName.'_addr where uuid='.$this->ua->id.' order by a_id asc');
			//$this->tableAddr->doFilter('a_');
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isAddr()
	{
		$re=false;
		//if($this->var_AddrID>0) $re=true;
		if($this->treeAddr->getCount()>0) $re=true;
		return $re;
	}
	/*
	public function setAddr($addrid)
	{
		global $dcs;
		$dcs->client->setSession($this->var_KeyName.'addrid',$addrid);
	}
	public function getAddrID()
	{
		return $this->var_AddrID;
	}
	*/
	public function setAddrTree($treeData)
	{
		global $dcs;
		$this->treeAddr->setArray($treeData->getArray());
		$dcs->client->setSession($this->var_KeyName.'addr',$treeData->getArray());
	}
	public function getAddrTree()
	{
		//$reTree=newTree();
		//$reTree->setArray($this->treeAddr->getArray());
		return $this->treeAddr;
	}
	public function doAddrClear()
	{
		global $dcs;
		$this->treeAddr=newTree();
		$dcs->client->delSession($this->var_KeyName.'addr');
	}
	
	public function isUnite()
	{
		$re=false;
		if(len($this->var_ShippingID)>0 && len($this->var_PaymentID)>0 && len($this->var_DtimeID)>0) $re=true;
		return $re;
	}
	public function setUnite($shippingid,$paymentid,$dtimeid)
	{
		global $dcs;
		if(len($shippingid)>-1) $this->var_ShippingID=$shippingid;
		if(len($paymentid)>-1) $this->var_PaymentID=$paymentid;
		if(len($dtimeid)>-1) $this->var_DtimeID=$dtimeid;
		if(len($shippingid)>-1) $dcs->client->setSession($this->var_KeyName.'shippingid',$shippingid);
		if(len($paymentid)>-1) $dcs->client->setSession($this->var_KeyName.'paymentid',$paymentid);
		if(len($dtimeid)>-1) $dcs->client->setSession($this->var_KeyName.'dtimeid',$dtimeid);
	}
	public function getShippingID(){return $this->var_ShippingID;}
	public function getPaymentID(){return $this->var_PaymentID;}
	public function getDtimeID(){return $this->var_DtimeID;}
	public function doUniteClear()
	{
		global $dcs;
		$this->var_ShippingID=0;
		$this->var_PaymentID=0;
		$this->var_DtimeID=0;
		$dcs->client->delSession($this->var_KeyName.'shippingid');
		$dcs->client->delSession($this->var_KeyName.'paymentid');
		$dcs->client->delSession($this->var_KeyName.'dtimeid');
	}
	
	public function doClear()
	{
		$this->doAddrClear();
		$this->doUniteClear();
	}
}
?>