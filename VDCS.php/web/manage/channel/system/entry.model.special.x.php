<?
class PagePortal extends ManagePortalBaseX
{
	use PortalSystemModelSpecialRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	
	protected function doHandle()
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
		if(!$this->refListLoad()) return;
		$this->_var['paging.listnum']=999;
		$this->_var['paging.show']=false;
		$this->doListServe();
	}
	
	protected function doListFilter(&$tableData)
	{
		
	}
	
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		$this->specialid=$this->treeData->getItemInt('specialid');
		$this->orderid=$this->treeData->getItemInt('orderid');
		$sql=DB::sqlSelect($this->TableName,'count','*',' specialid='.$this->specialid);//$this->sqlRelates.
		$exists=DB::queryInt($sql);
		if($this->specialid<1 || $exists>0){
			$this->addError($this->getLang('error.exist.id'));//
		}
		
		if($this->isRaiseError()) return;
		$this->treeData->addItem('channel',$this->channeli);
		$this->treeData->addItem('orderid',$this->orderid);
		$this->treeData->addItem('specialid',$this->specialid);
		$this->treeData->addItem('tim',DCS::timer());
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	protected function parseEdit()
	{
		if(!$this->refEditLoad()) return false;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		if($this->isRaiseError()) return;
		$this->treeData->addItem('tim_up',DCS::timer());
		DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$this->treeData,$this->sqlQuery,$this->treeRS);
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		$this->setSucceed();
	}
	
	
	//子分类的信息
	protected function parseGetinfo()
	{
		$specialid=queryi('specialid');
		if(!$specialid){
			$this->setStatus('failed');
			$this->setMessage('specialid 不能为空');
		}
		$sql=DB::sqlSelect($this->TableName,'','*','fatherid='.DB::q($specialid,1));
		$table=newTable();
		$table=DB::queryTable($sql);
		$this->setTable($table);
		$this->setSucceed();
	}
	
	//移动分类
	protected function parseMove()
	{
		$id=queryi('id');
		$fatherid=queryi('fatherid');
		if(!$id){
			$this->setStatus('failed');
			$this->setMessage('信息不完整');
			return;
		}
		$sqll=DB::sqlSelect($this->TableName,'','levelid','specialid='.DB::q($fatherid,1));
		$levelid=DB::queryInt($sqll)+1;//levelid
		$tree=newTree();
		$tree->addItem('levelid',$levelid);
		$tree->addItem('fatherid',$fatherid);
		$_status=DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$tree,'id='.DB::q($id,1));
		if($_status){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('移动失败');
		}
	}
	
	protected function parseDel()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=$this->id;
		//debugx('channel.tablename='.$this->channelTableName);
		if(len($this->channelTableName)<1){
			$this->setMessages('!handle',$this->getLang('error.no.channel'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery=$this->sqlQuerys.' fatherid='.$this->id;
		if(DB::toQueryNum($this->TableName,'count','*',$sqlQuery)>0){
			$this->setMessages('!handle',$this->getLang('error.exist.child'),$this->getURL('action=list'));
			return;
		}
		//##########
		$sqlQuery=''.$this->DefineID.'='.$this->id;
		if(DB::toQueryNum($this->channelTableName,'count','*',$sqlQuery)>0){
			$this->setMessages('!handle',$this->getLang('error.exist.data'),$this->getURL('action=list'));
			return;
		}
		//##########
		$sqlQuery=$this->sqlQuerys.$this->DefineID.'='.$this->id;
		$sql='delete from '.$this->TableName.' where '.$sqlQuery;
		DB::exec($sql);
		$this->doUpdateCache();
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
	}
}
?>