<?
class PageFrameXPortal extends PageFramePortal
{
	use WebServeRefXML;
	
	public function doParse()
	{
		$this->addVar('page',$this->page);
		switch($this->page){
			case 'menu':
				$this->doParseMenu();
				$this->setTable($this->tableMenu);
				break;
			case 'nav':
			default:
				$this->tableNav=$this->getNavTable();
				break;
		}
		$this->setSucceed();
	}
	
}
?>