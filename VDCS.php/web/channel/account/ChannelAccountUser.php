<?
class ChannelAccountUser extends ChannelAccountBase
{
	public $treeUav;
	
	public function doLoad()
	{
		//,query('name'),query('email')
		$this->treeUav=$this->ua->queryTree(queryi('id'),1);
		//debugTree($this->treeUav);
	}
	
	public function doParse()
	{
		
	}
	
	public function doThemeCache()
	{
		$this->theme->doCacheFilterTree('uav','cpo.treeUav');
	}
	
}
?>