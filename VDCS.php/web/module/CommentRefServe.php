<?php
class ChannelAccountProductsCommentX extends ChannelAccountBaseX
{
	public function parseList(){	
		
		$type=querys('type');
		$this->initPaging();//初始化分页
		$sqlTerm='';
		if($type) $sqlTerm='type='.DB::q($type,1);
		$table=ProductsComment::queryData($this->ua,$sqlTerm,'c_id desc',$this->p);
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
	public function parseAdd(){
		$this->treeData=newTree();
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		$this->doFormData();
		if($this->isRaiseError()) return;	
		$_status=ProductsComment::addData($this->ua,$this->treeData);		
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
		$types=posts('types');
		$pp=posti('pp');
		$score=posts('score');
		$topic=utilCode::toHTMLTag($content,0,10);	
		if(len($content)<1) $this->addError('内容不能为空');		
		$this->treeData->addItem('c_content',$content);//没有这句话能插入数据库，但是插入的为空
		$this->treeData->addItem('c_score',$score);
		$this->treeData->addItem('productsid',$pp);
		$this->treeData->addItem('c_topic',$topic);
		$this->treeData->addItem('type',$types);
		}

}
?>