<?php
class ChannelAccountTicketAnswerX extends ChannelAccountBaseX
{
	
	public function parseAdd()
	{
		$_status=0;
		$this->treeData=newTree();	
		 //$this->doFormData();
		  //if($this->isRaiseError()) return;

		$content=posts('content');
                $sid=posti('sid');
		$q_process=posti('q_process');
		if(len($content)<5){
			$this->setStatus('failed');
			$this->setMessage('内容太短');
			return;
		}

		$this->treeData->addItem('a_content',$content);
		$this->treeData->addItem('q_id',$sid);
		$this->treeData->addItem('q_process',$q_process);
		$_status=TicketAnswer::addData($this->ua,$this->treeData);
		if($_status==1){
			$this->setStatus('succeed');
		}else{
			$this->setStatus('failed');
		}  
	}

  	//对数据进行相关处理
	/**protected function doFormData()
	{
		$content=posts('content');
                $sid=posti('sid');
		$q_process=posti('q_process');		
		if(len($content)<1) $this->addError('不能为空');			
		$this->treeData->addItem('a_content',$content);//没有这句话能插入数据库，但是插入的为空
		$this->treeData->addItem('q_id',$sid);
		$this->treeData->addItem('q_process',$q_process);

	}**/

	public function ParseList()
	{
		$this->initPaging();//初始化分页
		$sid=queryi('sid');
	        $tree=TicketQuestion::getTree($sid);
		/*
		$q_content=$tree->getItem('q_content');
	        $q_id=$tree->getItem('q_id');
		$tim=$tree->getItem('q_tim');
		$this->addVar('q_tim',$tim);
		$this->addVar('q_id',$q_id);
		$this->addVar('q_content',$q_content);
		*/
		$this->addVarTree($tree);
		$sqlTerm='q_id='.$sid;
		$table=TicketAnswer::queryData($this->ua,$sqlTerm,'a_id desc',$this->p);		
		$name=$this->ua->getNames();
		$this->addVar('name',$name);

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

}

?>