<?
class PagePortal extends ManagePortalBaseX
{
	use PortalSystemModelClassRef;
	
	public function doLoad()
	{
		$this->refLoad();
	}
	
	protected function parseAdd()
	{
		if(!$this->refAddLoad()) return;
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		//$this->doFormCheck();
		//if($this->isRaiseError()) return;
		$this->fatherid=$this->treeData->getItemInt('fatherid');
		$this->classid=$this->treeData->getItemInt('classid');
		$this->orderid=$this->treeData->getItemInt('orderid');
		$this->sqlQuery='classid='.$this->classid;
		$this->sqlQuery=$this->sqlRelate($this->sqlQuery);
		//$sqlRelates=DB::sqla('classid='.$this->classid,'channel='.DB::q($this->channeli,1));
		$sql=DB::sqlSelect($this->TableName,'count','*',$this->sqlQuery);//$this->sqlRelates.
		$exists=DB::queryInt($sql);
		if($this->classid<1 || $exists>0){
			$this->addError($this->getLang('error.exist.id'));//
		}
		
		if(($this->fatherid<1)){
			$this->fatherid=0;
			$this->levelid=1;
			$sql=DB::sqlSelect($this->TableName,'max','id');
			$this->rootid=DB::queryInt($sql)+1;//'channel=xxx'
		}else{
			//$sql='select * from '.$this->TableName.' where '.$this->sqlQuerys.' classid='.$this->fatherid;
			$sql=DB::sqlSelect($this->TableName,'','*','id='.DB::q($this->fatherid,1));
			$treeTmp=DB::queryTree($sql);
			if($treeTmp->getCount()<1){
				$this->addError($this->getLang('error.not.fatherid'));
			}else{
				$this->levelid=$treeTmp->getItemInt('levelid')+1;
				$this->rootid=$treeTmp->getItemInt('rootid');
				$this->orderid=$treeTmp->getItemInt('orderid');
				$sql='select orderid from '.$this->TableName.' where rootid='.$this->rootid.' and orderid>'.$this->orderid.' and levelid<='.($this->levelid-1).' order by orderid asc limit 0,1';//'.$this->sqlQuerys.'
				$this->orderid=DB::queryInt($sql);
				if($this->orderid<1){
					$sql='select max(orderid) from '.$this->TableName.' where  rootid='.$this->rootid;//'.$this->sqlQuerys.'
					$this->orderid=DB::queryInt($sql)+1;
				}
			}
			unset($treeTmp);
		}
		
		if($this->isRaiseError()) return;

		$this->treeData->addItem('orderid',$this->orderid);
		$this->treeData->addItem('rootid',$this->rootid);
		$this->treeData->addItem('fatherid',$this->fatherid);
		$this->treeData->addItem('classid',$this->classid);
		$this->treeData->addItem('levelid',$this->levelid);
		$this->treeData->addItem('tim',DCS::timer());
		$this->treeData->addItem('channel',$this->channeli);
		$this->treeData->addItem('module',$this->channeli);
		$_status=DB::execInsertx($this->TableName,$this->getConfig('table.fields.add'),$this->treeData);
		if($_status){
			$sql='update '.$this->TableName.' set orderid=orderid+1 where rootid='.$this->rootid.' and orderid>='.$this->orderid.' and classid<>'.$this->classid;//'.$this->sqlQuerys.'
			$_status=DB::exec($sql);
		}
		//dcsLog('')
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
	
	protected function parseDel()
	{
		if(!$this->isChecked('lock')) return;
		$this->id=$this->id;
		
		$sqlQuery=$this->FieldID.'='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'count','*',$sqlQuery);
		if(DB::queryInt($sql)<1){
			$this->setMessages('!handle',$this->getLang('error.not.exist'),$this->getURL('action=list'));
			return;
		}
		$sqlQuery='fatherid='.$this->id;
		$sql=DB::sqlSelect($this->TableName,'count','*',$sqlQuery);
		if(DB::queryInt($sql)>0){
			$this->setMessages('!handle',$this->getLang('error.exist.child'),$this->getURL('action=list'));
			return;
		}
		$this->channelTableName=$this->getConfig($this->channeli.'/','table.name');
		if($this->channelTableName){
			$sql=DB::sqlSelect($this->channelTableName,'count','*','classid='.DB::q($this->id,1));
			if(DB::queryInt($sql)>0){
				$this->setMessages('!handle',$this->getLang('error.exist.data'),$this->getURL('action=list'));
				return;
			}
		}
		
		//##########
		$sqlQuery=$this->FieldID.'='.$this->id;
		$sql=DB::sqlDelete($this->TableName,$sqlQuery);
		$_status=DB::exec($sql);
		$this->setMessages('!handle',$this->getLang('handle.ok.'.$this->action),$this->getURL('action=list'));
		if($_status) $this->setSucceed();
	}
	
