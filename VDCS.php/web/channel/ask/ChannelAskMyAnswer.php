<?
class ChannelAskMyAnswer extends ChannelAskMyList
{
	
	public function doLoadPos()
	{
		$this->_var['query']=DB::sqla($this->_var['query'],'q_id in (select rootid from '.$this->AnswerTableName.' where uuid='.$this->ua->id.')');
	}
	
}
?>