<?php
class ChannelSupportBasic extends WebPortalBase
{
	use WebPortalRefControl;
	
	public $tableMenu=null;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableMenu);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		global $cfg;
		$this->loadPages();
		
		$cfg->setTitle('chn',$cfg->v('title'));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParsePre()
	{
		$this->doParseMenu();
	}
	public function doParseMenu()
	{
		global $cfg;
		$faqMenu=$cfg->getConfigValue('config.faq.menu');
		if(!$faqMenu) $faqMenu='about,contact,payment,help';
		$aryMenu=explode(',',$faqMenu);
		$this->tableMenu=newTable();
		$this->tableMenu->setFields('page,title,url');
		for($a=0;$a<count($aryMenu);$a++){
			$treeItem=newTree();
			$treeItem->addItem('page',$aryMenu[$a]);
			$title=$cfg->v('title.'.$aryMenu[$a]);if(!$title) $title='['.$aryMenu[$a].']';
			$treeItem->addItem('title',$title);
			$url=$cfg->toLinkURL($aryMenu[$a]);if(!$url) $url=$cfg->toLinkURL('faq','page='.$aryMenu[$a]);
			$treeItem->addItem('url',$url);
			$this->tableMenu->addItem($treeItem);unsetr($treeItem);
		}
	}
	
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('menu','cpo.tableMenu');
	}
}
?>