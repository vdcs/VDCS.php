<?
class ChannelBlogMy extends ChannelBlogMyBase
{
	
	public function doParse()
	{
		go($this->cfg->getLinkURL('account','p','p=blog&action='.$this->_p_));
		
	}
	
}
?>