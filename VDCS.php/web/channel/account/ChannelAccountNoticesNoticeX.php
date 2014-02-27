<?
class ChannelAccountNoticesNoticeX extends ChannelAccountBaseX
{
	
	public function doParse()
	{
		$this->setStatus('init');
		switch($ctl->action){
			case 'status':
				$this->doParseStatus();
				break;
			case 'delete':
				$this->doParseDelete();
				break;
			case 'list':
				$this->doParseList();
				break;
			case 'new':
				$this->doParseNew();
				break;
			case 'view':
				$this->doParseView();
				break;
			case 'test':
				NoticeAction::send($this->ua,'测试通知('.DCS::time().')',null);
				break;
		}
	}
	
        
	/*
	########################################
	########################################
	*/
	public function doParseList()
	{
		$this->initPaging();
		$this->tableList=NoticeQuery::querier($this->ua,null,$this->p);
		//debugx('total='.$this->p->getTotal().', page='.$this->p->getPage().', pagetotal='.$this->p->getPageTotal().', listnum='.$this->p->getListNum());
		//debugTable($this->tableList);
		//debugx(queryi('p'));
		
		$this->setTable($this->tableList);
		$this->addVar('total',$this->tableList->getRow());
		$this->addVarPaging();
	}
	protected function initPaging()
	{
		$listnum=queryi('listnum');
		if($listnum<3) $listnum=10;
		$this->p=new libPaging();
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);
		$this->p->setPage(queryi('page'));
	}
	
	public function doParseNew()
	{
		$this->setStatus('ready');
		$this->setStatus('parser');
		
		$reTree=NoticeAction::queryStat($this->ua);
		//debugTree($reTree);
		$this->addVarTree($reTree);
		//$this->addVar('id',$treeRS->getItem('id'));
		
		NoticeAction::setx($this->ua,'new_notice',0);
		
		$this->setStatus('succeed');
	}
	
	public function doParseView()
	{
		$this->setStatus('ready');
		
		$this->setStatus('parser');
		
		
	}
	
	
	public function doParseStatus()
	{
		$this->setStatus('ready');
		//if(!isPost()) return;
		$this->setStatus('parser');
		
		$ids=querys('ids',1);
		//debugx($ids);
		$type=query('type');
		if(!$ctl->e->isCheck()){
			if(!$ids) $ctl->e->addItem('操作对象为空！','ids');
		}
		if(!$ctl->e->isCheck()){
			if(!$type) $ctl->e->addItem('操作类型为空！','type');
		}
		if($ctl->e->isCheck()){$this->doRaiseError();}
		else{
			$_status=UcNoticeAction::status($this->ua,$type,$ids);
			switch($_status){
				case 1:
					$this->setStatus('succeed');
					break;
				case 2:
					$this->setStatus('not');
					break;
				default:
					$this->setStatus('failed');
					break;
			}
		}
	}
	
	public function doParseDelete()
	{
		$this->setStatus('ready');
		//if(!isPost()) return;
		$this->setStatus('parser');
		
		$ids=querys('ids',1);
		//debugx($ids);
		if(!$ctl->e->isCheck()){
			if(!$ids) $ctl->e->addItem('删除对象为空！','ids');
		}
		if($ctl->e->isCheck()){$this->doRaiseError();}
		else{
			$_status=UcNoticeAction::delete($this->ua,$ids);
			switch($_status){
				case 1:
					$this->setStatus('succeed');
					break;
				case 2:
					$this->setStatus('not');
					break;
				default:
					$this->setStatus('failed');
					break;
			}
		}
	}
	
	
}
