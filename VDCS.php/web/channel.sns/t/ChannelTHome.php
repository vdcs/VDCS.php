<?
class ChannelTHome extends ChannelTMyBase
{
	
	public function doParse()
	{
		//go(appURL('root'));
		
		$this->tableMlog=TMlogQuery::getHome($this->ua->id);
		//debugTable($this->tableMlog);
		
	}
	
	
	public function doThemeCache()
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableMlog');
		//$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
}
?>