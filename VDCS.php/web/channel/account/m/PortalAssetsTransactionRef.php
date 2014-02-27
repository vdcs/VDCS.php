<?php
trait PortalAssetsTransactionRef
{
	protected function refLoad()
	{	
		$this->tablemodule=VDCSDTML::getConfigTable('common.channel/account/data.transaction.module');
		//debugTable($this->tablemodule);
		$this->__module=querys('module');
		$this->addDTML('x.params','module='.$this->__module);
		$this->mpivotal=new UcPivotalManage();
		$this->mpivotal->setURC($this->UURC);
		$this->mpivotal->init();
	}
	public function refThemeCache()
	{
		$this->theme->doCacheFilterLoop('module','cpo.tablemodule');
	}
}
?>