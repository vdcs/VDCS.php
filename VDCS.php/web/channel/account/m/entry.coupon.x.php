<?
class PagePortal extends ManagePortalBaseX
{
	use PortalAccountCouponRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	//####################
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->checkFormData();
		$this->checkUnique();//检测唯一
		
		
		if($this->isRaiseError()) return;
		
		$this->treeData->addItem('tim',DCS::timer());
		
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		$id=DB::insertid();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function checkFormData()
	{
		$type=$this->treeData->getItemInt('type');
		if($type==1){
			$moduleid=$this->treeData->getItemInt('moduleid');
			if(!$moduleid) $this->addError('优惠券 指定产品ID 不能为空');
		}else{
			$money=$this->treeData->getItemNum('money');
			$price_lowest=$this->treeData->getItemNum('price_lowest');
			if($price_lowest<=$money) $this->addError('代金券 最低消费金额 不正确');
			
			$this->treeData->setItem('module','order');//代金券的module为空
		}
		
		$date=$this->treeData->getItem('date');
		$date_expire=$this->treeData->getItem('date_expire');
		if(!utilCheck::isDate($date)) $this->addError('开始时间格式 不正确');
		if(!utilCheck::isDate($date_expire)) $this->addError('到期时间格式 不正确');
		if(strtotime($date)>strtotime($date_expire)) $this->addError('到期时间不能早于开始时间 不正确');
	}
	
	protected function checkUnique()
	{
		$no=$this->treeData->getItem('no');
		$sql='select count(*) from '.$this->TableName.' where no='.DB::q($no,1);
		$_exists=DB::queryInt($sql);
		if($_exists) $this->addError('优惠券 编号已经存在');
	}
	
	
	//####################
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->checkFormData();
		if($this->isRaiseError()) return;
		$this->treeData->addItem('tim_up',DCS::timer());//更新时间
		
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function doHandle()	//$mod=null,$put=1
	{
		parent::doHandle();
		if($this->isHandle()){
			$ids=$this->chn->getVar('handle.ids');
			switch($this->chn->getVar('handle.value')){
				case 'delete':
					if($this->attrm->is()) $this->attrm->doDataRemove($ids);
					break;
			}
		}
	}
	protected function parseList()
	{
		$this->doHandle();
		
		$this->doListServe();
	}
	
	
	
}
?>