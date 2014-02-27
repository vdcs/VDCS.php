<?
class PagePortal extends ManagePortalBaseX
{
	
	public function initer()
	{
		$this->_mi_='';
		$this->ruler->setMode('ignore');
	}
	
	protected function parseList()
	{
		$sql='select * from '.$this->TableName.' where channel='.DB::q($this->channel,1).' and status=1 order by '.$this->getConfig('list.table.order');
		//debugx($sql);
		$this->tableList=DB::queryTable($sql);
		$this->setTable($this->tableList);
		$this->setSucceed();
	}
	
}
