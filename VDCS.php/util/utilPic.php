<?
class utilPic
{
	protected $_var=array();
	protected $treeConfig=null;
	
	public function __construct()
	{
		$this->_var['filename']		= '';
	}
	public function __destruct()
	{
		unset($this->_var,$this->treeConfig);
	}
	
	
	public function setVar($k,$v){$this->_var[$k]=$v;}
	public function getVar($k){return $this->_var[$k];}
	
	public function setVarArray($arr){return $this->_var=$arr;}
	public function setConfigArray($arr){$this->treeConfig=newTree();$this->treeConfig->setArray($arr);}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseThumb()
	{
		//debugx('thumbnames='.self::toMakeThumb($baseurl,'big.jpg','','',120,90));
		if(len($this->_var['thumbinput'])<1 || !$this->_var['isthumb']) return;
		$this->_var['thumb.width']=toInt($this->_var['channel.thumb.width']);
		$this->_var['thumb.height']=toInt($this->_var['channel.thumb.height']);
		if($this->_var['thumb.width']<1 || $this->_var['thumb.height']<1){
			$this->_var['thumb.width']=$this->treeConfig->getItemInt('thumb.weight');
			$this->_var['thumb.height']=$this->treeConfig->getItemInt('thumb.height');
		}
		if($this->_var['thumb.width']>0 && $this->_var['thumb.height']>0){
			$this->_var['thumb.name']=utilImage::toMakeThumb($this->_var['savepath'],$this->_var['file.name'],'',$this->_var['thumbname'],$this->_var['thumb.width'],$this->_var['thumb.height']);
			if(len($this->_var['thumb.name'])>0){
				$this->_var['thumb.is']=true;
				$this->_var['thumb.ext']=getPathPart($this->_var['thumb.name'],'ext');;
				$this->_var['thumb.size']=filesize($this->_var['savepath'].$this->_var['thumb.name']);
			}
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParseWatermark()
	{
		
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doConverFormat()
	{
		
	}

}
?>