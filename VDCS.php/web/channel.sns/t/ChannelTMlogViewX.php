<?
class ChannelTMlogViewX extends ChannelTBaseX
{
	
	public function doParseX()
	{
		$this->setStatus('init');
		$this->id=queryi('id');
		switch($this->action){
			case 'content':
				$this->doParseContent();
				break;
			default:
				$this->doParseView();
				break;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseContent()
	{
		$this->setStatus('ready');
		$treeRS=TMlogView::contentTree($this->id);
		$this->setStatus('parser');
		if($treeRS->getCount()<1){
			$this->setStatus('nodata');
			return;
		}
		
		$this->addVar('id',$treeRS->getItem('id'));
		$this->addVar('contents',$treeRS->getItem('contents'));
		$this->addVar('time',VDCSTime::toString($treeRS->getItem('tim')));
		//$this->addVar('abc','abc=='.$treeRS->getItem('id'));
		
		$this->setStatus('succeed');
	}
	
	public function doParseView()
	{
		$this->setStatus('ready');
		
		$this->setStatus('parser');
		
		
	}
	
}
?>