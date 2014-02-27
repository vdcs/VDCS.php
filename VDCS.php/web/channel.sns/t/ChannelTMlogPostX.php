<?
class ChannelTMlogPostX extends ChannelTBasePostX
{
	
	public function parseDel()
	{
		if(!$this->ready()) return;
		
		$id=queryi('id');
		if($id<1) $this->addError('ID('.$id.')不存在');
		
		$this->addVar('id',$id);
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$_status=TMlogPost::del($this->ua,$id);
		
		switch($_status){
			case 1:		$this->setSucceed();break;
			case 5:		$this->setStatus('noexist');break;
			case 6:		$this->setStatus('nopermission');break;
			default:	$this->setStatus('failed');break;
		}
	}
	
	public function parseSend()
	{
		if(!$this->ready()) return;
		
		$this->doFormData('send');
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		//$this->oTest($this->treeData,'treeData');
		
		$_status=TMlogPost::send($this->ua,$this->treeData);
		
		if($_status==1){
			$this->setSucceed();
		}
		else{
			$this->setStatus('failed');
		}
	}
	
	public function parseEdit()
	{
		if(!$this->ready(true)) return;
		
		$id=queryi('id');
		if($id<1) $this->addError('ID('.$id.')不存在');
		
		$this->addVar('id',$id);
		$treeRS=null;
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$_status=TMlogPost::isCheck($this->ua,$id,$treeRS);
			if(!$_status){
				switch($_status){
					case -1:	$this->setStatus('noexist');break;
					case -2:	$this->setStatus('nopermission');break;
					default:	$this->setStatus('failed');break;
				}
				return;
			}
			if($treeRS->getCount()>0){
				$treeRS->doAppendTree(TMlogQuery::viewContent($id));
			}
			//##########
			$content=$treeRS->getItem('content');
			$treeRS->addItem('_content',$content);
			$content=TAt::filterNames($content);
			$treeRS->addItem('content',$content);
			//##########
			//debugTree($treeRS);
			$this->addVar('id',$id);
			$fieldsAr=toSplit('id,type,message,summarys,more,tagids,source,pics,tim,tim_up,summary,content,contents,sp_editions');
			foreach($fieldsAr as $field){
				$this->addVar('data.'.$field,$treeRS->getItem($field));
			}
		}
		
		/*
		$rootid=25;
		$tableAt=TMlogAt::getByRID($rootid);
		$atouids=$tableAt->getValues('uuid');
		debugx($atouids);
		$atuids=TMlogAt::save($this->ua,$rootid,0,'dfasdfas @测试员 dfasdf',$atouids);
		
		//@ 清理
			TMlogPost::atClear($ua,$rootid,$atuids);
		
		$this->addVar('atouids',$atouids);
		$this->addVar('atuids',$atuids);
		*/
		
		$this->doFormData('edit');
		
		//$this->vcodeFormCheck();
		if($this->isRaiseError()) return;
		
		$_status=TMlogPost::edit($this->ua,$id,$this->treeData);
		
		if($_status==1){
			$this->setSucceed();
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
			//$_content=post('media_video');
		}
		$this->treeData->addItem('type',$_type);
		$this->treeData->addItem('topic',$_topic);
		$this->treeData->addItem('message',$_message);
		$this->treeData->addItem('content',$_content);
		$this->treeData->addItem('source',post('source',50));//来源网址限制
		$this->treeData->addItem('tagids',post('tagids',100));//标签id限制
		$this->treeData->addItem('pic',post('pic',200));//图片限制
		$this->treeData->addItem('media_video',post('media_video',200));//视频限制
		$this->treeData->addItem('media_music',post('media_music',200));//音频限制
		$this->treeData->addItem('sync_open',post('sync_open',20));
		$this->treeData->addItem('sync_weibo',post('sync_weibo',20));//同步到微博
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if($_type==5){
				//if(len($_topic)<3) $this->addError('标题不能为空！');//文章发布，标题限制
				$checknext=!$this->isErrorCheck();
				if($checknext){
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
				$this->treeData->addItem($_key,$_value);
			}
		}
		*/
		
		$this->loadShield('mlog');
		$this->shieldFormCheck(true,'message,content');
		
		//$this->addVarTree($this->treeData,'data.');
	}
	
}
