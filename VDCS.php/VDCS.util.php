<?
function isTree($o){return $o instanceof utilTreeBase?true:false;}	//function isTree($o){return is_object($o)?true:false;}
function isTable($o){return $o instanceof utilTableBase?true:false;}
function isXCML($o){return $o instanceof XCML?true:false;}
function isMap($o){return $o instanceof utilMapBase?true:false;}

function newTree(){return new utilTree();}
function newTable(){return new utilTable();}
function newXCML(){return new utilXCML();}
function newMap(){return new utilMap();}

function removeBOM($s)		//移除头部BOM信息
{
	$bom=chr(0xEF).chr(0xBB).chr(0xBF);
	if(strlen($s)>3 && substr($s,0,3)==$bom) $s=substr($s,3);
	return $s;
}
function toFilePath($f){return iconv('UTF-8','GBK',$f);}
function toFilePathR($f){return iconv('GBK','UTF-8',$f);}
function isExists($f){return @file_exists(toFilePath($f));}
function isDir($f){return @is_dir(toFilePath($f));}
function isFile($f){return @is_file(toFilePath($f));}
function getFile($f){return getFileContent($f);}function setFile($f,$s){return doFileCreate($f,$s);}
function getFileContent($f){return removeBOM(@file_get_contents(toFilePath($f)));}
function doFileWrite($f,$s,$flag=null){return @file_put_contents(toFilePath($f),$s,$flag);}function doFileCreate($f,$s){return doFileWrite($f,$s);}
function doFileCopy($f,$f2){return @copy(toFilePath($f),toFilePath($f2));}
function doFileDel($f){$f=toFilePath($f);return @is_file($f)?@unlink($f):false;}
function getPathPart($path,$t){
	$paths=pathinfo($path);		//dirname,basename,extension,filename
	$re='';
	switch($t){
		case 'name':		$re=$paths['filename'];break;
		case 'ext':		$re=$paths['extension'];break;
		case 'exts':		$re='.'.$paths['extension'];break;
		case 'names':		$re=$paths['basename'];break;
		case 'dir':		$re=$paths['dirname'];break;
		default:
			$re=array();
			$re['dir']=$paths['dirname'];
			$re['name']=$paths['filename'];
			$re['ext']=$paths['extension'];
			$re['exts']='.'.$paths['extension'];
			break;
	}
	return $re;
}
function clearFileCaches(){clearstatcache();}

//function getString2Tree($s,$p1=';',$p2='='){return utilString::toTree($s,$p1,$p2);}
function getFile2Tree($f,$k='',$v=''){return getXCML2Tree(getFileContent($f),$k,$v);}
function getFile2Table($f){return getXCML2Table(getFileContent($f));}
function getFile2Map($f){return getXCML2Map(getFileContent($f));}

function getXCML2Tree($re,$k='',$v='')
{
	$xcml=new utilXCML();
	//$xcml->loadFile($re);
	$xcml->loadXML($re);
	//debugx($xcml->getErrorType());
	$xcml->doParse();
	return utilXCMLExtend::toTree($xcml,$k,$v);
}

function getXCML2Table($re)
{
	$xcml=new utilXCML();
	$xcml->loadXML($re);
	//debugx($xcml->getErrorType());
	$xcml->doParse();
	return utilXCMLExtend::toTable($xcml);
}

function getXCML2Map($re)
{
	$xcml=new utilXCML();
	$xcml->loadXML($re);
	//debugx($xcml->getErrorType());
	$xcml->doParse();
	return utilXCMLExtend::toMap($xcml);
}


/*
################################################
################################################
################################################
################################################
*/
class utilFile
{
	const ExtsDenied		= 'inc,asp,asa,aspx,ascx,shtml,shtm,php,php2,php3,php4,php5,phtml,pl,cgi,jsp,cfm,cfc,bat,exe,com,dll,vbs,reg,htaccess,cer,asis';
	const ExtsAllowed		= 'gif,jpg,jpeg,png,bmp,doc,xls,ppt,pdf,txt,xcml,dtml,zip,rar,mp3,swf,flv';
	const ExtsPic			= 'gif,jpg,jpeg,png,bmp';
	
