<?
class ChannelAskMyBaseRoot extends ChannelAskMyBase
{
	public $view=null;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->view);
	}
	
	
	public function doLoadRoot()
	{
		$this->view=new PageViewQuestion();
		$this->view->setAuthMode(0);
		$this->view->setVar('id',queryi('rootid'));
		$this->view->doLoad();
		if(!$this->view->isDat()){
			$this->doMessage('this','error:系统提示','错误的数据提交！',$url);
			return;
		}
		$this->view->doParse();
		$this->view->doParseValid();
		
		if($this->view->isSolved()){
			$this->doMessage('this','info:系统提示','问题 已经解答！无法执行本次操作。',$url);
			return;
		}
		if($this->_m_!='choose'){
			if(!$this->view->isTimed()){
				$this->doMessage('this','info:系统提示','问题 已经过期！无法执行本次操作。',$url);
				return;
			}
		}
		$this->rootid=$this->view->getVar('id');
		$this->isRoot=true;
	}
	
	
}
?>