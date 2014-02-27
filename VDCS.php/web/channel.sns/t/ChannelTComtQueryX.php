<?
class ChannelTComtQueryX extends ChannelTBasePostX
{
	
	public function parseQuery()
	{
		$id=queryi('id');
		$nid=queryi('nid');
		$row=TComtQuery::toRow(queryi('row'));
		$row=0;
		$this->tableList=TComtQuery::getRoot($id,$nid,$row);
		$total=TComtQuery::total($id);
		$this->addVar('total',$total);
		$this->setTable($this->tableList);
	}
	
}
