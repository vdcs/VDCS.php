<?
class utilSettings
{
	public static function loadINI($file)
	{
		$sIni=new utilSettingsINI;
		$sIni->load($file);
		return $sIni;
	}
}

class utilSettingsBase
{
	var $_datas=array();
	
	function get($var)
	{
		$var=explode('.', $var);
		
		$result=$this->_datas;
		foreach($var as $key){
			if(!isset($result[$key])) { return false; }
			$result=$result[$key];
		}
		return $result;
		
		//v_error('Not yet implemented', E_USER_ERROR);
	}
	
	function load()
	{
		//v_error('Not yet implemented', E_USER_ERROR);
	}
}

Class utilSettingsINI Extends utilSettingsBase
{
	function get($key)
	{
		$re='';
		$place=@strpos($key,'.');
		if($place===false){
			$re=$this->_datas[$key];
		}
		else{
			$re=$this->_datas[substr($key,0,$place)][substr($key,$place+1)];
		}
		return $re;
	}
	
	function getSection($sec)
	{
		return $this->_datas[$sec];
	}
	
	function load($file)
	{
	         if(is_file($file)==false) return false;
	         $this->_datas=parse_ini_file($file,true);
	}
}

Class utilSettingsXML Extends utilSettingsBase
{
	function load($file)
	{
		if(is_file($file)==false) return false;
		/*
		xmllib.php为PHP XML Library, version 1.2b,相关连接:http://keithdevens.com/software/phpxml
		xmllib.php主要特点是把一个数组转换成一个xml或吧xml转换成一个数组
		XML_unserialize:把一个xml给转换 成一个数组
		XML_serialize:把一个数组转换成一个xml
		自PHP5起,simpleXML就很不错,但还是不支持将xml转换成数组的功能,所以xmlLIB还是很不错的. 
		*/
		include('xmllib.php');
		$xml=file_get_contents($file);
		$data=XML_unserialize($xml);
		$this->_datas=$data['settings'];
	}
}

Class utilSettingsYAML Extends utilSettingsBase
{
	function load($file)
	{
		if(is_file($file)==false) return false;
		include('spyc.php');
		$this->_datas=Spyc::YAMLLoad($file);
	}
}

Class utilSettingsPHP Extends utilSettingsBase
{
	function load($file)
	{
		if(is_file($file)==false) return false;
		
		include($file);				// Include file
		unset($file);				//销毁指定变量
		$vars=get_defined_vars();		//返回所有已定义变量的列表,数组,变量包括服务器等相关变量,
		foreach($vars as $key=>$val){		//通过foreach吧$file引入的变量给添加到$_datas这个成员数组中去.
			if($key=='this') continue;
			$this->_datas[$key]=$val;
		}
	}
}
?>