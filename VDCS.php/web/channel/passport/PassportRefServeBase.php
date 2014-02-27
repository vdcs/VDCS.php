<?
trait PassportRefServeBase
{
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		//$this->treeData=&$ctl->treeData;
		//if(!$this->treeData) $this->treeData=newTree();
		
		$this->doLoadSet(false);
		$this->doLoadExtend();
	}
	protected function doLoadSet($ismm=false)
	{
		$names=$this->cfg->getChannelConfigure('account','var.'.$this->UURC.':names');
		if(!$names) $names=appv('var.'.$this->UURC.'');
		if(!$names) $names=appv('var.account');
		$this->addVar('ua.names',$names);
		$this->addVar('ua.rc',$this->UURC);
		$this->addVar('ua.key',$this->UURC);
	}
	protected function doLoadExtend(){}
	
}
?>