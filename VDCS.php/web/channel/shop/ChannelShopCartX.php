<?
class ChannelShopCartX extends ChannelShopCartBaseX
{
	public function parseList()
	{
		$this->tableList=ShopCart::query($this->ua,'','id desc');
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVar('moneys',$this->tableList->getItemValue('moneys'));
		$this->setSucceed();
	}
	
	public function parseAdd()
	{
		$_status=0;
		$id=queryi('id');
		$amount=queryi('amount');
		
		if(!$id) $this->addError('商品ID不能为空');
		if(!$amount) $this->addError('商品数量不能为空');
		if($this->doRaiseError()){
			$this->setStatus('data');
			$this->setMessage($this->getVar('error_message'));
			return;
		}
		
		$treeProduct=newTree();
		$treeProduct=ShopProduct::getTree($id);
		if($treeProduct->getCount()<1){
			$this->setStatus('product');
			$this->setMessage('商品(ID:'.$id.')不存在');
			return;
		}
		
		
		$this->treeData=$treeProduct->getFilterTree('p_');
		$this->addData('resid',$id);
		$this->addData('amount',$amount);
		
		$sets=[];
		//$setts['base']=$proTree->getArray();
		$sets['buy']=$this->treeData->getArray();
		$this->addData('settings',VDCSData::enCode($sets));
		
		$_status=ShopCart::add($this->ua,$this->treeData);
		if(!$_status){
			$this->setStatus('save');
			$this->setMessage('加入购物车失败！');
		}
		
		$this->setSucceed();
	}
	
	public function parseChange()//修改购物车
	{
		$_status=0;
		$amount=queryi('amount');
		$id=queryi('id');
		if($id && $amount){
			$tree=newTree();
			$tree->addItem('amount',$amount);
			$_status=ShopCart::changeAmount($this->ua,$id,$tree);	
		}
		if($_status){
			//$this->addVar('money',$tree->getItemNum('money'));
			//$this->addVar('moneys',$tree->getItemNum('moneys'));
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('购物车修改失败！');
		}
	}
	
	public function parseDelete()
	{
		$_status=0;
		$id=queryi('id');
		$type=querys('type');
		if($type=='clear'){
			$_status=ShopCart::del($this->ua);
		}else if($id){
			$_status=ShopCart::del($this->ua,'id='.DB::q($id,1));
		}
		if($_status){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('操作失败！');
		}
	}
	
	public static function parseTest()
	{
		$sql='select * from db_shop_orderi';
		$t=DB::queryTable($sql);
		$f=$t->getFields();
		debugx($f);
	}
}
?>