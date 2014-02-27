<?
class ChannelAccountBasic extends WebPortalBase
{
	use WebPortalRefControl,WebPortalRefVerify;
	
	public $tableList,$tableItem;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->tableList,$this->tableItem);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doAuth()
	{
		$this->doAuthed();
	}
	
	public function uInit()
	{
		if($this->isuInit)return;$this->isuInit=true;
		$this->ua->dataLoader();
	}
	public function uInfo($k)
	{
		if(!$this->isuInit)$this->uInit();
		return $this->ua->getData($k);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		$this->theme->doCacheFilterLoop('item','cpo.tableItem');
		$this->theme->doCacheFilterTree('info','cpo.','uInfo');
	}
	
	
	/*
	########################################
	########################################
	*/
	/*
	public function toRelateQuery($query)
	{
		if($this->corpRelate){
			$query=DB::sqla($query,'corpid='.$this->corpid);
		}
		return $query;
	}
	*/
}
?>