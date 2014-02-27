<?
class VerifyShield
{
	const SYNC_PEAK		= 10;
	protected $treeShield,$aryShield=array();
	protected $_Channel;
	protected $_Message='无效的防护验证';
	
	public function __construct()
	{
		$this->_Channel='post';
	}
	public function __destruct() { }
	
	
	public function setChannel($s){$this->_Channel=$s;}
	public function getChannel(){return $this->_Channel;}
	
	
	/*
	########################################
	########################################
	*/
	public function isBanIP($sort='post')
	{
		$re=false;
		$ip = DCS::ip();
		$arys = $this->getValuesAry("banip",$sort);
		foreach($arys as $_ip){
			if(ins(",".$ip.".", ",".$_ip.".") > 0){
				$re=true;
				break;
			}
		}
		return $re;
	}
	
	public function isBadWord($content,$sort='post')
	{
		$re=false;
		if(len($content)<1) return $re;
		$_content=$content;
		for($c=0;$c<=47;$c++){
			$_content = str_replace(chr($c),'',$_content);
		}
		for($c=58;$c<=64;$c++){
			$_content = str_replace(chr($c),'',$_content);
		}
		for($c=91;$c<=96;$c++){
			$_content = str_replace(chr($c),'',$_content);
		}
		$_content = str_replace(' ','',$_content);
		$_content = str_replace(chr(9),'',$_content);
		$_content = str_replace(chr(10),'',$_content);
		$_content = str_replace(chr(13),'',$_content);
		$arys = $this->getValuesAry("badword",$sort);
		foreach($arys as $_word){
			if(ins($_content,$_word) > 0){
				$re=true;
				break;
			}
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getValuesAry($type,$sort='post')
	{
		$arys=$this->aryShield[$type.'.'.$sort];
		if(!isa($arys)){
			$this->aryShield[$type.'.'.$sort] = toSplit($this->getValues($type,$sort));
			$arys=$this->aryShield[$type.'.'.$sort];
		}
		return $arys;
	}
	public function getValues($type,$sort='post')
	{
		$re=$this->getValue($type.'.common');
		if($sort!='common') $re.=','.$this->getValue($type.'.'.$sort);
		$re=trim($re,',');
		return $re;
	}
	public function getValue($keys)
	{
		if(!$this->treeShield){
			$this->treeShield=VDCSDTML::getConfigTree("vdcs.config/data/shields");
			$this->treeShield->doAppendTree(VDCSDTML::getConfigTree("common.config/data/shields"));
		}
		return $this->treeShield->getItem($keys);
	}
	
	public function getFiltrateTree($type)
	{
		$reTree=newTree();
		$arys=toSplit($this->getValue("filtrate.common").",".$this->getValue("filtrate.".$type), ",");
		for($a=0;$a<count($arys);$a++){
			if(len($arys[$a])>0){
				utilString::lists($arys[$a],$key,$value,'=');
				if(len($value)<1) $value=$key;
				$reTree->addItem($key,$value);
			}
		}
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function ipFilter($ip,$type=0)
	{
		$re='';
		$n=1;
		if($type>0){
			$n=3;
			if($type>5) $n=4;
			if($type>8) $n=6;
		}
		$arys=toSplit($ip,'.');
		for($a=($n-1);$a<=count($arys);$a++){
			$arys[$a]='*';
		}
		$re=implode('.',$arys);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getMessage($type='')
	{
		$re='';
		global $cfg;
		$re=$cfg->getLang('message.'.$type);
		if(len($re)<1) $re=$this->_Message.'('.$type.')';
		return $re;
	}
	
}
?>