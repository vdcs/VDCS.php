<?
class ChannelShopCart extends ChannelShopBase
{
	public $iCart,$iFlow;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->iCart,$this->iFlow);
	}
	
	
	protected function getTotal(){return $this->iCart->getTotal();}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadPre()
	{
		$this->iCart=new ShopCart();
		$this->iCart->doLoad();
		$this->iCart->setFields('full');
		
		$this->iFlow=new ShopCartFlow();
		$this->iFlow->setKeyName($this->_chn_.'.flow');
		$this->iFlow->doLoad();
	}
	
	public function doLoad()
	{
		
	}
	
	public function doLoadPos()
	{
		$this->isflow=false;
		if($this->_m_=='flow'){
			$this->isflow=true;
			$this->flow=query('flow');
			if(inp('start,addr,unite',$this->flow)<1) $this->flow='';
			
			if(inp('addr,unite',$this->flow)<1){
				if($this->iFlow->isAddr()){
					if(!$this->iFlow->isUnite()){
						$this->flow='unite';
					}
				}
				else{
					$this->flow='addr';
				}
			}
			
			$theme->setPage($this->_p_.'.'.$this->_m_);
			$theme->setModule($this->flow);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		$this->iCart->doParse();
		//debugTable($this->iCart->tableCarts);
		
		$ctl->treeVar=$this->iCart->treeVar;
		
		$this->doParseModule();
	}
	protected function doParseModule()
	{
		if($this->isflow){
			if($this->getTotal()<1) go($cfg->toLinkURL('cart'));
			switch($this->flow){
				case 'addr':
					$this->doParseFlowAddr();
					break;
				case 'unite':
					$this->doParseFlowUnite();
					break;
				default:
					if($this->iFlow->isAddr() && $this->iFlow->isUnite()) go($cfg->toLinkURL('pay'));
					break;
			}
		}
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseFlowAddr()
	{
		$ctl->treeDat->doAppend($this->iFlow->getAddrTree());
		if(isFormPost()){
			$ctl->treeDat->addItem('linkman',postc('linkman',50));
			$ctl->treeDat->addItem('area_province',postc('area_province',50));
			$ctl->treeDat->addItem('area_city',postc('area_city',50));
			$ctl->treeDat->addItem('area_area',postc('area_area',50));
			if(len($ctl->treeDat->getItem('area_province'))>0 && len($ctl->treeDat->getItem('area_city'))>0 && len($ctl->treeDat->getItem('area_area'))>0){
				$ctl->treeDat->addItem('areas',$ctl->treeDat->getItem('area_province').'-'.$ctl->treeDat->getItem('area_city').'-'.$ctl->treeDat->getItem('area_area'));
			}
			$ctl->treeDat->addItem('address',postc('address',200));
			$ctl->treeDat->addItem('postcode',postc('postcode',10));
			$ctl->treeDat->addItem('phone',postc('phone',50));
			$ctl->treeDat->addItem('email',postc('email',100));
			
			if(len($ctl->treeDat->getItem('linkman'))<1) $ctl->e->addItem('请填写有效的 收货人姓名');
			if(len($ctl->treeDat->getItem('areas'))<1) $ctl->e->addItem('请选择正确的 省份/直辖市');
			if(len($ctl->treeDat->getItem('address'))<1) $ctl->e->addItem('请填写有效的 详细地址');
			if(len($ctl->treeDat->getItem('postcode'))<1) $ctl->e->addItem('请填写有效的 邮政编码');
			if(len($ctl->treeDat->getItem('phone'))<1) $ctl->e->addItem('请填写有效的 联系电话');
			if(len($ctl->treeDat->getItem('email'))>0){
				if(!utilCheck::isEmail($ctl->treeDat->getItem('email'))) $ctl->e->addItem('请填写有效的 E-mail地址');
			}
			
			if($this->e->isCheck()) $ctl->doRaiseError();
			else{
				$this->iFlow->setAddrTree($this->treeDat);
				if($this->iFlow->isAddr()) go($this->cfg->toLinkURL('cart.flow'));
			}
		}
	}
	
	public function doParseFlowUnite()
	{
		if(isFormPost()){
			$this->treeData->addItem('_shippingid',post('_shipping'));
			$this->treeData->addItem('_paymentid',post('_payment'));
			$this->treeData->addItem('_dtime',post('_dtime'));
			//debugx($this->treeData->getItem('_shippingid').','.$this->treeData->getItem('_paymentid').','.$this->treeData->getItem('_dtime'));
			if(len($this->treeData->getItem('_shippingid'))>0 && len($this->treeData->getItem('_paymentid'))>0 && len($this->treeData->getItem('_dtime'))>0){
				$this->iFlow->setUnite($this->treeData->getItem('_shippingid'),$this->treeData->getItem('_paymentid'),$this->treeData->getItem('_dtime'));
				if($this->iFlow->isUnite()) go($this->cfg->toLinkURL('cart.flow'));
			}
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCache()
	{
		parent::doThemeCache();
		$this->theme->doCacheFilterLoop('cart','cpo.iCart.tableCart','');
		$this->theme->doCacheFilterLoop('carts','cpo.iCart.tableCarts','');
		
		$this->theme->doCacheFilterLoop('shipping','cpo.iFlow.tableShipping','');
		$this->theme->doCacheFilterLoop('payment','cpo.iFlow.tablePayment','');
		$this->theme->doCacheFilterLoop('dtime','cpo.iFlow.tableDtime','');
		$this->theme->doCacheFilterLoop('addr','cpo.iFlow.tableAddr','');
	}
}
?>