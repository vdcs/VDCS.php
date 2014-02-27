<?
class VerifyCodeI
{
	const SYNC_PEAK		= 10;
	const SYNC_SYMBOL	= ',';
	const SYMBOL		= '0123456789abcdefg';
	
	protected $_Mode;
	protected $_module;
	protected $_Codes,$_Code,$_CodeValue;
	protected $_Field='_vcode_img';
	protected $_Message='验证码 为空或不符合规则';
	
	public function __construct()
	{
		
	}
	public function __destruct(){}
	
	
	/*
	########################################
	########################################
	*/
	public function getKey(){return 'vcode_'.$this->_module;}
	
	public function setMode($s){$this->_Mode=$s;}
	public function getMode(){return $this->_Mode;}
	public function setModule($s)
	{
		$this->_module=$s;
		if($this->_module){
			$this->_isInit=false;
			$this->doInit();
		}
	}
	public function getModule(){return $this->_module;}
	
	public function getField(){return $this->_Field;}
	
	
	/*
	########################################
	########################################
	*/
	public function getCodes(){return DCS::sessionGet($this->getKey());}
	public function setCodes($v){return DCS::sessionSet($this->getKey(),$v);}
	
	public function getCode(){return $this->_Code;}
	public function setCodeValue($v){$this->_CodeValue=$v;}
	
	public function getFormHidden(){return '<input type="hidden" name="'.$this->_Field.'" value="'.$this->_Code.'" />';}
	
	public function setMessage($s){$this->_Message=$s;}
	public function getMessage($type='')
	{
		$re='';
		global $cfg;
		$re=$cfg->getLang('message.'.$type);
		if(!$re) $re=$this->_Message;
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->_isInit) return;$this->_isInit=true;
		if(!$this->_module) $this->_module=queryx('module');
		if(!$this->_module) return;
		
		$ismode=false;
		switch($this->_Mode){
			case 'ui':
				$ismode=true;
				break;
			case 'data':
				$ismode=false;
				break;
		}
		
		$this->_Codes=$this->getCodes();
		if($ismode){
			$this->_Code=$this->getRand(4);
			$this->_Codes=utilStrings::toExtentAppend($this->_Codes,$this->_Code,self::SYNC_SYMBOL,self::SYNC_PEAK);
			$this->setCodes($this->_Codes);
		}
		else{
			$arCode=toSplit($this->_Codes,',');
			$this->_Code=isa($arCode)?$arCode[count($arCode)-1]:'';
		}
	}
	
	public function isCheck()
	{
		$this->doInit();
		$re=false;
		$this->_Codes=$this->getCodes();
		$this->_Code=$this->_CodeValue;
		//debugx('VerifyCode._Codes='.$this->_Codes);
		//debugx('VerifyCode._Code='.$this->_Code);
		if(len($this->_Code)<1) $this->_Code=postx($this->_Field);
		if(len($this->_Code)<1) $this->_Code=queryx($this->_Field);
		//debugx('VerifyCode._Code='.$this->_Code);
		if($this->_Codes && $this->_Code){
			if(utilStrings::isExtentValue($this->_Codes,$this->_Code,self::SYNC_SYMBOL)){
				$this->_Codes=utilStrings::toExtentFilter($this->_Codes,$this->_Code,self::SYNC_SYMBOL);
				$this->setCodes($this->_Codes);
				$re=true;
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doDraw($style='normal')
	{
		global $vcodeword;
		$vcodeword=$this->getCode();
		include_once(_BASE_PATH_VDCS.'lib/model/vcode/draw.'.($style ? $style : 'normal').EXT_EXECUTE);
	}
	
	public function doAudio($style='normal')
	{
		global $vcodeword;
		$vcodeword=$this->getCode();
		include_once(_BASE_PATH_VDCS.'lib/model/vcode/audio.'.($style ? $style : 'normal').EXT_EXECUTE);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getRand($n)
	{
		$chars=self::SYMBOL;
		return utilCode::toRandom($chars,$n);
	}
	
	
	/*
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	@@@@@@@@@@@@@ DTML Dispose @@@@@@@@@@@@@
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	*/
	public function toDTML($re)
	{
		if(ins($re,'<vcp:')>0){
			$re=r($re,'<vcp:module>',$this->getModule());
			$re=r($re,'<vcp:code>',$this->getCode());
			$re=r($re,'<vcp:codes>',$this->getCodes());
			$re=r($re,'<vcp:form.field>',$this->getField());
			$re=r($re,'<vcp:form.hidden>',$this->getFormHidden());
			$re=r($re,'<vcp:message>',$this->getMessage());
		}
		return $re;
	}
	
	public function toDTMLValue($key,$type=null)
	{
		$re='';
		switch($key){
			case 'module':			$re=$this->getModule();break;
			case 'code':			$re=$this->getCode();break;
			case 'codes':			$re=$this->getCodes();break;
			case 'form.field':		$re=$this->getField();break;
			case 'form.hidden':		$re=$this->getFormHidden();break;
			case 'message':			$re=$this->getMessage();break;
		}
		return $re;
	}
		
}
?>