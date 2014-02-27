<?
class utilINI
{
	protected $_var=array();
	protected $_data=array();
	
	public function __construct()
	{
		$this->_var['update']=false;
	}
	public function __destruct()
	{
		$this->doUpdate();
		unsetr($this->_var,$this->_data);
	}
	
	public function setFilePath($s){$this->_var['filepath']=$s;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->readFile();
	}
	
	public function doUpdate()
	{
		if(!$this->_var['update'])return;
		$this->writeFile();
		$this->_var['update']=false;
	}
	
	
	public function getArray($section='')
	{
		$re=$section?$this->_data[$section]:$this->_data;
		if(!isa($re)) $re=array();
		return $re;
	}
	public function setArray($datas,$section='')
	{
		if($section){
			$this->_data[$section]=$datas;
		}
		else{
			$this->_data=$datas;
		}
	}
	public function getTree($section='')
	{
		$reTree=newTree();
		$reTree->setArray($this->getArray($section));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getKey($section,$k)
	{
		return $this->_data[$section][$k];
	}
	
	public function setKey($section,$k,$v)
	{
		$this->_data[$section][$k]=$v;
		$this->_var['update']=true;
	}
	
	public function delKey($section,$k)
	{
		unset($this->_data[$section][$k]);
		$this->_var['update']=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function readFile($filepath='')
	{
		if($this->_data) return;
		$filepath=$filepath?$filepath:$this->_var['filepath'];
		if($filepath){
			$this->_data=@parse_ini_file($filepath,true);
		}
		return $this->_data;
	}
	public function writeFile($filepath='',$datas=null)
	{
		if(!$datas) $datas=$this->_data;
		if(!$datas) return;
		$filepath=$filepath?$filepath:$this->_var['filepath'];
		if(!$filepath){
			utilFile::doFileCreate($filepath);
		}
		if($filepath){
			self::write_ini_file($this->_data,$filepath);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function write_ini_file($datas,$filepath='')
	{   
		$ok='';$re='';
		if(isa($datas)){
			foreach($datas as $k=>$v){
				if(is_array($v)){
					if($k!=$ok){
						$re.=NEWLINE.'['.$k.']'.NEWLINE;
						$ok=$k;
					}
					$re.=self::write_ini_file($v,"");
				}
				else{
					if(trim($v)!=$v || strstr($v,'[') || strstr($v,'&') || strstr($v,'=') || strstr($v,' ')) $v='"'.$v.'"';
					$re.=$k.' = '.$v.NEWLINE;
				}
			}
		}
		if(ise($filepath)) return $re;
		
		$fp=fopen($filepath,'w');
		fwrite($fp,$re);
		fclose($fp);
	}
}
?>