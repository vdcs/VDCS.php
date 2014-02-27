<?
class ChannelShopAuction extends ChannelShopBase
{
	public $view,$vattr,$vauction;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->view,$this->vattr,$this->vauction);
	}
	
	
	public function doLoadPre()
	{
		if($this->_m_){
			//$this->theme->setPage($this->_p_.'.'.$this->_m_);
		}
	}
	
	
	protected function loadRoot($extend=false)
	{
		$this->view=new PageView();
		$this->view->setAuthMode(0);
		$this->view->doLoad();
		if(!$this->view->isDat()){
			go('./');
			return;
		}
		$this->view->doParse();
		
		if($extend){
			$this->loadAttr();
			$this->loadData();
		}
	}
	protected function isRoot()
	{
		return $this->view->isDat();
	}
	
	protected function loadAttr()
	{
		$this->vattr=new ModelAttrView();
		$this->vattr->setChannel($this->_chn_);
		$this->vattr->setRootID($this->id);
		$this->vattr->doParse();
	}
	
	protected function loadData()
	{
		$this->vauction=new ModelAuctionView();
		//$this->vauction->ua=&$this->ua;
		$this->vauction->setChannel($this->_chn_);
		$this->vauction->doLoad();
		$this->vauction->setRootID($this->id);
		$this->vauction->setRootMode($this->view->getData($this->vauction->getRelateField()));
		$this->vauction->doParse();
	}
	protected function isData()
	{
		return $this->vauction->is();
	}
	
	
	public function doLoad()
	{
		
	}
	
	public function doParse()
	{
		
	}
	
	
	public function doThemeCachePre()
	{
		parent::doThemeCachePre();
		$this->theme->output=$this->view->toDTMLCache($this->theme->output,'cpo.view');
		if($this->vattr) $this->theme->output=$this->vattr->toDTMLCache($this->theme->output,'cpo.vattr');
		if($this->vauction) $this->theme->output=$this->vauction->toDTMLCache($tthis->heme->output,'cpo.vauction');
	}
	
}
?>