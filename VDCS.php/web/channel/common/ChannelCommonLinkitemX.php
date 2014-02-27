<?
class ChannelCommonLinkitemX extends ChannelCommonBaseX
{
	
	public function load()
	{
		$this->channel=queryx('channel');
		if(len($this->channel)<1) $this->channel=query('subchannel');
		$this->_var['channel']=$this->channel;
		$this->_var['order']=queryx('order');
		$this->_var['limit']=queryi('limit');
		if(!$this->_var['order']) $this->_var['order']='hot';
		if($this->_var['limit']<1) $this->_var['limit']=5;
	}

	protected function parseLinkitem()
	{
		$tableWord=ModelLinkitemWord::getTable($this->channel,$this->_var['order'],$this->_var['limit']);
		$this->setTable($tableWord);
		$this->setFields('key,topic,linkurl,sort,type,tim,tim_up,total_view,total_get');
	}
	
	protected function parseSearch()
	{
		$tableWord=ModelLinkitemWord::getSearchTable($this->_var['order'],$this->_var['limit']);
		$this->setTable($tableWord);
		$this->setFields('key,topic,linkurl,sort,type,tim,tim_up,total_view,total_get');
	}
	
}
