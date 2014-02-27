<?
trait WebPortalRefVerify
{
	protected $vcode,$vcp;
	protected $vshield;
	
	
	/*
	########################################
	########################################
	*/
	protected function loadVcode($module=null)
	{
		$this->vcode=new VerifyCodeI();
		$this->vcode->setMode('data');
		if($channel) $this->vcodeModule($module);
		$this->vcp=$this->vcode;
	}
	protected function vcodeModule($module=null)
	{
		if($module || !$this->vcode->getModule()){
			if(!$module) $module='channel';
			if($module=='channel') $module=$this->_chn_;
			if($module=='portal') $module=$this->_chn_.($this->_p_?'.'.$this->_p_:'');
			//debugx('module='.$module);
			$this->vcode->setModule($module);
		}
	}
	
	
	protected function vcodeFormCheck()
	{
		global $ctl;
		if(!$ctl->e->isCheck()){
			if(!$this->isVcodeCheck()) $ctl->e->addItem($this->vcode->getMessage(),$this->vcode->getField());
		}
	}
	
	protected function isVcodeCheck()
	{
		$this->vcodeModule();
		return $this->vcode->isCheck();
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function loadShield($channel=null)
	{
		$this->vshield=new VerifyShield();
		if($channel) $this->vshield->setChannel($channel);
	}
	protected function shieldChannel($channel=null)
	{
		if(!$this->vshield->getChannel()<1){
			$channel='post';
			$this->vshield->setChannel($channel);
		}
	}
	
	
	protected function shieldFormCheck($ip=false,$fields='',$channel=null)
	{
		global $ctl;
		if(!$ctl->e->isCheck()){
			$this->shieldChannel();
			$channel=$channel?:$this->vshield->getChannel();
			if($ip && $this->vshield->isBanIP($channel)) $ctl->e->addItem($this->vshield->getMessage('banip'));
			if(len($fields)>0){
				$arField=toSplit($fields,',');
				foreach($arField as $field){
					if($this->vshield->isBadWord($ctl->treeData->getItem($field),$channel)){
						$ctl->e->addItem($this->vshield->getMessage('badword'));
						break;
					}
				}
			}
		}
	}
	
	protected function isShieldBanIP($channel=null)
	{
		$this->shieldChannel();
		$channel=$channel?:$this->vshield->getChannel();
		return $this->vshield->isBanIP($channel);
	}
	
	protected function isShieldBadWord($content,$channel=null)
	{
		$this->shieldChannel();
		$channel=$channel?:$this->vshield->getChannel();
		return $this->vshield->isBadWord($content,$channel);
	}
	
	
	/*
	########################################
	########################################
	*/
	protected function isContentSimilar($content,$tbData,$field,$percenta=80)
	{
		$re=false;
		$tbData->doBegin();
		while($tbData->isNext()){
			$_percent=utilCoder::toSimilarPercent($content,$tbData->getItemValue($field));
			if($_percent>$percenta){
				$re=true;
				break;
			}
		}
		return $re;
	}
	
}
?>