	public static function isExt($exts,$ext){return inp($exts?$exts:self::ExtsAllowed,$ext)>0?true:false;}
	public static function isExtDenied($ext,$exts=''){return inp($exts?$exts:self::ExtsDenied,$ext)>0?true:false;}
	public static function isExtAllowed($ext,$exts=''){return inp($exts?$exts:self::ExtsAllowed,$ext)>0?true:false;}
	public static function isExtPic($ext,$exts=''){return inp($exts?$exts:self::ExtsPic,$ext)>0?true:false;}
	
	
	//public static $ConfigureHeader='<'.'?exit(\'VDCS\');?'.'>';
	
	
	public static function toPath($s){return toFilePath($s);}
	public static function toPathR($s){return toFilePathR($s);}
	public static function toFilter($s,$secure=false)
	{
		$afind=array('*','?','"','<','>','|');
		$areplace=array('','','','','');
		if($secure){
			$afind=array_merge($afind,array('\\','/',':'));
			$areplace=array_merge($areplace,array('','',''));
		}
		return str_replace($afind,$areplace,$s);
	}
	
	public static function toBOMRemove($s){return self::removeBOM($s);}
	public static function removeBOM($s)		//移除头部BOM信息
	{
		$bom=chr(0xEF).chr(0xBB).chr(0xBF);
		if(strlen($s)>3 && substr($s,0,3)==$bom) $s=substr($s,3);
		return $s;
	}
	
	public static function getSize($f){return @filesize(self::toPath($f));}
	public static function getTime($f){return @filemtime(self::toPath($f));}
	
	public static function getContent($f){return self::toBOMRemove(@file_get_contents(self::toPath($f)));}
	public static function getConfig($f){return self::getContent($f);}
	public static function getTemplate($f){return self::getContent($f);}
	public static function getConfigure($f){return toSubstr(self::getContent($f),20);}
	
	public static function isExist($f){return @file_exists(self::toPath($f));}
	public static function isFiles($f){return self::isFile($f);}
	public static function isFile($f){return @is_file(self::toPath($f));}
	
	public static function doFileCreate($f,$content){return self::doFileWrite($f,$content);}
	public static function doFileWrite($f,$content,$t='write')
	{
		switch($t){
			case 'w':
			case 'write':	$mode='w';break;
			case 'a':
			case 'append':	$mode='a';break;
			default:	$mode='r';break;
		}
		$f=self::toPath($f);
		if(!$fp=fopen($f,$mode)) return false;
		flock($fp,3);
		fwrite($fp,$content);
		fclose($fp);
		return true;
	}
	
	public static function doFileCopy($f,$f2){return copy(self::toPath($f),self::toPath($f2));}
	public static function doFileMove($f,$f2,$real=true)
	{
		//return rename(self::toPath($f),self::toPath($f2));
		$re=self::doFileCopy($f,$f2);
		if($re && $real) self::doFileDel($f);
		return $re;
	}
	
	public static function doFileDel($f)
	{
		$f=self::toPath($f);
		//if(substr($f,0,1)=='/') $f='..'.$f;
		return @is_file($f)?@unlink($f):false;
	}
	public static function doFileDelete($f){self::doFileDel($f);}
	
	
	/*
	########################################
	########################################
	*/
	public static function toDir($s)
	{
		if(right($s,1)!='/') $s.='/';
		return toFilePath($f);
	}
	
	public static function isDir($f){return @is_dir(self::toPath($f));}
	
	public static function doDirCreate($dir,$mode=0777)
	{
		$dir=self::toPath($dir);
		if(!is_dir($dir)) return @mkdir($dir,$mode);
		return false;
	}
	
	public static function doDirCreates($dirs,$mode=0777)
	{
		foreach($dirs as $dir){
			$dir=self::toPath($dir);
			if(!is_dir($dir))  mkdir($dir,$mode);
		}
		return false;
	}
	
	public static function doDirCreated($dir,$mode=0777)
	{
		//debugx($dir);
		//$dir=toDirPath($dir);
		if(is_dir($dir)) return;
		$arr=split(PATH_SEPARATORS,$dir);
		$_path=$arr[0];
		for($a=1;$a<count($arr)-1;$a++){
			$_path.=PATH_SEPARATORS.$arr[$a];
			self::doDirCreate($_path,$mode);
			//debugx($_path);
		}
	}
	public static function doDirMove($dir,$dir2)
	{
		if(!isDir($dir) || !isDir($dir2)) return false;
		return rename(self::toPath($dir),self::toPath($dir2));
	}
	
