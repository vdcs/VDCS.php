<?
class TPaTagsView extends ChannelPaBase
{
	
	public function doParse(&$that)
	{
		$that->tagname=queryx('tagname');
		$this->doParseView($that);
	}
	
	public function doThemeCache(&$that)
	{
		$this->theme->doCacheFilterTree('view','cpo.treeView');
		$this->theme->doCacheFilterLoop('activeU','cpo.activeUTable');
		$this->theme->doCacheFilterLoop('relatedTags','cpo.relatedTags');
		$this->theme->doCacheFilterLoop('tagMasters','cpo.tagMasters');	
		$this->theme->doCacheFilterLoop('tagLogs','cpo.tagLogs');
	}
	
	public function doParseView(&$that)
	{
		$that->treeView=TTagsQuery::viewInfo(0,$that->tagname);
		
		if($that->treeView->getCount()<1){
			go('/');
			return;
		}
		
		$this->cfg->setTitle('sub',$that->treeView->getItem('name'));
		
		$tagid=$that->treeView->getItem('tagid');
		$this->doTreeDataFilter($that->treeView,$tagid);
		//debug($that->totalArticle);
		//debugTable($that->tableView);
		
		//活跃用户
		$that->activeUTable=TTagsQuery::activeU($tagid,'','','',9);
		//debugTable($that->activeUTable);
		
		//相关标签
		$tagids=$that->treeView->getItem('relates');
		$that->relatedTags=TTagsQuery::getRelatedTags($tagids,'','','',6);
		//debugTable($that->relatedTags);
		
		//标签管理者
		$that->tagMasters=TTagsQuery::getMasters($tagid);
		//$masterids=$that->tagMasters->getValues('userid');
		if($that->tagMasters){
			$masterids=$that->tagMasters->getValues('userid');
			$masteridsAry=explode(',',$masterids);
			$that->treeView->addItem('total_masters',count($masteridsAry));
		}else{
			$that->treeView->addItem('total_masters',0);
		}
		
		//标签更新日记
		$that->tagLogs=TTagsQuery::getTagLogs($tagid);
		$this->doDataFilter($that->tagLogs);
		//debugTable($that->tagLogs);
	}
	
	
	public function doTreeDataFilter(&$treeView,$tagid)
	{
		//debug($this->ua->id);
		//$followNum=TTagsQuery::getFollowTagsByTid($tagid)->getRow();//标签订阅数目
		$isFollow=TTagsQuery::getFollowTagsByUid($this->ua->id,'','status=1 and tagid = '.$tagid,'',1)->getRow();
		//debug($isFollow);
		$myTimes=TTagsQuery::getTotalByUid($this->ua->id,$tagid);
		$tagsTotalTimes=TTagsQuery::getTotalByUid('',$tagid);
		//$treeView->addItem("follow_num",$followNum);
		$treeView->addItem('isfollow',$isFollow);//是否订阅
		$treeView->addItem('myTimes',$myTimes);//我使用该标签了多少次
		$treeView->addItem('userid',$this->ua->id);//当前用户id
		$treeView->addItem('tagsTotalTimes',$tagsTotalTimes);
	}
	
	public function doDataFilter(&$tableData)
	{
		if(!$tableData) return;
		$uids=$tableData->getValues('uuid');
		if(!$uids) return;
		$tableU=DB::queryTable('select userid,u_names from db_user where userid in('.$uids.')');
		$tableData->doAppendFields('uname');
		$tableData->doItemBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('tim',date('Y.m.d H:i',$tableData->getItemValue('tim')));
			$tableData->setItemValue('uname',$tableU->getTermsValue('userid='.$tableData->getItemValue('uuid'),'u_names'));
		}
	}
	
}
