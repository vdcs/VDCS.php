<?
class WebPageFaq
{
	public $tableDats=null,$tableDat=null;
	protected $_var=array();
	
	public function __construct()
	{
		//parent::__construct();
		$this->tableDats=newTable();
		$this->tableDat=newTable();
		$this->_var['FileTemplate']='common.channel/{$channel}/faq.{$page}';
	}
	public function __destruct()
	{
		//parent::__destruct();
		unsetr($this->_var,$this->tableDats,$this->tableDat);
	}
	
	
	public function setFileTemplate($s) { $this->_var['FileTemplate']=$s; }
	
	public function setChannel($s) { $this->_var['channel']=trim($s); }
	public function getChannel() { return $this->_var['channel']; }
	
	public function setPage($s) { $this->_var['page']=trim($s); }
	public function getPage() { return $this->_var['page']; }
	
	public function setKey($s) { $this->_var['key']=trim($s); }
	public function getKey() { return $this->_var['key']; }
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		if($this->_isLoad) return; $this->_isLoad=true;
		if(!$this->_isDats){
			$file=$this->_var['FileTemplate'];
			$file=rd($file,'channel',$this->_var['channel']);
			$file=rd($file,'page',$this->_var['page']);
			$this->tableDats=VDCSDTML::getConfigTable($file);
			unset($file);
			$this->_isDats=true;
		}
	}
	
	public function doParse()
	{
		$this->doLoad();
		$this->tableDat->setFields($this->tableDats->getFields());
		$this->tableDats->doBegin();
		while($this->tableDats->isNext()){
			if($this->tableDats->getItemValue('key')==$this->_var['key']){
				$this->tableDat->addItem($this->tableDats->getItemTree());
				$this->_isDat=true;
			}
		}
		if(!$this->_isDat){
			$this->tableDat->setArray($this->tableDats->getArray());
		}
		//debugTable($this->tableDats);
		//debugTable($this->tableDat);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCache($re,$oprefix='')
	{
		if(!$oprefix) $oprefix='faq';
		$_oprefix=$oprefix;
		$re=CommonTheme::toCacheFilterLoop($re,'faqs',$_oprefix.'.tableDats');
		$re=CommonTheme::toCacheFilterLoop($re,'faq',$_oprefix.'.tableDat');
		return $re;
	}
}
?>