<?
trait ManageRefChannel
{
	
	public function loadChannel($channel='')
	{
		if(!$channel) $channel=$this->_chn_;
		if(!$channel) $channel='default';
		if(inp('default,system',$channel)<1){
			$this->cfg->setChannel($channel);
			$this->cfg->doChannelInit();
		}
		$this->_chn_=$channel;
		//##########
		$this->modules=$this->_p_;$this->modulec='';
		if($this->_m_) $this->modules.='.'.$this->_m_;
		if($this->_mi_) $this->modules.='.'.$this->_mi_;
		if($this->modules) $this->modulec=$this->modules.':';
		//debugx('modules='.$this->modules.', modulec='.$this->modulec);
		//##########
		$this->chn=new ManageChannel();
		$this->chn->doInit($channel);
		$this->chn->modules=&$this->modules;
		$this->chn->modulec=&$this->modulec;
		$this->chn->portal=&$this->_p_;
		$this->chn->module=&$this->_m_;
		$this->chn->extend=&$this->_x_;
		$this->chn->action=&$this->action;
		//debugx($this->chn->modules.' , '.$this->chn->modulec.' , '.$this->chn->getModules());
		//##########
		$this->TableName=$this->getConfig('table.name');
		$this->TablePX=$this->getConfig('table.px');
		$this->FieldID=$this->getConfig('table.field.id');
		//debugx('TableName='.$this->TableName.', TablePX='.$this->TablePX.', FieldID='.$this->FieldID);
		//##########
		$this->chnPre=CommonChannelExtend::getPreTree($this->chn->get(),true);
		$this->chnPre->addItem('portals',$this->_chn_);
		$this->chnPre->addItem('channel',$this->_chn_);
		$this->chnPre->addItem('portal',$this->_p_);
		$this->chnPre->addItem('module',$this->_m_);
		$this->chnPre->addItem('modulei',$this->_mi_);
		$this->chnPre->addItem('modulec',$this->modulec);
		$this->chnPre->addItem('xchannel',$this->_chn_);
		$this->chnPre->addItem('xportals',$this->_chn_);
		$this->chnPre->addItem('xportal',$this->_p_);
		$this->chnPre->addItem('tbl.name',$this->TableName);
		$this->chnPre->addItem('tpx',$this->TablePX);
		$this->chnPre->addItem('tbl.field.id',$this->FieldID);
		$this->chnPre->addItem('xname',tv($this->chnPre->getItem($this->modulec.'name'),$this->getLang('title.name')));
		$this->chnPre->addItem('xnames',tv($this->chnPre->getItem($this->modulec.'names'),$this->getLang('title.names'),$this->getLang('title.name')));
		$this->chnPre->addItem('xact',tv($this->chnPre->getItem($this->modulec.'act'),$this->getLang('act')));
		$this->chnPre->addItem('xact.get',tv($this->chnPre->getItem($this->modulec.'act.get'),$this->getLang('act.get')));
		$this->chnPre->addItem('xact.view',tv($this->chnPre->getItem($this->modulec.'act.view'),$this->getLang('act.view')));
		$this->chnPre->addItem('xunit',tv($this->chnPre->getItem($this->modulec.'unit'),$this->getLang('unit')));
		//debugTree($this->chnPre);
		$this->chnPre->doBegin();
		for($t=0;$t<=$this->chnPre->getCount();$t++){
			$this->theme->setPre($this->chnPre->getItemKey(),$this->chnPre->getItemValue());
			$this->chnPre->doMove();
		}
		//##########
		$this->is_loadChannel=true;
		
		$this->_var['PageMode']='list';
		$this->_var['PageAction']='list';
	}
	
	public function setChannel($s){$this->chn->set($s);}
	public function getChannel(){return $this->chn->get();}
	
	public function getConfigValue($node,$modules,$k=null){return $this->chn->getConfigureValue($node,$modules,$k);}
	public function getConfig($modules='',$k=null){return $this->chn->getConfig($modules,$k);}
	public function getConfiga($k,$real=false,$cmb='.')
	{
		$kk=$k;
		if(ins($kk,'{action}')<1) $kk='{action}'.$cmb.$kk;
		//debugx($k.' , '.r($kk,'{action}',$this->_var['PageAction']));
		$re=$this->chn->getConfig(r($kk,'{action}',$this->_var['PageAction']));
		if($real && strlen($re)<1) $re=$this->chn->getConfig($k);
		return $re;
	}
	//public function getConfigm($k){return $this->chn->getConfigm($k);}
	//public function getConfigma($k,$cmb='.'){return $this->chn->getConfigm($this->_var['PageAction'].$cmb.$k);}
	public function getLang($modules='',$k=null){return $this->chn->getLang($modules,$k);}
	public function getLangx($modules='',$k=null){$re=$this->getLangi($modules,$k);if(!$re)$re='['.($k?$k:$modules).']';return $re;}
	public function getLangi($modules='',$k=null){return $this->chn->getLang($modules,$k);}
	public function getLanga($k,$real=false,$cmb='.')
	{
		if(ins($k,'{action}')<1) $k='{action}'.$cmb.$k;
		$re=$this->chn->getLang(r($k,'{action}',$this->_var['PageAction']));
		if($real && (strlen($re)<1 || substr($re,0,1)=='[')) $re=$this->chn->getLang($k);
		return $re;
	}
	//public function getLangm($k){return $this->chn->getLangm($k);}
	
	public function loadChannelStruct()
	{
		$this->tableChannelStruct=CommonChannelExtend::getStructTermTable('','','all');
		//debugTable($this->tableChannelStruct);
	}
	
}
?>