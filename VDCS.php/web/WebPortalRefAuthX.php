<?
trait WebPortalRefAuthX
{
	
	/*
	########################################
	########################################
	*/
	public function doAuth()
	{
		$this->doAuthx();
	}
	public function doAuthx()
	{
		$this->doAuther();
		$this->isauth=$this->ua->isLogin();
		//if(!$this->isauth) $this->canparse=false;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function parserCan()
	{
		if(!$this->isauth){
			$this->setStatus('nologin');
			$this->setMessage('未登录');
		}
		return $this->isauth;
	}
	
}
