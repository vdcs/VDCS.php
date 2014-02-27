<?
class ChannelTComtPostX extends ChannelTBasePostX
{
	
	public function parseDel()
	{
		if(!$this->ready()) return;
		
		$id=queryi('id');
		if($id<1) $this->addError('谈恋爱还得有个对像不是？！');
		
		$this->addVar('id',$id);
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$_status=TMlogTalk::delete($this->ua,$id);
		
		switch($_status){
			case 1:		$this->setStatus('succeed');break;
			case 5:		$this->setStatus('noexist');break;
			case 6:		$this->setStatus('nopermission');break;
			default:	$this->setStatus('failed');break;
		}
	}
	
	public function parseSend()
	{
		if(!$this->ready(true)) return;
		
		$rootid=queryi('rootid');
		if($rootid<1) $this->addError('谈恋爱还得有个对像不是？！');
		
		global $ctl;
		$_message=post('message');
		$_message=TCode::toCut($_message,140);
		$ctl->treeData->addItem($this->TablePX.'message',$_message);
		$ctl->treeData->addItem('isreply',post('isreply',20));
		$ctl->treeData->addItem('replyid',posti('replyid'));
		$ctl->treeData->addItem('replyuid',posti('replyuid'));
		if(!$ctl->e->isCheck()){
			if(len($_message)<1) $this->addError('总要评点什么吧！');
			else if(len($_message)<5) $this->addError('亲，评论太短可不行哦！');
		}
		
		$this->addVar('rootid',$rootid);
		
		$this->loadShield('mlog');
		$this->shieldFormCheck(true,'message');//非法字验证
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$_status=TMlogTalk::send($this->ua,$rootid,$ctl->treeData);
		
		if($_status==1){
			$this->setStatus('succeed');
		}
		else{
			$this->setStatus('failed');
		}
	}
	
}
