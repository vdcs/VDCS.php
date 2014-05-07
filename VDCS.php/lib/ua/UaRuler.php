<?
class UaRuler
{
	protected $_mode='',$_popedom='',$_popedoms='',$_grade=-1;
	protected $_channel='',$_module='',$_action='';
	protected $_isPopedom=false;
	
	
	public function __construct()
	{
		
	}
	public function __destruct(){}
	public function doDestroy(){}
	
	
	/*
	########################################
	########################################
	*/
	public function getValuePopedoms()
	{
		//return $GLOBALS['ua']->getData('popedoms');
		return '';
	}
	public function getValueGrade()
	{
		//return $GLOBALS['ua']->getGrade();
		return -1;
	}
	
	public function getValueChannel(){return $this->channel;}	//$GLOBALS['ctl']
	public function getValuePortal(){return $this->portal;}
	public function getValueModule(){return $this->module;}
	public function getValueModulei(){return $this->modulei;}
	public function getValueAction(){return $this->action;}
	
	
	/*
	########################################
	########################################
	*/
	public function setMode($s){$this->_mode=$s;}
	
	public function setChannel($s){$this->_channel=$s;}
	public function getChannel($v=0)
	{
		$re=$this->_channel;
		if($v==1 && !$re) $re=$this->getValueChannel();
		return $re;
	}
	
	public function setModule_($s){$this->_modules=$s;}
	public function getModule_($v=0)
	{
		$re=$this->_modules;
		if($v==1 && !$re){
			$re=$this->portal;
			if($this->module) $re.='.'.$this->module;
			if($this->modulei) $re.='.'.$this->modulei;
		}
		return $re;
	}
	public function getModulec($v=0)
	{
		$re=$this->getModule_($v);
		if($re) $re.=UaRulerExtend::CMODULE;
		return $re;
	}
	
	public function setAction($s){$this->_action=$s;}
	public function getAction($v=0)
	{
		$re=$this->_action;
		if($v==1 && !$re) $re=$this->getValueAction();
		return $re;
	}
	public function getActionc($v=0)
	{
		$re=$this->getAction($v);
		if(!$re) $re='list';
		return $re;
	}
	
	/*
	########################################
	########################################
	*/
	public function isPopedom(){return $this->_isPopedom;}
	
	public function setPopedom($s){$this->_popedom=$s;}
	public function setPopedoms($s){$this->_popedoms=$s;}
	public function getPopedoms($v=0)
	{
		$re=$this->_popedoms;
		if($v==1 && !$re) $re=$this->getValuePopedoms();
		return $re;
	}
	
	public function getGrade($v=0)
	{
		$re=$this->_grade;
		if($v==1 && $re<0) $re=$this->getValueGrade();
		return $re;
	}
	
	
	public function isPopedomCheck($channel,$act='')
	{
		$re=false;
		if(!$channel) $channel='default';
		if($this->getGrade(1)>9 || $channel=='default'){
			$re=true;
		}
		else{
			$_popedoms=$this->getPopedoms(1);
			$_channel=$channel;
			//debugx($_popedoms);
			if(ins(','.$_popedoms,','.$_channel.':')>0){
				$re=true;
				if($act){
					$_popedom=$channel.':'.$act;
					//debugx($_popedoms);
					//debugx($_popedom);
					if(inp($_popedoms,$_popedom)<1) $re=false;
				}
			}
		}
		return $re;
	}
	
	public function doPopedomCheck()
	{
		//debugx($this->getGrade(1));
		if($this->_mode=='ignore' || $this->getGrade(1)>9){
			$this->_isPopedom=true;
			return;
		}
		$_popedoms=$this->getPopedoms(1);
		$_popedom=$this->_popedom;
		$_channel=$this->getChannel(1);
		$_modulec=$this->getModulec(1);
		$_actionc=$this->getActionc(1);
		//debugx('popedoms: '.$_popedoms);
		//debugx('cma: '.$_channel.','.$_modulec.','.$_actionc);
		$isCheck=false;
		if(ins(','.$_popedoms,','.$_channel.':')>0){
			if(!$_popedom){
				if(isFormPost()) $tmpHandle=post('_select_handle');
				//debugx($tmpHandle);
				if($tmpHandle){
					$_popedom=$_channel.':'.$_modulec.'handle.'.$tmpHandle;
				}
				else{
					if($_actionc){
						$_popedom=$_channel.':'.$_modulec.'action.'.$_actionc;
					}
					else{
						$isCheck=true;
					}
				}
				//debugx($_popedom);
			}
			//debug _popedoms&'<br>'&_popedom
			if($isCheck || inp($_popedoms,$_popedom,',')>0) $this->_isPopedom=true;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toFilterSelectOption($options)
	{
		$re='';
		$_grade=$this->getGrade(1);
		$_popedoms=$this->getPopedoms(1);
		$_channel=$this->getChannel(1);
		$_modulec=$this->getModulec(1);
		//debugx($_grade.'  '.$_popedoms);
		//debugx('cma: '.$_channel.','.$_modulec.','.$_actionc);
		//debugx($options);
		$optionsAr=toSplit($options,',');
		foreach($optionsAr as $option){
			utilString::lists($option,$key,$name,'=');
			//debugx($option.': '.$key.'='.$name);
			$_popedom=$_channel.':'.$_modulec.'handle.'.$key;
			//debugx($_popedom);
			if($_grade>9 || inp($_popedoms,$_popedom,',')>0) $re.=','.$option;
		}
		if(len($re)>0) $re=toSubstr($re,2);
		return $re;
	}
	
}
?>