<?php
class ChannelForumPostX extends ChannelForumBaseX
{
	
	public function parseTest()
	{
		$fs=DB::queryTable('select * from dbs_class')->getFields();
		debugx($fs);
	}
	
	public function parseSend()
	{
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		$this->treeData=newTree();
		$this->doFormData();
		if($this->isRaiseError()) return;
		$_status=ForumPost::create($this->ua,$this->treeData);	
		if($_status==1){
			$this->setMessage('发帖成功');
			$this->setSucceed();
		}else{
			$this->setMessage('发帖失败');
			$this->setStatus('failed');
		}
		
	}
	
	public function parseComment()
	{
		if(!isPost()){
			$this->setStatus('notPost');
			return;
		}
		$this->treeData=newTree();
		$this->checkFormComment();
		if($this->isRaiseError()){
			$this->setStatus('failed');
			$this->setMessage($this->getVar('error_message'));
			return;
		}				
		$_status=ForumPost::createData($this->ua,$this->treeData);
		if($_status==1){
			$this->setMessage('评论成功');
			$this->setSucceed();
		}else{
			$this->setMessage('评论失败');
			$this->setStatus('failed');
		}
		
	}

  	//对数据进行相关处理
	protected function doFormData()
	{
		$topic=posts('topic');
		$remark=posts('remark');
		$classid=posti('classid');
		
		if(!$classid) $this->addError('请选择分类');
		if(len($topic)<1) $this->addError('标题不能为空');
		if(len($remark)>5000) $this->addError('帖子内容不能超过5000字');
		if(len($remark)<10) $this->addError('帖子内容不能少于10个字');
		
		$this->addData('topic',$topic);
		$this->addData('remark',$remark);
		$this->addData('classid',$classid);
	}
	
	protected function checkFormComment()
	{
		$classid=posti('classid');
		$rootid=posti('rootid');
		$type=posti('type');
		$remark=posts('remark');
		$replyid=posts('replyid');
		
		if(len($remark)<10) $this->addError('评论内容不能少于10个字');
		if(!$classid) $this->addError('类别不能为空');
		if(!$rootid) $this->addError('评论对象不存在');
		
		$this->addData('remark',$remark);
		$this->addData('classid',$classid);
		$this->addData('rootid',$rootid);
		$this->addData('replyid',$replyid);
		$this->addData('type',$type);
	}
}

?>
