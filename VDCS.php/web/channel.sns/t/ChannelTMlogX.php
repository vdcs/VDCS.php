<?
class ChannelTMlogX extends ChannelTBaseX
{
	
	public function doParsePos()
	{
		if($this->tableList){
			$this->addVar('total',$this->tableList->getRow());
			$this->setTable($this->tableList);
			$relayids=$this->tableList->getValues('relayid','id');
			//debugx($relayids);
		}
		if($relayids){
			//debugx($relayids);
			$this->tableRelay=TMlogQuery::getByRID($relayids);
			$this->addTable('relay',$this->tableRelay);
		}
		$this->setSucceed();
	}
	
	protected function parsePlaza()
	{
		$mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getPlaza($this->ua->id,$mode,$value,$row);
	}
	
	protected function parseHome()
	{
		$mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getHome($this->ua->id,$mode,$value,$row);
	}
	
	protected function parseAt()
	{
		$mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getAt($this->ua->id,$mode,$value,$row);
	}
	
	protected function parseTagid()
	{
		$tagid=queryi('tagid');
		$mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getByTagID($tagid,$mode,$value,$row);
	}
	
	protected function parseUid()
	{
		$uid=queryi('uid');
		$mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getByUID($uid,$mode,$value,$row);
	}
        
        protected function parseHot()
        {
                $mode=query('mode');
		$value=queryi('value');
		$row=TMlogQuery::toRow(queryi('row'));
		$this->tableList=TMlogQuery::getHome($this->ua->id,$mode,$value,$row);
        }
	
}
