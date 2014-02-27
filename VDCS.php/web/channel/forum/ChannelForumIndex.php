<?
class ChannelForumIndex extends ChannelForumBase
{
	public $tableClassRoot=null;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->tableClassRoot);
	}
	
	public function doParse()
	{
		$this->tableClassRoot=ModelClassExtend::toTable($this->tableClass);
	}
	
}
?>