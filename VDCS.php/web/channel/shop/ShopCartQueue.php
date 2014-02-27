<?
class ShopCartQueue
{
	protected $tableQueue=null;
	protected $var_KeyText;
	protected $var_KeyName,$var_KeySeparatr,$var_KeySeparatrs;
	protected $var_Fields;
	protected $var_isupdate;
	
	public function __construct()
	{
		$this->var_Fields='sn,id,amount,prop1,prop2,prop3,prop4,prop5';
		$this->tableQueue=newTable();
		$this->tableQueue->setFields($this->var_Fields);
		
		$this->var_KeyName='cart';		//cfg.getChannel()&'.cart'
		$this->var_KeySeparatr=',';
		$this->var_KeySeparatrs=';';
		
		$this->var_isupdate=false;
	}
	
	public function __destruct()
	{
		unsetr($this->tableQueue);
	}
	
	
	public function setKeyName($v){$this->var_KeyName=$v;}
	public function isUpdate(){return $this->var_isupdate;}
	
	public function getTotal(){return $this->tableQueue->getRow();}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		global $dcs;
		$this->var_KeyText=$dcs->client->getSession($this->var_KeyName);
		//debugx($this->var_KeyText);
		$sn=1;
		$aryText=toSplit($this->var_KeyText,$this->var_KeySeparatrs);
		for($t=0;$t<count($aryText);$t++){
			$aryItem=toSplit($aryText[$t],$this->var_KeySeparatr);
			if(count($aryItem)==7){
				$aryItem[0]=toInt($aryItem[0]);
				$aryItem[1]=toInt($aryItem[1]);
				if($aryItem[0]>0 && $aryItem[1]>0){
					$treeItem=newTree();
					$treeItem->addItem('sn',$sn);
					$treeItem->addItem('id',$aryItem[0]);
					$treeItem->addItem('amount',$aryItem[1]);
					$treeItem->addItem('prop1',$aryItem[2]);
					$treeItem->addItem('prop2',$aryItem[3]);
					$treeItem->addItem('prop3',$aryItem[4]);
					$treeItem->addItem('prop4',$aryItem[5]);
					$treeItem->addItem('prop5',$aryItem[6]);
					$this->tableQueue->addItem($treeItem);
					$sn++;
				}
			}
		}
		//debugTable($this->tableQueue);
		unsetr($treeItem);
	}
	
	
	public function doItemAdd($id,$treeData)
	{
		if(!$this->isExist($id)){
			$treeData->addItem('sn',$this->tableQueue->getRow()+1);
			$treeData->addItem('id',$id);
			$this->tableQueue->addItem($treeData);
			$this->var_isupdate=true;
		}
	}
	
	public function doItemEdit($sn,$id,$treeData)
	{
		if($id>0){
			$this->tableQueue->doBegin();
			while($this->tableQueue->isNext()){
				if($this->tableQueue->getItemValueInt('sn')==$sn && $this->tableQueue->getItemValueInt('id')==$id){
					$treeData->doBegin();
					for($t=0;$t<$treeData->getCount();$t++){
						$this->tableQueue->setItemValue($treeData->getItemKey(),$treeData->getItemValue());
						$treeData->doMove();
					}
					$this->var_isupdate=true;
					break;
				}
			}
		}
	}
	
	public function doItemDel($sn,$id)
	{
		$sn=toInt($sn);$id=toInt($id);
		if($sn>0 && $id>0){
			$this->tableQueue->doBegin();
			while($this->tableQueue->isNext()){
				if($this->tableQueue->getItemValueInt('sn')==$sn && $this->tableQueue->getItemValueInt('id')==$id){
					$this->tableQueue->delItem();
					$this->var_isupdate=true;
					break;
				}
			}
		}
	}
	
	public function doItemClear()
	{
		$this->tableQueue=newTable();
		$this->tableQueue->setFields($this->var_Fields);
		$this->var_isupdate=true;
	}
	
	public function doUpdate()
	{
		global $dcs;
		if($this->var_isupdate){
			$this->var_KeyText='';
			$this->tableQueue->doBegin();
			while($this->tableQueue->isNext()){
				$this->var_KeyText.=$this->var_KeySeparatrs.$this->tableQueue->getItemValue('id').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('amount').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('prop1').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('prop2').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('prop3').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('prop4').$this->var_KeySeparatr.
					$this->tableQueue->getItemValue('prop5');
			}
			if(len($this->var_KeyText)>0) $this->var_KeyText=toSubstr($this->var_KeyText,len($this->var_KeySeparatrs)+1);
			$dcs->client->setSession($this->var_KeyName,$this->var_KeyText);
			//debugx($this->var_KeyText);
			//debugx($this->tableQueue->getRow());
		}
	}
	
	public function doClear()
	{
		$this->doItemClear();
		$this->doUpdate();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getTable()
	{
		//$reTable=newTable();
		//$reTable->setArray($this->tableQueue->getArray());
		return $this->tableQueue;
	}
	
	public function getItemTree($id)		//???
	{
		$reTree=newTree();
		if($id>0){
			$this->tableQueue->doBegin();
			while($this->tableQueue->isNext()){
				if($this->tableQueue->getItemValueInt('id')==$id){
					$reTree=$this->tableQueue->getItemTree();
					break;
				}
			}
		}
		return $reTree;
	}
	
	public function isExist($id)
	{
		$re=false;
		if($id>0){
			$this->tableQueue->doBegin();
			while($this->tableQueue->isNext()){
				if($this->tableQueue->getItemValueInt('id')==$id){
					$re=true;
					break;
				}
			}
		}
		return $re;
	}
	
}
?>