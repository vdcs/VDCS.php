<?php
class ChannelAccountAssetsRemitE extends ChannelAccountBase{
	use WebServeRefEle;
	

	public function parseApply()
	{
		$this->parseForm();
	}
	public function parseForm()
	{
		$this->theme->setAction('form');	
		$this->tableList=$this->cfg->getTable('data.remit.bank');
	}
	public function doThemeCache()
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
	}	
}
?>