	public static function doDirDel($dir,$ext='*',$self=true)
	{
		if(!isDir($dir)) return false;
		if(right($dir,1)!=PATH_SEPARATORS && right($dir,1)!=PATH_SEPARATORR) $dir.=PATH_SEPARATORS;
		$ext=$ext?$ext:'*';
		$_pattern=$dir.'*';
		if($ext!='*') $_pattern=$dir.'*.'.$ext;
		foreach(glob(self::toPath($_pattern)) as $path){
			$path=self::toPathR($path);
			self::doFileDel($path);
		}
		//if($ext=='*') debugs('del:'.$dir);
		if($self && $ext=='*') rmdir(self::toPath($dir));
		return true;
	}
	public static function doDirDelFile($dir,$ext='*'){return self::doDirDel($dir,$ext,false);}
	
	
	/*
	########################################
	########################################
	*/
	public static function getDirAry($pdir)
	{
		$re=array();
		$odir=dir(self::toPath($pdir));
		while(false!==($name=$odir->read())) {
			if($name=='.' || $name=='..') continue;
			$fpath=$odir->path.$name.'/';
			if(is_dir(self::toPath($fpath))){
				$ard=array();
				$ard['name']=$name;
				$ard['type']='folder';
				$ard['path']=$fpath;
				array_push($re,$ard);
			}
		}
		$odir->close();
		return $re;
	}
	
