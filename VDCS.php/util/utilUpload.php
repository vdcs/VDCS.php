<?
class utilUpload
{
	protected $_var=array();
	
	public function __construct()
	{
		$this->_var['status']		= 'unknown';
		$this->_var['file']		= 'file1';
		
		$this->_var['allowext']		= '';
		$this->_var['maxsize']		= 500;			//最大文件尺寸，默认 500KB
		
		$this->_var['savepath']		= './';			//用于存放上传文件的目录
		
		$this->_var['file.names']	= '';			//原文件名称
		$this->_var['file.type']	= '';			//文件类型 mime
		$this->_var['file.name']	= '';			//文件名称 
		$this->_var['file.ext']		= '';			//文件后缀
		$this->_var['file.size']	= -1;			//文件大小
	}
	public function __destruct()
	{
		unset($this->_var);
	}
	
	
	public function getMode(){return $this->mode;}
	public function setMode($v){$this->_var['mode']=$v;$this->mode=$v;}
	
	public function getResource(){return $this->resource;}
	public function setResource($v){$this->_var['resource']=$v;$this->resource=$v;}
	
	public function getFormat(){return $this->format;}
	public function setFormat($v){$this->_var['format']=$v;$this->format=$v;}
	
	public function setVar($k,$v){$this->_var[$k]=$v;}
	public function getVar($k){return $this->_var[$k];}
	public function getVarInt($k){return intval($this->_var[$k]);}
	
	public function getStatus(){return $this->_var['status'];}
	public function setStatus($v){$this->_var['status']=$v;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		
	}
	
	public function doLoad()
	{
		if(!$this->_var['allowext']) $this->_var['allowext']		= utilFile::ExtsAllowed;
		if(!$this->_var['filename']) $this->_var['filename']		= utilCoder::toMD5(microtime(1).utilCode::getRand(3,6));
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		if($_SERVER['REQUEST_METHOD']!='POST') return;
		
		$_file=$this->_var['file'];
		//debuga($_FILES[$_file]);
		$_filecontents=null;
		$save_quality=toInt($this->_var['save.quality']);
		if($this->resource=='input'){
			$_filecontents=file_get_contents('php://input');
			$filelen=strlen($_filecontents);
			if($filelen<1){
				$this->setStatus('nofile');
				return;
			}
			$this->_var['file.size']=$filelen;
			$this->_var['file.names']=$this->_var['real.names'];
		}
		else{
			if(!isset($_FILES[$_file]) || !is_uploaded_file($_FILES[$_file]['tmp_name']) || $_FILES[$_file]['error']!=0){
				$this->setStatus('nofile');
				return;
			}
			//##########
			$this->_var['file.size']=toInt($_FILES[$_file]['size']);
			$this->_var['file.names']=$_FILES[$_file]['name'];
			$this->_var['file.type']=$_FILES[$_file]['type'];
		}
		$this->_var['file.ext']=utilFile::getFilePart($this->_var['file.names'],'ext');
		$this->_var['file.name']=$this->_var['filename'].'.'.$this->_var['file.ext'];
		//debugx($this->_var['savepath'].$this->_var['file.name']);
		//##########
		if($this->_var['file.size']<1){
			$this->setStatus('nofiles');
			return;
		}
		if($this->_var['file.size']>($this->_var['maxsize']*1024)){
			$this->setStatus('maxsize');
			return;
		}
		//##########
		//debugx($this->_var['file.names'].'---'.$this->_var['file.type'].'---'.'---'.$this->_var['file.ext']);
		if(utilFile::isExtDenied($this->_var['file.ext']) || !utilFile::isExt($this->_var['allowext'],$this->_var['file.ext'])){
			$this->setStatus('ext');
			return;
		}
		//##########
		utilFile::doDirCreated($this->_var['savepath']);
		$filesavepath=$this->_var['savepath'].$this->_var['file.name'];
		if($this->resource=='input'){
			if($this->_var['file.ext']=='jpg'){
				$filelen=@file_put_contents($filesavepath,$_filecontents);
				unset($_filecontents);
				if(!$filelen || $filelen<1){
					$this->setStatus('nosave');
					return;
				}
				//if($save_quality<100){
					$im=@imagecreatefromjpeg($filesavepath);
					@imagejpeg($im,$filesavepath,$save_quality);
				//}
			}
		}
		else{
			//##########
			if(!@move_uploaded_file($_FILES[$_file]['tmp_name'],$filesavepath)){
				$this->setStatus('nosave');
				return;
			}
			//##########
		}
		//##########
		if(len($this->format)>0 && $this->_var['file.ext']!=$this->format){
			if(utilFile::isExtPic($this->format) && utilFile::isExtPic($this->_var['file.ext'])){
				$pathFrom=$this->_var['savepath'].$this->_var['file.name'];
				$filenameTo=$this->_var['filename'].'.'.$this->format;
				$pathTo=$this->_var['savepath'].$filenameTo;
				/*dcsLog(__CLASS__.'.this',test::o($this));
				dcsLog(__CLASS__.'._var',test::a($this->_var));
				dcsLog(__CLASS__.'.pathFrom',$pathFrom);
				dcsLog(__CLASS__.'.filenameTo',$filenameTo);
				dcsLog(__CLASS__.'.pathTo',$pathTo);*/
				$isCover=utilImage::doFileConver($this->_var['file.ext'],$pathFrom,$this->format,$pathTo);
				if($isCover){
					$this->_var['file.size']=utilFile::getSize($pathTo);
					$this->_var['file.names']=utilFile::getPathPart($pathTo,'names');
					$this->_var['file.type']=utilImage::getMimeType($pathTo);
					$this->_var['file.name']=$filenameTo;
					$this->_var['file.ext']=utilFile::getPathPart($pathTo,'ext');
					$this->_var['file.name']=$this->_var['filename'].'.'.$this->_var['file.ext'];
					//debuga($this->_var);
					utilFile::doFileDel($pathFrom);
				}
			}
		}
		//##########
		$this->setStatus('succeed');
	}
	
	
}
?>