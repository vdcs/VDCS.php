<?
class PageMainPortal extends ManagePortalBase
{
	
	public function doInit()
	{
		$this->ruler->setMode('ignore');
		$this->theme->setMode('common');
	}
	
	public function doLoad()
	{
		$this->theme->setChannel('',1);
		$this->theme->setPage('main');
	}
	
	public function doParse()
	{
		/*
		$path=appPath('vdcs.mconfig/lang.xcml');
		$this->_dataXML=@file_get_contents($path);
		$xmlParser=new utilXMLParser2();
		$xmlParser->parseString($this->_dataXML);
		$this->_data['isparse']=$xmlParser->isParse();
		$this->_data['error.type']=$xmlParser->getParseError();
		$this->_dataArrays=$xmlParser->getTree();
		
		debugx(print_r($this->_dataArrays,true));
		*/
		
	}
	
	public function doThemeCache()
	{
		
	}
	
}
?>