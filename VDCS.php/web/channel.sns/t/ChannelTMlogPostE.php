<?
class ChannelMlogPostE extends ChannelTMyBaseE
{
	
	public function doParse()
	{
		//debugx($ctl->action);
		if($this->action) $this->theme->setAction($this->action);
		
		$this->tableTagsSort=VDCSDTML::getConfigTable('common.channel/t/tags.sort');
		//$this->tableTagsSort=$cfg->getTable('tags.sort');
		$sql=DB::sqlSelect('db_tags','','*','status=1','sort asc,orderid desc');
		$this->tableTagsItems=DB::queryTable($sql);
		//debugTable($this->tableTagsItem);
		
	}
	
	public function doThemeCache()
	{
		$this->theme->doCacheFilterLoop('tags_sort','cpo.tableTagsSort');
		$this->theme->doCacheFilterLoop('tags_items','cpo.tableTagsItems');
		//$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
