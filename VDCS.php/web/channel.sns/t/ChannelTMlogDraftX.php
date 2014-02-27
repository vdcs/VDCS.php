<?
class ChannelTMlogDraftX extends ChannelTBasePostX
{
	
	public function parseSave()
	{
		if(!$this->ready(true)) return;
		
		$this->doFormData('save');
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		//$this->oTest($ctl->treeData,'treeData');
		
		$_status=TMlogPost::sendDraft($this->ua,$this->treeData);
		
		if($_status>0){
			$this->setStatus('succeed');
			$this->addVar('draftid',$_status);
		}
		else{
			$this->setStatus('failed');
		}
	}
	
	public function parseEdit()
	{
		$id=queryi('id');
		if($id<1) $this->addError('谈恋爱还得有个对像不是？！');
		
		$this->addVar('id',$id);
		$treeRS=null;
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_status=TMlogDraft::isCheck($this->ua,$id,$treeRS);
			if(!$_status){
				switch($_status){
					case -1:	$this->setStatus('noexist');break;
					case -2:	$this->setStatus('nopermission');break;
					default:	$this->setStatus('failed');break;
				}
				return;
			}
			if($treeRS->getCount()>0){
				$treeRS->doAppendTree(TMlogQuery::viewContentDraft($id,'draft'));
			}
			//##########
			$content=$treeRS->getItem('content');
			$treeRS->addItem('_content',$content);
			$content=TAt::filterNames($content);
			$treeRS->addItem('content',$content);
			//##########
			$this->addVar('id',$id);
			$fieldsAr=toSplit('id,type,message,summarys,more,tagids,source,pics,tim,tim_up,summary,content,contents,sp_editions');
			foreach($fieldsAr as $field){
				$this->addVar('data.'.$field,$treeRS->getItem($field));
			}
		}
		
		if(!$this->ready(true)) return;
		
		$this->doFormData('editDraft');
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$_status=TMlogDraft::edit($this->ua,$id,$this->treeData);
		if($_status>0){
			$this->setStatus('succeed');
			$this->addVar('draftid',$_status);
		}
		else{
			$this->setStatus('failed');
		}
	}
	
	protected function doFormData($mode)
	{
		$_type=posti('type');
		if($_type==5){
			$_topic=TCode::toCut(post('topic'),50);//标题长度
			if(!$_topic) $_topic=TCode::toCut(post('message'),20);//如果没有标题，则从内容中截取25个字作为标题
			if(!$_topic) $_topic=TCode::toCut(post('content'),20);//如果没有标题，则从内容中截取25个字作为标题
			$_message=$_topic;
			$_content=post('content',50000);//文章发布内容限制为50000
		}
		else{
			$_type=0;
			$_message=TCode::toCut(post('message'),TCode::MAX_MESSAGE);
			$_content=post('video');
		}
		$ctl->treeData->addItem('type',$_type);
		$ctl->treeData->addItem('topic',$_topic);
		$ctl->treeData->addItem('message',$_message);
		$ctl->treeData->addItem('content',$_content);
		$ctl->treeData->addItem('source',post('source',50));//来源网址限制
		$ctl->treeData->addItem('tagids',post('tagids',100));//标签id限制
		$ctl->treeData->addItem('pic',post('pic',200));//图片限制
		$ctl->treeData->addItem('video',post('video',200));//视频限制
		$ctl->treeData->addItem('music',post('music',200));//音频限制
		$ctl->treeData->addItem('sync_open',post('sync_open',20));
		$ctl->treeData->addItem('sync_weibo',post('sync_weibo',20));//同步到微博
		if(!$ctl->e->isCheck()){
			if($_type==5){
				//if(len($_topic)<3) $this->addError('标题不能为空！');//文章发布，标题限制
				if(!$ctl->e->isCheck()){
					if(len($_content)<1) $this->addError('总要写点内容吧！');
					else if(len($_content)<5) $this->addError('内容不足5个字符，多写点呗');
				}
			}
			else{
				if(len($_message)<5) $this->addError('总要写点什么吧！');
			}
		}
		/*
		foreach($_POST as $_key => $_value){
			if(left($_key,10)=='tags_sort_' && toInt($_value)>0){
				$ctl->treeData->addItem($_key,$_value);
			}
		}
		*/
		
		$this->loadShield('mlog');
		$this->shieldFormCheck(true,'message,content');
		
		//$this->addVarTree($ctl->treeData,'data.');
		//$this->treeData=&$ctl->treeData;
	}
	
}
