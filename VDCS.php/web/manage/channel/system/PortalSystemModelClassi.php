<?
class PortalSystemModelClassi extends PortalSystemModelClass
{
	
	//####################
	//####################
	protected function doList()
	{
		$this->doHandle();
		$this->setVar('list.num',200);
		$this->doAppendQuery($this->sqlQuery);
		$this->setPageMode('lists');
		$this->loadPaging();
		$this->doPaging();
	 	$this->loadBox();
		$this->addBoxVar('url.add',$this->getURL('action=add&id=[item:nid]'));
		$this->addBoxVar('url.edit',$this->getURL('action=edit&id=[item:nid]'));
		$this->addBoxVar('url.del',$this->getURL('action=del&id=[item:nid]'));
		$this->addBoxVar('url.moveup',$this->getURL('action=move&mode=up&id=[item:nid]'));
		$this->addBoxVar('url.movedown',$this->getURL('action=move&mode=down&id=[item:nid]'));
		$this->addBoxVar('url.sort',$this->getURL('portal=model.sort&action=sort&classid=[item:nid]'));
	  	$this->doBoxParse();
	  	$this->doListFilter($this->box->tableData);
	  	//debugTable($this->box->tableData);
	}
	
}
?>