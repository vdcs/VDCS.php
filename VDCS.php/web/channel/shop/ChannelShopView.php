<?
class ChannelShopView extends ChannelShopBase
{
	use ChannelRefView,ChannelRefViewCall;
	
	public $vattr;
	public $vauction;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->vattr,$this->vauction);
	}
	
	
	public function doParse()
	{
		$this->doParseView();
		if(!$this->isView()) return;
		$this->doParseViewData();
		
		$this->view->doParsePic('pic');
		
		$this->doParseHistory();
		
		if($this->cfg->cfg('attr')=='on') $this->doParseAttr();
		if($this->cfg->cfg('auction')=='on') $this->doParseAuction();
	}
	
	protected function doParseHistory()
	{
		$historys=DCS::cookieGet('historys',$this->_chn_);
		if(utilStrings::isExtentValue($historys,$this->id,',')) $historys=utilStrings::toExtentFilter($historys,$this->id,',');
		$historyid=utilStrings::toExtentAppend($historys,$this->id,',',5);
		DCS::cookieSet('historys',$historys,$this->_chn_);
	}
	
	protected function doParseAttr()
	{
		$this->vattr=new ModelAttrView();
		$this->vattr->setChannel($this->_chn_);
		$this->vattr->setRootID($this->id);
		$this->vattr->doParse();
	}
	protected function doParseAuction()
	{
		$this->vauction=new ModelAuctionView();
		//$this->vauction->ua=&$this->ua;
		$this->vauction->setChannel($this->_chn_);
		$this->vauction->doLoad();
		$this->vauction->setRootID($this->id);
		$this->vauction->setRootMode($this->view->getData($this->vauction->getRelateField()));
		$this->vauction->doParse();
		if($this->vauction->is()){
			$this->theme->setModule('auction');
		}
	}
	
	public function doThemeCache()
	{
		$this->doThemeCacheView();
		$this->theme->doCacheFilterLoop('data','cpo.tableData');
		$this->theme->doCacheFilterLoop('data.pic','cpo.tableDataPic');
		$this->theme->doCacheFilterLoop('data.file','cpo.tableDataFile');
		if($this->vattr) $this->theme->output=$this->vattr->toDTMLCache($this->theme->output,'cpo.vattr');
		if($this->vauction) $this->theme->output=$this->vauction->toDTMLCache($this->theme->output,'cpo.vauction');
	}
	
}
?>