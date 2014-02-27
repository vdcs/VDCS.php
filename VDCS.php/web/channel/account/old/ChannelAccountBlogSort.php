<?
class ChannelAccountBlogSort extends ChannelAccountBlogBase
{
	public function doLoad()
	{
		global $cfg;
		$this->MKEY='sort';
		$this->theme->setPageEnd('.'.$this->MKEY);
		
		$this->SortNames=$this->chn->vp('sort:names');
		$this->SortTableName=$this->chn->vp('sort:table.name');
		$this->SortTablePX=$this->chn->vp('sort:table.px');
		
		$cfg->setTitle('sub',$this->SortNames);
		$this->_var['url']=$this->cURL('m','m='.$this->_m_);
	}
	
	public function doParse()
	{
		switch($this->action){
			case 'add':
				$this->doParseAdd();
				break;
			case 'edit':
				$this->doParseEdit();
				break;
			case 'del':
				$this->doParseDel();
				break;
			case 'order':
				$this->doParseOrder();
				break;
			default:
				$this->doParseList();
				break;
		}
	}
	
	public function doParseAdd()
	{
		global $cfg,$ctl;
		if(!isFormPost()) return;
		
		$ctl->treeData->addItem('name',post('name',200));
		if(len($ctl->treeData->getItem('name'))<1) $ctl->e->addItem(''.$this->SortNames.' 名称 不能为空!');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$sql=DB::sqlSelect($this->SortTableName,'max','sortid',$this->_var['sqlquery.key']);
			$sortid=DB::queryInt($sql)+1;
			$rootid=$sortid;
			$ctl->treeData->addItem('sortid',$sortid);
			$ctl->treeData->addItem('keyid',$this->keyid);
			$ctl->treeData->addItem('classid',0);
			$ctl->treeData->addItem('orderid',0);
			$ctl->treeData->addItem('levelid',1);
			$ctl->treeData->addItem('rootid',$rootid);
			$ctl->treeData->addItem('status',1);
			
			$this->SortTableFieldsAdd='sortid,keyid,classid,orderid,levelid,rootid,name,status';
			$sql=DB::sqlInsert($this->SortTableName,$this->SortTableFieldsAdd,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			$this->doMessage('this','succeed:操作成功','您已成功添加了一个'.$this->SortNames.'！',$this->_var['url']);
			go($this->_var['url']);
		}
	}
	
	public function doParseEdit()
	{
		global $cfg,$ctl;
		if(!isFormPost()) return;
		
		if($ctl->id<1) $ctl->id=posti('id');
		$sqlQuery=DB::sqla($this->_var['sqlquery.key'],'sortid='.$ctl->id);
		$sql=DB::sqlSelect($this->SortTableName,'count','*',$sqlQuery,'');
		//debugx($sql);
		if(DB::queryInt($sql)<1){
			$this->doMessage('this','error:操作失败','需要编辑的'.$this->SortNames.'不存在！',$this->_var['url']);
			return;
		}
		
		$ctl->treeData->addItem('name',post('name',200));
		if(len($ctl->treeData->getItem('name'))<1) $ctl->e->addItem(''.$this->SortNames.' 名称 不能为空!');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			
			$this->SortTableFieldsEdit='name';
			$sql=DB::sqlUpdate($this->SortTableName,$this->SortTableFieldsEdit,$ctl->treeData,$sqlQuery);
			//debugx($sql);
			DB::exec($sql);
			
			$this->doMessage('this','succeed:操作成功','您已成功编辑了一个'.$this->SortNames.'！',$this->_var['url']);
			go($this->_var['url']);
		}
	}
	
	public function doParseDel()
	{
		global $cfg,$ctl;
		$sqlQuery=DB::sqla($this->_var['sqlquery.key'],'sortid='.$ctl->id);
		$sql=DB::sqlSelect($this->DataTableName,'count','*',$sqlQuery,'');
		//debugx($sql);
		if(DB::queryInt($sql)>0){
			$this->doMessage('this','error:操作失败','需要删除的'.$this->SortNames.'仍有'.$this->Names.'存在！',$this->_var['url']);
			return;
		}
		
		$sql=DB::sqlDelete($this->SortTableName,DB::sqla($this->_var['sqlquery.key'],'sortid='.$ctl->id));
		//debugx($sql);
		DB::exec($sql);
		
		$this->doMessage('this','succeed:操作成功','您已成功删除了一个'.$this->SortNames.'！',$this->_var['url']);
		go($this->_var['url']);
	}
	
	public function doParseOrder()
	{
		global $cfg,$ctl;
		$isUpdate=false;
		$sql=DB::sqlSelect($this->SortTableName,'','*',$this->_var['sqlquery.key'],'orderid,sortid');
		$tableCat=DB::queryTable($sql);
		$tableCat->doItemBegin();
		for($t=0;$i<$tableCat->getRow();$i++){
			$sortid=$tableCat->getItemValue('sortid');
			$orderid=post('orderid'.$sortid);
			//debugx(($sortid.' - '.$orderid.' - '.toInt($orderid));
			if(len($orderid)>0 && isInt($orderid)){
				$orderid=toInt($orderid);
				if($orderid!=$tableCat->getItemValueInt('orderid')){
					$sql='update '.$this->SortTableName.' set orderid='.$orderid.' where '.$this->_var['sqlquery.key'].' and sortid='.$sortid;
					//debugx($sql);
					DB::exec($sql);
					$isUpdate=true;
				}
			}
			$tableCat->doItemMove();
		}
		$this->doMessage('this','succeed:操作成功','您已成功对'.$this->SortNames.'进行了重新排序！',$this->_var['url']);
		go($this->_var['url']);
	}
	
	public function doParseList()
	{
		$sql=DB::sqlSelect($this->SortTableName,'','*',$this->_var['sqlquery.key'],'orderid,sortid');
		//debugx($sql);
		$this->tableList=DB::queryTable($sql);
	}
	
}
?>