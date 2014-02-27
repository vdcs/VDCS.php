<?
class ChannelAskMyQuestion extends ChannelAskMyList
{
	
	public function doLoadPos()
	{
		$this->_var['query']=DB::sqla($this->_var['query'],'uuid='.$this->ua->id);
	}
	
}
?>