<?
class DemoPaList extends ChannelPaBase
{
	use ChannelRefList;		//ChannelRefListCall
	
	
	public function doInit(&$that)	
	{
		$this->paInitList($that);
		$this->portalPage='list';
	}
	
	public function doLoad(&$that)	
	{
		$this->theme->setPage($this->portalPage);
	}
	
	public function doParseListQuery()
	{
		switch($this->_p_){
			case 'hot':
				$this->queryAppend($this->cfg->chn->getSQLStruct('table.px').'ishot=1');
				break;
		}
	}
	
	public function doParse(&$that)	
	{
		$this->doParseList();
	}
	
	public function doThemeCache(&$that)
	{
		$this->doThemeCacheList();
	}
	
}
