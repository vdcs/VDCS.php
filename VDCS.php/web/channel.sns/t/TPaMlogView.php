<?
class TPaMlogView extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$that->id=queryi('id');
		$this->doParseView($that);
	}
	
	public function doThemeCache(&$that)
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('media','cpo.tableMedia');
		$this->theme->doCacheFilterLoop('media_pic','cpo.tablePic');
		$this->theme->doCacheFilterLoop('tags_items','cpo.tableTagsItems');
		$this->theme->doCacheFilterLoop('arts_items','cpo.tableArtRelated');
		//$this->theme->doCacheFilterPaging($this->p,'cpo.p');
		$this->theme->doCacheFilterTree('uad','cpo.treeUa');
		//$this->theme->doCacheFilterTree('uad','cpo.','uData');
	}
	

	public function doParseView(&$that)
	{
		$that->treeView=TMlogView::infoTree($that->id);
		if($that->treeView->getCount()<1){
			go('/');
			return;
		}
		//debugTree($that->treeView);
		
		$title='';
		$that->type=$that->treeView->getItemInt('type');
		if($that->type==5){
			$that->treeView->addItem('topic',$that->treeView->getItem('message'));
			$title=$that->treeView->getItem('topic');
			$source=$that->treeView->getItem('source');
			if(!$source){
				$that->treeView->setItem('source','GAME+');
			}
		}
		else{
			$contents=$that->treeView->getItem('summarys');
			$contents.=$that->treeView->getItem('contents');
			$that->treeView->addItem('contents',$contents);
			$title=$that->treeView->getItem('summarys');
			$title=utilCode::toHTMLTag($title,1);
			$title=utilCode::toCut($title,50);
		}
		$that->treeView->addItem('title',$title);
		$that->cfg->setTitle($title);
		
		$that->tableMedia=newTable();
		$that->tableMedia->setFields('type,url,summary');
		$that->tablePic=newTable();
		$that->tablePic->setFields('type,url,summary');
		$pic_path='';
		$medias=$that->treeView->getItem('medias');	//如果是微文，则需要转化图片
		if($medias) $mediaa=VDCSDATA::deCode($medias);
		if($mediaa){
			//debuga($mediaa);
			$treeItem=newTree();
			foreach($mediaa[0] as $n=>$itema){
				//debuga($itema);
				$treeItem->addItem('type',$itema[0]);
				$treeItem->addItem('url',$itema[1]);
				$treeItem->addItem('summary',$itema[2]);
				$that->tableMedia->addItem($treeItem);
				if($itema[0]=='pic') $that->tablePic->addItem($treeItem);
			}
		}
		//debugTable($that->tableMedia);
		//debugTable($that->tablePic);
		
		$that->treeUa=$that->ua->queryTree($that->treeView->getItemInt('uuid'),1);
		
		/*关于标签的相关信息*/
		/*
		$tagids=$that->treeView->getItem('tagids');
		if($tagids){
			$sql=DB::sqlSelect('db_tags','','*','tagid in ('.$tagids.')','sort asc,orderid desc');
			$that->tableTagsItems=DB::queryTable($sql);
			$rootids=DB::queryTable('select rootid from db_mtags where tagid in('.$tagids.') order by tim desc')->getValues('rootid');
			if($rootids){
				//debug($rootids);
				$rootids=implode(array_unique(explode(',',$rootids)),',');//去除重复
				$that->tableArtRelated=DB::queryTable('select id,message from db_mlog where id in('.$rootids.') and type=5 order by tim_up limit 10');
			}
		}
		*/
	}
	
}
