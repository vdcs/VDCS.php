<?php

class ChannelAccountTicketQuestionX extends ChannelAccountBaseX{
	public function parseAdd(){
		$this->treeData=newTree();
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		
		$this->doFormData();
		if($this->isRaiseError()) return;
		//$_status=UcLinkmanDomain::add($this->ua,$this->treeData);			
		$_status=TicketQuestion::addData($this->ua,$this->treeData);		
		$_status=1;
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
		}
		
	}

  	//对数据进行相关处理
	protected function doFormData(){
		$content=posts('content');
		$type=posts('type');
		$priority=posts('priority');
		$topic=utilCode::toHTMLTag($content,0,50);	
		if(len($content)<1) $this->addError('问题内容不能为空');		
		$this->treeData->addItem('q_priority',$priority);
		$this->treeData->addItem('type',$type);
		$this->treeData->addItem('q_content',$content);//没有这句话能插入数据库，但是插入的为空
		$this->treeData->addItem('q_topic',$topic);

		}



	public function parseList(){		
		$this->initPaging();//初始化分页

		//$tree=TicketQuestion::getTree($sid,'');
		//$q_content=$tree->getItem('q_content');
		$table=TicketQuestion::queryData($this->ua,'','q_id desc',$this->p);
		$this->setTable($table);

		if(!$total) $total=$table->getRow();
		$this->addVar('total',$total);
		$this->addVarPaging();//在treeVar中增加相关的分页信息
		$this->setSucceed();
	}
	protected function initPaging()
	{
		$listnum=queryi('listnum');//每页显示的数目
		if($listnum<2) $listnum=10;
		$this->p=new libPaging();//分页对象
		$this->p->setConfig('url','?');
		$this->p->setListNum($listnum);//设置每页显示数目
		$this->p->setPage(queryi('page'));//设置当前的页码
	}

	
	public function parseHelp()
	{
		$_status=0;
		$qid=queryi('qid');
		$process=queryi('process');
		debugx($qid.'=='.$process);
		if(!$qid || !$process){
			$this->setStatus('failed');
			$this->setMessage('qid或者process为空');
			return;
		}
		$_status=TicketQuestion::changeProcess($qid,$process);
		if($_status==1){
			$this->setSucceed();
		}else{
			$this->setStatus('failed');
			$this->setMessage('更新失败');
		}
	}
	
}

?>
