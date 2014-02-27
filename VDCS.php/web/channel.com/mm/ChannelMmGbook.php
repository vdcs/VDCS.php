<?
class ChannelMmGbook extends ChannelMmBase
{
	use ModuleRefDisp;
	
	public function doLoad()
	{
		$this->TableName	= 'db_mm_gbook';
		$this->TablePX		= 'm_';
		$this->FieldID		= 'm_id';
		
		$this->_var['query']='uaid='.$this->ua->id;
	}
	
	public function doParse()
	{
		$this->doParseDisp();
	}
	
	
	public function doThemeCache()
	{
		$this->doThemeCacheDisp();
	}
	
}
?>