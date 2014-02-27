<?
class ChannelShopAuctionX extends WebPortalBase
{
	use WebServeRefXML;
	
	public $view;
	public $vauction;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->view);
		unset($this->vauction);
	}
	
	
	public function doLoadPre()
	{
		$this->uid=$this->ua->id;
	}
	
	
	protected function isUser($status=true)
	{
		if(!$this->ua->isLogin()){
			//debugx('user.nologin');
			if($status) $this->setStatus('user.no');
		}
		return $this->ua->isLogin();
	}
	
	
	protected function isParse()
	{
		return ($this->isRoot() && $this->isData());
	}
	
	
	protected function loadRoot($extend=false)
	{
		$this->view=new PageView();
		$this->view->setAuthMode(0);
		$this->view->doLoad();
		if(!$this->view->isDat()){
			$this->setStatus('_root');
			return;
		}
		$this->setStatus('root');
		$this->view->doParse();
		
		if($extend){
			$this->loadData();
		}
	}
	protected function isRoot()
	{
		return $this->view->isDat();
	}
	
	protected function loadData()
	{
		if($this->vauction) return;
		$this->vauction=new ModelAuctionView();
		//$this->vauction->ua=&$this->ua;
		$this->vauction->setChannel($this->_chn_);
		$this->vauction->doLoad();
		$this->vauction->setRootID($this->view->id);
		$this->vauction->setRootMode($this->view->getData($this->vauction->getRelateField()));
		$this->vauction->doParse();
		$this->addVar('state',$this->vauction->state);
		if($this->vauction->isStart()) $this->addVar('isstart','yes');
		if($this->vauction->isOver()) $this->addVar('isover','yes');
		if($this->vauction->isTime()) $this->addVar('istime','yes');
		if($this->vauction->isTimeOut()) $this->addVar('istimeout','yes');
	}
	protected function isData()
	{
		return $this->vauction->is();
	}
	
	
}
?>