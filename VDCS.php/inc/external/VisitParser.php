<?
class VisitParser
{
	protected $_value=array();
	protected $isParseIP=true;
	protected $isParseAgent=true;
	protected $ipParser=null;
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unsetr($this->ipParser,$this->iplocation);
	}
	
	
	public function sets($ip,$agent)
	{
		if($ip!=$this->ip)$this->isParseIP=false;
		if($agent!=$this->agent)$this->isParseAgent=false;
		$this->ip=$ip;
		$this->agent=$agent;
	}
	public function getValue($k){return $this->_value[$k];}
	public function getValues(){return $this->_value;}
	
	
	public function doParse()
	{
		if($this->ip) $this->parseIP();
		if($this->agent) $this->parseAgent();
		//debugx(($this->agent);
		//debugAry($this->_value);
	}
	
	public function parseIP()
	{
		if($this->isParseIP)return;$this->isParseIP=true;
		if(!$this->ipParser){
			$this->ipParser=new VisitIPParser();
			$this->ipParser->doInit();
		}
		$infos=$this->ipParser->getInfo($this->ip);
		foreach($infos as $k=>$v){$this->_value[$k]=$v;}
		/*
		if(!$this->iplocation){
			$this->iplocation=new VisitIPLocation();
			$this->ipseparator=$this->iplocation->separate(1000);
		}
		$locations=$this->iplocation->getlocation($this->ip,$this->ipseparator);
		$this->_value['_country']='';
		$this->_value['_country.name']='';
		$this->_value['_area']='';
		$this->_value['_area.name']=utilCoder::toUTF8($locations['country']);
		$this->_value['_city']='';
		$this->_value['_city.name']=utilCoder::toUTF8($locations['area']);
		$this->_value['_isp']='';
		$this->_value['_isp.name']='';
		unsetr($locations);
		*/
	}
	
	public function parseAgent()
	{
		if($this->isParseAgent)return;$this->isParseAgent=true;
		$infos=VisitBrowserParser::getAgentBrowser($this->agent);
		foreach($infos as $k=>$v){$this->_value[$k]=$v;}
		$infos=VisitBrowserParser::getAgentOS($this->agent);
		foreach($infos as $k=>$v){$this->_value[$k]=$v;}
	}
	
}
?>