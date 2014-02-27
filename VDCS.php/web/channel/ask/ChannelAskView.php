<?
class ChannelAskView extends ChannelAskBase
{
	use ChannelRefView,ChannelRefViewCall;
	
	
	public function doLoadView()
	{
		$this->view=new PageViewQuestion();
		$this->view->setAuthMode(0);
		$this->view->doLoad();
	}
	
	public function doParse()
	{
		$this->doParseView();
		
		if(!$this->isView()) return;
		
		$this->view->doParseValid();
		$this->view->doParseAnswer();
		
		if($this->view->isSolved()){
			$this->theme->setModule('solved');
		}
	}
	
	public function doThemeCache()
	{
		$this->doThemeCacheView();
		$this->theme->doCacheFilterLoop('answers','cpo.view.tableAnswer');
		$this->theme->doCacheFilterLoop('answerbest','cpo.view.tableAnswerBest');
		$this->theme->doCacheFilterPaging($this->view->pa,'cpo.view.pa');
	}
	
}
?>