<?
class VDCS_Request
{
	private $_data=array();
	
	
	public function __construct()
	{
		$this->_data['https']=false;
		$this->_data['url_head']='http://';		//protocol
		if($_SERVER['HTTPS']=='on'){
			$this->_data['https']=true;
			$this->_data['url_head']='https://';
		}
		$this->_data['script_file']=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
	}
	public function __destruct(){}
	
	
	/*
	########################################
	########################################
	*/
	public function isPost(){return $_SERVER['REQUEST_METHOD']!='POST'?false:true;}
	public function isForm(){return $_POST['_chk']=='yes'?true:false;}
	
	public function isFormPost($refer=false)
	{
		if($_POST['_chk']!='yes' || $_SERVER['REQUEST_METHOD']!='POST') return false;
		if($refer){
			$tmpurl=$this->_data['url_head'].$_SERVER['HTTP_HOST'];
			$tmpurls=substr($_SERVER['HTTP_REFERER'],0,strlen($tmpurl));
			return $tmpurl==$tmpurls?true:false;
		}
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function query($k){return $_GET[$k];}
	public static function post($k){return $_POST[r($k,'.','_')];}
	
	public function getRequest($k,$t=0,$cut=0,$tsql=0)
	{
		$re='';
		//if($k){
			switch($t){
				case 1: $re=trim(self::query($k)); break;		//Query		GET
				case 2: $re=trim(self::post($k)); break;		//Form		POST
				default: $re=trim($k); break;
			}
		//}
		if(!$re) return '';
		if($cut>0) $re=utilCode::toCut($re,$cut);
		if($tsql==1) $re=utilCode::toSQL($re);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getQueryString(){return $_SERVER['QUERY_STRING'];}
	public function getQuery($k,$c=0){return $c>0?utilCode::toCut(trim(self::query($k)),$c):trim(self::query($k));}
	public function getQueryc($k,$c=0){return $this->getQuery($k,$c);}
	public function getQueryID($k){$re=self::query($k); if(!is_numeric($re) || strpos($re,'.')>-1 || strpos($re,'-')>-1) $re=0; return $re;}
	public function getQueryInt($k){$re=self::query($k); if(!is_numeric($re) || strpos($re,'.')>-1) $re=0; return $re;}
	public function getQueryNum($k){$re=self::query($k); if(!is_numeric($re)) $re=0; return $re;}
	public function getQueryx($k,$c=0){$re=$this->getQuery($k,$c);return isx($re)?$re:'';}
	
	public function getQuerys($k,$type=0,$ret=null){return utilCode::toValues(self::toDataAry($_GET[$k]),$type,'string',$ret);}
	public function getQuerysID($k,$t=''){return $this->getQuerys($k,$t,5);}
	public function getQuerysInt($k,$t=''){return $this->getQuerys($k,$t,1);}
	public function getQuerysNum($k,$t=''){return $this->getQuerys($k,$t,2);}
	
	public function getPost($k,$c=0){return $c>0?utilCode::toCut(trim(self::post($k)),$c):trim(self::post($k));}
	public function getPostc($k,$c=0){return $this->getPost($k,$c);}
	public function getPostID($k){$re=self::post($k); if(!is_numeric($re) || strpos($re,'.')>-1 || strpos($re,'-')>-1) $re=0; return $re;}
	public function getPostInt($k){$re=self::post($k); if(!is_numeric($re) || strpos($re,'.')>-1) $re=0; return $re;}
	public function getPostNum($k){$re=self::post($k); if(!is_numeric($re)) $re=0; return $re;}
	public function getPostx($k,$c=0){$re=$this->getPost($k,$c);return isx($re)?$re:'';}
	public function getPostContent($k,$t,$c=0){return ($c)?utilCode::toCut(self::post($k),$c):self::post($k); }
	
	public function getPosts($k,$type=0,$smb=null){return utilCode::toValues(self::toDataAry($_POST[$k]),$type,'string',$smb);}
	public function getPostsID($k,$t=''){return $this->getPosts($k,$t,5);}
	public function getPostsInt($k,$t=''){return $this->getPosts($k,$t,1);}
	public function getPostsNum($k,$t=''){return $this->getPosts($k,$t,2);}
	
	public function getForm($k,$c=0){return $c>0?utilCode::toCut(trim(self::post($k)),$c):trim(self::post($k));}
	public function getFormc($k,$c=0){return $this->getForm($k,$c);}
	public function getFormID($k){$re=self::post($k); if(!is_numeric($re) || strpos($re,'.')>-1 || strpos($re,'-')>-1) $re=0; return $re;}
	public function getFormInt($k){$re=self::post($k); if(!is_numeric($re) || strpos($re,'.')>-1) $re=0; return $re;}
	public function getFormNum($k){$re=self::post($k); if(!is_numeric($re)) $re=0; return $re;}
	public function getFormx($k,$c=0){$re=$this->getForm($k,$c);return isx($re)?$re:'';}
	public function getFormContent($k,$t,$c=0){return ($c)?utilCode::toCut(self::post($k),$c):self::post($k); }
	
	public static function toDataAry($avalue,$smb=',')
	{
		$rea=array($avalue);
		if(is_array($avalue)){
			$rea=array();
			foreach($avalue as $value){
				if(!is_null($value)) array_push($rea,$value);
			}
		}
		$values=implode($smb,$rea);
		return toSplit($values,',');
	}
	
	/*
	########################################
	########################################
	*/
	public function getScriptFile(){return $this->_data['script_file'];}
	public function getScriptURL(){$querys=$this->getQueryString(); return $this->_data['script_file'].($querys?'?'.$querys:'');}
	public function getRequestMethod(){return $_SERVER['REQUEST_METHOD'];}
	public function getBrowseURI(){return $_SERVER['REQUEST_URI'];}
	public function getBrowseURL($script=false){return $this->_data['url_head'].$_SERVER['HTTP_HOST'].($script?$this->getScriptURL():'/');}
	public function getBrowseFile(){$tmpary=explode('?',$this->_data['script_file']); $tmpary=explode('/',$tmpary[0]); return $tmpary[count($tmpary)-1];}
	public function getBrowseDir(){$tmpary=explode('?',$this->_data['script_file']); $tmpary=explode('/',$tmpary[0]); for($i=0;$i<count($tmpary)-1;$i++){$restr.=$tmpary[$i].'/';}; return $restr;}
	//public function getBrowseDirFile(){$tmpstr=strstr($this->_data['script_file'],'/'); return substr($tmpstr,1);}
	public function getBrowsePath($script=false){return $script?$_SERVER['REQUEST_URI']:$this->_data['script_file'];}
	public function getBrowseURLPath(){return $this->_data['url_head'].$_SERVER['HTTP_HOST'].$this->_data['script_file'];}
	//public function getBrowseURLPaths(){return $this->_data['url_head'].$_SERVER['HTTP_HOST'].$this->getScriptURL();}
	public static function getBrowseDomain(){return $_SERVER['HTTP_HOST'];}
	public static function getBrowseIP(){return $_SERVER['REMOTE_ADDR']?$_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR'];}
	public static function getBrowseAgent(){return str_replace(array('\'','\"'),array(''),$_SERVER['HTTP_USER_AGENT']);}
	public static function getBrowseReferer(){return $_SERVER['HTTP_REFERER'];}
}
