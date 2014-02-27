<?
class CommonPaSearch extends ChannelPaBase
{
	//public $treeSearch,$tableChannel,$tableChannels;
	
	public function doParse(&$that)
	{
		$that->tableChannels=CommonChannelExtend::getStructTermTable('','','all');
		//debugTable($that->tableChannels);
		$that->tableChannel=newTable();
		$that->tableChannel->setFields($this->tableChannels->getFields());
		$that->tableChannels->doBegin();
		while($that->tableChannels->isNext()){
			if(inp($that->tableChannels->getItemValue('model'),'class')>0){
				$that->tableChannel->addItem($that->tableChannels->getItemTree());
			}
		}
		//debugTable($that->tableChannel);
		
		$that->_var['keyword']=queryx('keyword');
		$that->_var['channel']=queryx('channel');
		//ModelLinkitemWord::saveSearch($that->_var['keyword'],$that->_var['channel']);
		
		$that->treeSearch=newTree();
		$that->treeSearch->addItem('keyword',$that->_var['keyword']);
		$that->treeSearch->addItem('channel',$that->_var['channel']);
	}
	
	public function doThemeCache(&$that)
	{
		$that->theme->doCacheFilterTree('search','cpo.treeSearch');
		$that->theme->doCacheFilterLoop('channel','cpo.tableChannel');
	}
	
}
?>