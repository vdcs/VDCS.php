<?
class ChannelApiEntryX extends ChannelApiBaseX
{
	
	public function apiInit(&$oapi)
	{
		$oapi->authiModel('app');
		$oapi->authMode('interface');
		
	}
	
	public function doParse()
	{
		//$this->apiDir($this->_p_);
		$this->apiDir('entry');
		$this->apiParser();
	}
	
}
