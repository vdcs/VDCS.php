<?
class VisitIPParser
{
	public $db=null;
	
	public function __construct()
	{
		
	}
	public function __destruct()
	{
		unsetr($this->db);
	}
	
	public function dbinfo()
	{
		global $_cfg;
		$_cfg['sys.dbc']['type']			= 'mysql';
		$_cfg['sys.dbc']['port']			= '3306';
		$_cfg['sys.dbc']['perdure']			= '1';
		$_cfg['sys.dbc']['server']			= 'localhost';
		$_cfg['sys.dbc']['database']			= 'dbc_data';
		$_cfg['sys.dbc']['username']			= 'dbc_data';
		$_cfg['sys.dbc']['password']			= 'dbc_datajk';
		$_cfg['sys.dbc']['charset']			= 'utf8';
		$_cfg['sys.dbc']['charset.result']		= 'utf8';
		$_cfg['sys.dbc']['tableprefix']			= 'db_';
		$_cfg['sys.dbc']['debug']			= '';
		return $_cfg['sys.dbc'];
	}
	
	
	public function doInit()
	{
		if(!$this->db){
			//debugAry($this->dbinfo());
			$this->db=VDCSDB::getInstance($this->dbinfo());
		}
		
	}
	
	public function getInfo($ip)
	{
		$ipen=$this->toEncodeIP($ip);
		//debugx(($ipen);
		$sql='select * from ipv4 where '.$ipen.' between ipstart and ipend limit 0,1';
		//$sql='select * from ipv4 where ipstart<='.$ipen.' and ipend>='.$ipen.' limit 0,1';
		//debugx(($sql);
		$treeDat=$this->db->getQueryTree($sql);
		$re=array();
		$re['country']=$treeDat->getItem('countryid');
		$re['country.name']=$treeDat->getItem('country');
		$re['area']=$treeDat->getItem('regionid');
		$re['area.name']=$treeDat->getItem('region');
		$address=$treeDat->getItem('location').' '.$treeDat->getItem('address');
		$re['city']='';
		$re['city.name']=trim($address);
		$re['isp']='';
		$re['isp.name']='';
		unsetr($treeDat);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toEncodeIP($ip)		// 把点格式的ip地址转换成整数表示的ip地址
	{
		$arr=explode('.', $ip);
		if(count($arr)!=4) return 0;
		$re=0;
		foreach($arr as $k=>$v){
			$re+=(int)$v*pow(256,3-$k);
		}
		return $re;
	}
	
	public function toPackIP($ip)
	{
		return pack('N', intval($ip));
	}
}
?>