	public static function getDirTable($dir,$ext='*')
	{
		$reTable=new utilTable();
		$reTable->setFields('type,names,name,ext,size,path');
		if(is_dir(self::toPath($dir))){
			if(right($dir,1)!=PATH_SEPARATORS && right($dir,1)!=PATH_SEPARATORR) $dir.=PATH_SEPARATORS;
			$ext=$ext?$ext:'*';
			$_pattern=$dir.'*';
			if($ext!='*') $_pattern=$dir.'*.'.$ext;
			$treeItem=new utilTree();
			foreach(glob(self::toPath($_pattern)) as $path){
				$path=self::toPathR($path);
				$treeItem->addItem('path',$path);
				$kpath=$path;
				$kpath=self::toPath($path);
				$isitem=false;
				//debugs($path.' = '.$kpath);
				$ainfo=pathinfo($path);		//dirname,basename,extension,filename
				//debuga($ainfo);
				if($ainfo['extension']){
					$treeItem->addItem('type','file');
					$treeItem->addItem('names',$ainfo['basename']);
					$treeItem->addItem('name',$ainfo['filename']);
					$treeItem->addItem('ext',$ainfo['extension']);
					$treeItem->addItem('size',@filesize($kpath));
					$isitem=true;
				}
				elseif(is_dir($kpath)){
					$name=getPathPart($path,'name');
					$treeItem->addItem('type','folder');
					$treeItem->addItem('names',$ainfo['basename']);
					$treeItem->addItem('name',$ainfo['filename']);
					$treeItem->addItem('ext','');
					$treeItem->addItem('size','-1');
					$isitem=true;
				}
				if($isitem) $reTable->addItem($treeItem);
			}
			unset($treeItem);
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function getFilePart($file,$t=''){return self::getPathPart($file,$t);}
	public static function getPathPart($file,$t='')
	{
		$re='';$dir_='';$file_=$file;
		if(ins($file,PATH_SEPARATORS)>0){
			$file_=substr(strrchr($file,PATH_SEPARATORS),1);
			$dir_=substr($file,0,strlen($file)-strlen($file_));
		}
		if(ins($file,PATH_SEPARATORR)>0){
			$file_=substr(strrchr($file,PATH_SEPARATORS),1);
			$dir_=substr($file,0,strlen($file)-strlen($file_));
		}
		switch($t){
			case 'name':		$re=substr($file_,0,strrpos($file_,'.'));break;
			case 'ext':		$re=strtolower(substr(strrchr($file_,'.'),1));break;
			case 'exts':		$re=strtolower(strrchr($file_,'.'));break;
			case 'names':		$re=substr($file_,0,strrpos($file_,'.')).strtolower(strrchr($file_,'.'));break;
			case 'dir':		$re=$dir_;break;
			default:
				$re=array();
				$re['dir']=$dir_;
				$re['name']=substr($file_,0,strrpos($file_,'.'));
				$re['ext']=substr($re['exts'],1);
				$re['exts']=strtolower(strrchr($file_,'.'));
				break;
		}
		return $re;
	}
	
}



/*
################################################
################################################
################################################
################################################
*/
class utilString
{
	
	public static function toTree($s,$p1=';',$p2='=',$value=false)
	{
		$reTree=new utilTree();
		$reTree->setArray(self::toArray($s,$p1,$p2,$value));
		return $reTree;
	}
	
	public static function toFilterBlank($s)
	{
		return str_replace(array("\t","\n","\r"),array('','',''),$s);	//制表符,换行,回车
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toAry($s,$smb=','){return explode($smb,$s);}
	public static function toStr($ary,$smb=','){return implode($smb,$ary);}
	
	public static function toArray($s,$p1=';',$p2='=',$value=false)
	{
		if(!$s) return array();
		$re=array();
		$ars=explode($p1,$s);
		$len2=strlen($p2);
		for($i=0;$i<count($ars);$i++){
			$p=strpos($ars[$i],$p2);
			if($p===false){
				$ar[0]=$ars[$i];
				$ar[1]='';
			}
			else{
				$ar[0]=substr($ars[$i],0,$p);
				$ar[1]=substr($ars[$i],$p+$len2);
			}
			if($value && strlen($ar[1])<1) $ar[1]=$ar[0];
			$re[$ar[0]]=$ar[1];
		}
		return $re;
	}
	
	public static function toStringByArray($ary,$p1=';',$p2='=')
	{
		if(!is_array($ary)) return '';
		foreach($ary as $k=>$v){
			if(strlen($k) && strlen($v)){$re.=$p1.$k.$p2.$v;}
		}
		if($re) $re=toSubstr($re,strlen($strp1)+1);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function swap(&$s1,&$s2)					//两值互换
	{
		$_s=$s1;$s1=$s2;$s2=$_s;unset($_s);
	}
	
	public static function lists($str,&$k,&$v,$sp='=')			//解析数组
	{
		if(!$sp) $sp='=';
		list($k,$v)=explode($sp,$str,2);
	}
	public static function lists3($str,&$k,&$v,&$v2,$sp='=')		//解析数组
	{
		if(!$sp) $sp='=';
		list($k,$v,$v2)=explode($sp,$str,3);
	}
	
}


/*
################################################
################################################
################################################
################################################
*/
class utilArray
{
	
	public static function toString($ary,$p1=';',$p2='=')
	{
		$itema=array();
		foreach($ary as $k=>$v){
			array_push($itema,$k.$p2.$v);
		}
		return implode($p1,$itema);
	}
	public static function toTree($ary)
	{
		$reTree=new utilTree();
		$reTree->setArray($ary);
		return $reTree;
	}
	
	
	public static function toParams($params)
	{
		if(isTree($params)) $params=$params->getArray();
		if(!isa($params)) $params=array();
		return $params;
	}
	public static function toParamsTree($params)
	{
		if(isa($params)){
			$treep=newTree();
			$treep->setArray($params);
			$params=$treep;
		}
		if(!isTree($params)) $params=newTree();
		return $params;
	}
	
	public static function toAppend($ar,$ara)
	{
		//return array_merge($ar,$arAppend);
		foreach($ara as $k=>$v){$ar[$k]=$v;}
		return $ar;
	}
	
	public static function toValue($ar,$keys='{$key}',$values='{$value}')
	{
		if(!$keys) $keys='{$key}';
		if(!$values) $values='{$value}';
		$reAry=array();
		reset($ar);
		for($i=0;$i<count($ar);$i++){
			$_key=rd($keys,'key',key($ar));
			$_value=rd($values,'value',current($ar));
			$reAry[$_key]=$_value;
			next($ar);
		}
		return $reAry;
	}
	
	public static function doRemove(&$arr,$trim=true)
	{
		foreach($arr as $key=>$value){
			if(is_array($value)){
				self::doRemove($arr[$key],$trim);
			}
			else{
				$value=trim($value);
				if($value==''){
					unset($arr[$key]);
				}
				elseif($trim){
					$arr[$key]=$value;
				}
			}
		}
	}
	
	/**************************************************************
	 *
	 *	使用特定function对数组中所有元素做处理
	 *	@param	string	&$array		要处理的字符串
	 *	@param	string	$function	要执行的函数
	 *	@return boolean	$apply_to_keys_also		是否也应用到key上
	 *	@access public
	 *
	 *************************************************************/
	public static function toRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter=0;
		if(++$recursive_counter>1000){
			die('possible deep recursion attack');
		}
		foreach($array as $key=>$value){
			if(is_array($value)){
				self::toRecursive($array[$key],$function,$apply_to_keys_also);
			}else{
				$array[$key]=$function($value);
			}
			if($apply_to_keys_also && is_string($key)){
				$new_key=$function($key);
				if($new_key != $key){
					$array[$new_key]=$array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
}



/*
################################################
################################################
################################################
################################################
*/
class utilTreeBase{}
class utilTree extends utilTreeBase
{
	private $_data=array();
	private $_n,$_isIgnoreCase,$_Separatr,$_Separatrs;
	
	
	public function __construct()
	{
		$this->_n=0;
		$this->_isIgnoreCase=false;
		$this->_Separatr='=';
		$this->_Separatrs=';';
	}
	public function __destruct(){unset($this->_data);}
	
	public function doDestroy(){}
	
	public function __invoke($k){return $this->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function setSeparatr($s,$ss){if($s) $this->_Separatr=$s; if($ss) $this->_Separatrs=$ss;}
	
	public function setIgnoreCase($b){$this->_isIgnoreCase=$b;}
	public function isIgnoreCase(){return $this->_isIgnoreCase;}
	
	
	/*
	########################################
	########################################
	*/
	public function isObj(){return count($this->_data)?true:false;}public function isData(){return $this->isObj();}public function isTree(){return $this->isObj();}
	
	public function getCount(){return count($this->_data);}public function getLength(){return $this->getCount();}
	public function getI(){return $this->_i;}
	
	
	/*
	########################################
	########################################
	*/
	public function add($k,$v=''){return $this->addItem($k,$v);}public function setv($k,$v=''){return $this->setItem($k,$v);}
	public function addItem($k,$v=''){if(strlen($k)<1) return false;$this->_data[$k]=$v;return true;}
	public function setItem($k,$v=''){if(strlen($k)<1) return false;if(array_key_exists($k,$this->_data)){$this->_data[$k]=$v;}return true;}
	
	public function v($k){return $this->getItem($k);}public function vi($k){return $this->getItemInt($k);}public function vn($k){return $this->getItemNum($k);}
	public function getItem($k){return $this->_data[$k];}
	public function getItemInt($k){return intval($this->_data[$k]);}
	public function getItemNum($k){return floatval($this->_data[$k]);}
	
	public function delItem($k){unset($this->_data[$k]);return true;}
	public function isItem($k){return strlen($k)>0?array_key_exists($k,$this->_data):false;}
	
	public function getItemv($k,$v)
	{
		$re=$this->_data[$k];
		if(strlen($re)<1) $re=$v;
		return $re;
	}

	public function delItems($ks){
		$tmpFieldAry=explode(',', $ks);
		foreach ($tmpFieldAry as $tmpField) {
			unset($this->_data[$tmpField]);
		}
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doBegin(){reset($this->_data);$this->_n=1;}
	
	public function doMove($n=1)
	{
		if($n<0){
			for($i=0;$i>$n;$i--){
				prev($this->_data);
				$this->_n--;
			}
		}
		else{
			for($i=0;$i<$n;$i++){
				next($this->_data);
				$this->_n++;
			}
		}
	}
	
	public function getItemKey(){return key($this->_data);}
	public function getItemValue(){return current($this->_data);}
	
	public function setItemKey($k){return key($this->_data);}
	public function setItemValue($v){$this->_data[key($this->_data)]=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function setArray($ar,$px='')
	{
		if(is_array($ar)){
			$this->_data=array();
			reset($ar);
			for($i=0;$i<count($ar);$i++){
				$this->_data[$px.key($ar)]=current($ar);
				next($ar);
			}
		}
	}
	public function getArray(){return $this->_data;}
	
	public function getItems()
	{
		$re='';
		reset($this->_data);
		for($i=0;$i<count($this->_data);$i++){
			$_key=key($this->_data);
			$_value=current($this->_data);
			$re.=$this->_Separatrs.$_key.$this->_Separatr.$_value;
			next($this->_data);
		}
		if($re) $re=toSubstr($re,strlen($this->_Separatrs)+1);
		return $re;
	}
	
	public function setItems($strItems)
	{
		$this->setArray(utilString::toArray($re,$this->_Separatrs,$this->_Separatr));
	}
	
	public function getFields(){return utilString::toStr(array_keys($this->_data));}
	public function getFieldArray(){return array_keys($this->_data);}
	
	public function getValues(){return utilString::toStr(array_values($this->_data));}
	public function getValueArray(){return array_values($this->_data);}
	
	public function setString($s,$p1=';',$p2='='){$this->setArray(utilString::toArray($s,$p1,$p2));}
	public function getString($p1=';',$p2='=')
	{
		reset($this->_data);
		for($i=0;$i<count($this->_data);$i++){
			$_key=key($this->_data);
			$_value=current($this->_data);
			$re.=$p1.$_key.$p2.$_value;
			next($this->_data);
		}
		if($re) $re=toSubstr($re,strlen($this->p1)+1);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getFilterFields($prefix)
	{
		$_fields='';
		$tmpLen=strlen($prefix);
		$tmpFieldAry = $this->getFieldArray();
		foreach ($tmpFieldAry as $tmpField) {
			if(toSubstr($tmpField,1,$tmpLen)==$prefix){ $_fields.=','.$tmpField; }
		}
		if($_fields) $_fields=toSubstr($_fields,2);
		return $_fields;
	}
	public function getFilterArray($prefix,$pxr='')
	{
		$reAry=array();
		$tmpLen=strlen($prefix);
		reset($this->_data);
		for($i=0;$i<count($this->_data);$i++){
			$tmpKey=key($this->_data);
			if(toSubstr($tmpKey,1,$tmpLen)==$prefix){$reAry[$pxr.toSubstr($tmpKey,$tmpLen+1)]=current($this->_data);}
			next($this->_data);
		}
		return $reAry;
	}
	public function getFilterTree($prefix,$pxr=''){$reTree=new utilTree(); $reTree->setArray($this->getFilterArray($prefix,$pxr)); return $reTree;}
	public function getFilter($prefix,$pxr=''){return $this->getFilterTree($prefix,$pxr);}
	
	public function doFilter($prefix,$pxr='')
	{
		$ar=array();
		$tmpLen=strlen($prefix);
		reset($this->_data);
		for($i=0;$i<count($this->_data);$i++){
			$tmpKey=key($this->_data);
			$tmpKey=(toSubstr($tmpKey,1,$tmpLen)==$prefix)? $pxr.toSubstr($tmpKey,$tmpLen+1) : $tmpKey;
			$ar[$tmpKey]=current($this->_data);
			next($this->_data);
		}
		$this->_data=$ar;unset($ar);
	}
	
	
	public function doAppend($o,$prefix='')
	{
		$ar=is_object($o)?$o->getArray():$o;
		reset($ar);
		for($i=0;$i<count($ar);$i++){
			$tmpKey=$prefix.key($ar);
			$tmpValue=current($ar);
			$this->_data[$tmpKey]=$tmpValue;
			next($ar);
		}
		//array_merge
	}
	public function doAppendArray($o,$prefix=''){$this->doAppend($o,$prefix);}
	public function doAppendTree($o,$prefix=''){$this->doAppend($o,$prefix);}
	
	public function doAppendPrefix($prefix)
	{
		$ar=array();
		reset($this->_data);
		for($i=0;$i<count($this->_data);$i++){
			$ar[$prefix.key($this->_data)]=current($this->_data);
			next($this->_data);
		}
		$this->_data=$ar;unset($ar);
	}
	
	
	public function extractJson($fields)
	{
		$fielda=$fields?toSplit($fields,','):array();
		foreach($fielda as $field){
			if($field) $this->extractJsoni(VDCSDATA::deCode($this->getItem($field)),$field.'.');
		}
	}
	public function extractJsoni($jsono,$px){
		foreach($jsono as $node=>$value){
			if(tn($value)=='object') $this->extractJsoni($value,$px.$node.'.');
			else $this->add($px.$node,$value);
		}
	}
	
	public function doTaxis($type='key')
	{
		switch($type){
			case 'key':
				ksort($this->_data);
				break;
			case 'keyr':
				krsort($this->_data);
				break;
			case 'value':
				asort($this->_data);
				break;
			case 'valuer':
				arsort($this->_data);
				break;
		}
		
	}
}


/*
################################################
################################################
################################################
################################################
*/
class utilTableBase{}
class utilTable extends utilTableBase
{
	private $_data=array();
	private $_fields='',$_row=-1,$_col=-1,$_n=-1,$_i=-1,$_isNext=false,$_isLast=false;
	
	public function __construct(){}
	public function __destruct(){unset($this->_data);}
	
	public function doDestroy(){}
	
	
	/*
	########################################
	########################################
	*/
	public function isObj(){return $this->_col?true:false;}public function isData(){return $this->isObj();}public function isTable(){return $this->isObj();}
	
	public function row(){return $this->getRow();}
	public function getRow(){return $this->_row;}public function getLength(){return $this->getRow();}
	public function getCol(){return $this->_col;}
	public function getI(){return $this->_i;}
	
	
	/*
	########################################
	########################################
	*/
	public function doItemBegin(){$this->_n=1; $this->_i=0; $this->_isLast=false;}
	public function doItemEnd(){$this->_n=$this->_row; $this->_i=$this->_row-1; $this->_isLast=true;}
	public function doItemMove($n=1){if(($this->_n+$n)<=$this->_row) $this->_n+=$n;}
	//public function doItemMoved($n=1){$this->doItemMove($n);}		//hold
	
	public function begin(){return $this->doItemBegin();}
	public function doBegin(){return $this->doItemBegin();}
	public function doMove($n=1){$this->doItemMove($n); $this->_i+=$n;}
	public function doMoved($n=1){$this->doMove($n);}
	
	public function next(){return $this->isNext();}
	public function isNext()
	{
		if($this->_row>0 && $this->_i<$this->_row){
			if($this->_i>0) $this->doItemMove();
			$this->_i++;
			return true;
		}
		else{
			return false;
		}
	}
	public function isLast(){return $this->_isLast;}
	
	
	/*
	########################################
	########################################
	*/
	public function add($obj){return $this->addItem($obj);}
	public function addItem($obj)
	{
		$ary=is_object($obj)?$obj->getArray():$obj;
		//if(!is_array($ary)) return false;
		if($this->_row<1){
			if($this->_row<0) $this->setFields(array_keys($ary));
		}
		$this->_n++;
		$this->_row++;
		$this->_data[$this->_row]=$ary;
	}
	
	public function setItem($obj)
	{
		if($this->_n<0) return false;
		$ary=is_object($obj)?$strTree->getArray():$obj;
		//if(!is_array($ary)) return false;
		$this->_data[$this->_n]=$ary;
	}
	
	public function setItemValue($k,$v)
	{
		if($this->_n<0) return false;
		$this->_data[$this->_n][$k]=$v;
	}
	
	public function delItem()
	{
		if(isa($this->_data[$this->_n])){
			//unset($this->_data[$this->_n]);
			for($n=$this->_n;$n<$this->_row;$n++){
				$this->_data[$n]=$this->_data[$n+1];
			}
			unset($this->_data[$this->_row]);
			//debugAry($this->_data[$this->_n+1]);
			$this->_row--;
		}
	}
	
	public function getItemTree()
	{
		$reTree=new utilTree();
		if($this->_n>0) $reTree->setArray($this->_data[$this->_n]);
		return $reTree;
	}
	
	public function iv($k){return $this->getItemValue($k);}public function ivi($k){return $this->getItemValueInt($k);}public function ivn($k){return $this->getItemValueNum($k);}
	public function getItemValue($k){return($this->_n>0)?$this->_data[$this->_n][$k]:'';}
	public function getItemValueInt($k){return intval($this->getItemValue($k));}
	public function getItemValueNum($k){return floatval($this->getItemValue($k));}
	
	
	/*
	public void doItemsSwap(int row1,int row2)
	{
		if(row1<1 || row2<1 || row1>this._row || row2>this._row || row1==row2) return;
		int c;
		String[] arTmp=new String[this._col];
		for(c=0;c<this._col;c++){arTmp[c]=this._aryData[row1][c];}
		for(c=0;c<this._col;c++){this._aryData[row1][c]=this._aryData[row2][c];}
		for(c=0;c<this._col;c++){this._aryData[row2][c]=arTmp[c];}
	}
	
	public void doItemsDel(int row1)
	{
		if(row1<1 || row1>this._row) return;
		int r,c,n=0;
		String[][] aryTemp=this._aryData.clone();
		this._aryData=new String[this._row][this._col];
		for(r=0;r<(this._row+1);r++){
			if(r!=row1){
				for(c=0;c<this._col;c++){this._aryData[n][c]=aryTemp[r][c];}
				n++;
			}
		}
		this._row--;
	}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function getArray(){return $this->_data;}
	public function setArray($ary)
	{
		$this->_data=$ary;
		$this->_n=1;
		if(is_array($this->_data[0])){
			$this->_row=count($this->_data);
			$this->_fields=utilString::toStr($this->_data[0]);
			$this->_data=array_pad($this->_data,-($this->_row+1),$this->_fields);
		}
		else{
			$this->_row=count($this->_data)-1;
			$this->_fields=$this->_data[0];
		}
		$this->_col=count($this->_fields);
	}
	
	public function isField($k){return (inp($this->_fields,$k)>0);}
	public function getFields(){return $this->_fields;}
	public function setFields($fields)
	{
		$this->_n=0;
		$this->_row=0;
		if($fields){
			$this->_fields=is_array($fields)?utilString::toStr($fields):$fields;
			$this->_data[0]=$this->_fields;
			$this->_col=count(utilString::toAry($this->_fields,','));
		}
	}
	public function getFieldsArray(){return toSplit($this->_fields,',');}
	
	public function getValues($k,$type=null,$sl=','){return implode($sl,$this->getValuea($k,$type));}
	public function getValuea($k,$type=null)
	{
		$rea=array();
		if(!$sl) $sl=',';
		if($this->isField($k)){
			$this->doItemBegin();
			for($t=0;$t<$this->getRow();$t++){
				$value=$this->getItemValue($k);
				if($type=='id'){
					$value=$this->getItemValueInt($k);
					if($value<1) $value=null;
				}
				if(!is_null($value)) array_push($rea,$value);
				$this->doItemMove();
			}
		}
		//if($type=='id' && count($rea)<1)
		return $rea;
	}
	
	public function doAppendFields($strFields)
	{
		if($this->_row<0){
			$this->setFields($strFields);
		}
		else{
			$arf=explode(',',$strFields);
			for($a=0;$a<count($arf);$a++){
				if(len($arf[$a])>0&&!$this->isField($arf[$a])){
					$this->_fields.=','.$arf[$a];
				}
			}
			$this->_data[0]=$this->_fields;
		}
	}
	
	public function doAppendTable($o)
	{
		if(!isTable($o)) return;
		$ary1 = $this->getArray();
		$ary2 = $o->getArray();
		$al=count($ary1);
		if ($al==0){
			$ary1=$ary2;
		}else{
			if ($ary1[0]!==$ary2[0]) return;
			for($i=1;$i<count($ary2);$i++){
				$n=$al+$i;
				$ary3 = array($n=>$ary2[$i]);
				$ary1 = array_merge($ary1, $ary3);
			}
		}
		$this->_data=$ary1;
		$this->_row=count($this->_data)-1;
	}
	
	public function doFilter($prefix,$pxr='')
	{
		if($this->_row<0) return;
		$tmpLen=strlen($prefix);
		$this->_fields='';
		$arf=explode(',',$this->_data[0]);
		$arf0=$arf;
		for($a=0;$a<count($arf);$a++){
			$tmpKey=$arf[$a];
			$tmpKey=(toSubstr($tmpKey,1,$tmpLen)==$prefix)?$pxr.toSubstr($tmpKey,$tmpLen+1):$tmpKey;
			$arf[$a]=$tmpKey;
			$this->_fields.=','.$tmpKey;
		}
		if($this->_fields) $this->_fields=toSubstr($this->_fields,2);
		$this->_data[0]=$this->_fields;
		for($i=1;$i<count($this->_data);$i++){
			$ari=array();
			for($a=0;$a<count($arf);$a++){
				$ari[$arf[$a]]=$this->_data[$i][$arf0[$a]];
			}
			$this->_data[$i]=$ari;
		}
		unset($arf,$arf0,$ar);
	}
	
	public function getTermsValue($strTerms,$strField)
	{
		$tmpKey=$strTerms;
		$tmpValue=XCML_NODE_EXIST;
		if(ins($strTerms,'=')>0){
			$tmpKey=left($strTerms,instr($strTerms,'=') - 1);
			$tmpValue=right($strTerms,len($strTerms) - ins($strTerms,'='));
		}
		return $this->getFieldValue($tmpKey,'=',$tmpValue,$strField);
	}
	
	public function getFieldValue($strTermKey,$strTermNexus,$strTermValue,$strField)
	{
		$re='';
		if($strField) return $this->getFieldItem($strTermKey,$strTermNexus,$strTermValue,$strField);
		return $re;
	}
	public function getFieldItem($strTermKey,$strTermNexus,$strTermValue,$strField=null)
	{
		$re=isn($strField)?newTree():'';
		if(!$strTermKey){
			$arFields=explode(',',$this->getFields());
			$strTermKey=$arFields[0];
		}
		$this->doItemBegin();
		for($t=0;$t<$this->getRow();$t++){
			if($this->getItemValue($strTermKey)==$strTermValue){
				$re=isn($strField)?$this->getItemTree():$this->getItemValue($strField);
				break;
			}
			$this->doItemMove();
		}
		return $re;
	}
	public function getFieldItemTree($strTermKey,$strTermNexus,$strTermValue){return $this->getFieldItem($strTermKey,$strTermNexus,$strTermValue);}
}


/*
################################################
################################################
################################################
################################################
*/
class XCML{}
//class utilXCMLBase extends XCML{}

class utilMapBase{}