	//子分类的信息
	protected function parseGetinfo()
	{
		$classid=queryi('classid');
		if(!$classid){
			$this->setStatus('failed');
			$this->setMessage('classid 不能为空');
		}
		$sql=DB::sqlSelect($this->TableName,'','*','fatherid='.DB::q($classid,1));
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
		if(!$id || !$fatherid){
			$this->setStatus('failed');
			$this->setMessage('信息不完整');
			return;
		}
		$oldTree=newTree();
		$sql==DB::sqlSelect($this->TableName,'','*','id='.DB::q($id,1));
		$oldTree=DB::queryTree($sql);
		
		$fatherTree=newTree();
		$sql=DB::sqlSelect($this->TableName,'','*','id='.DB::q($fatherid,1));
		$fatherTree=DB::queryTree($sql);
		if($fatherTree->getCount()<1){
			$this->setStatus('failed');
			$this->setMessage('目标分类不存在！');
			return;
		}
		$rootid=$fatherTree->getItemInt('rootid');
		$levelid=$fatherTree->getItemInt('levelid')+1;
		$sql_order='select max(orderid) from '.$this->TableName.' where rootid='.$rootid;
		$orderid=DB::queryInt($sql_order)+1;
		$tree=newTree();
		$tree->addItem('orderid',$orderid);
		$tree->addItem('levelid',$levelid);
		$tree->addItem('rootid',$rootid);
		$tree->addItem('fatherid',$fatherid);
		$_status=DB::execUpdatex($this->TableName,$this->getConfig('table.fields.edit'),$tree,'id='.DB::q($id,1));
		
		$oldrootid=$oldTree->getItemInt('rootid');
		$oldorderid=$oldTree->getItemInt('orderid');
		$oldlevelid=$oldTree->getItemInt('levelid');
		$orderid_change=$orderid-$oldorderid;
		$levelid_change=$levelid-$oldlevelid;
		
		if($_status){
			//$sql='update '.$this->TableName.' set orderid=orderid+'.$orderid_change.',rootid='.$rootid.',levelid=levelid+'.$levelid_change.' where rootid='.$oldrootid;
			//$_status=DB::exec($sql);
		}
		
		if($_status){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('移动失败！');
		}
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
		//if($this->channeli) $this->doAppendQuery('channel='.DB::q($this->channeli,1));
		$this->_var['paging.listnum']=999;
		$this->_var['paging.show']=false;
		$this->doListServe();
	}
	protected function doListFilter(&$tableData)
	{
		
	}
	
	
	protected function parseSort()
	{
		$arr=$_POST;
		foreach($arr as $classid=>$orderid){
			$classid=toInt($classid);
			$orderid=toInt($orderid);
			
			$sqlTerm=$this->sqlRelate('classid='.DB::q($classid,1));
			$sql='update '.$this->TableName.' set orderid='.$orderid.' where '.$sqlTerm;
			DB::exec($sql);
		}
		$this->setSucceed();
	}
	
	
}
?>