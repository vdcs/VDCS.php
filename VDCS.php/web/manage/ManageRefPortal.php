<?
trait ManageRefPortal
{
	
	protected function doAppendURL($s){if($s) $this->_var['AppendURL'].=$s.'&';$this->appendURLSet();}
	protected function setAppendURL($s){$this->_var['AppendURL']=($s) ? $s.'&' : '';$this->appendURLSet();}
	protected function appendURLSet()
	{
		$params=trim($this->_var['AppendURL'],'&');
		$this->addDTML('x.params',$params);
		$this->addDTML('a.params',$params);
	}

	protected function doAppendQuery($s){if($s) $this->_var['AppendQuery']=DB::sqla($this->_var['AppendQuery'],$s);}
	protected function setAppendQuery($s){$this->_var['AppendQuery']=$s;}
	
	protected function setPageMode($s){$this->_var['PageMode']=$s;}
	protected function getPageMode(){return $this->_var['PageMode'];}
	
	protected function setPageAction($s){$this->_var['PageAction']=$s;}
	protected function getPageAction(){return $this->_var['PageAction'];}
	
	public function toURL($act=''){return $this->getURL($act);}
	public function getURL($act='',$params='')
	{
		$acts='';
		if(ins($act,'!')>0){
			utilString::lists($act,$act,$acts,'!');
			//list($act,$acts)=split('!',$act,2);
			//$acts=toSubstr($act,ins($act,'!')+1);
			//$act=toSubstr($act,1,ins($act,'!')-1);
		}
		//debugx($act.' , '.$acts);
		if(len($params)>0){
			if(ins($params,':')<1) $params=$this->_chn_.':'.$params;
			list($_portals,$_portal)=split(':',$params,2);
			//$_portals=substr($params,0,ins($params,':')-1);
			//$_portal=substr($params,ins($params,':'));
			/*
			if(ins($_portals,'.')>0){
				$_portal=substr($_portals,0,ins($_portals,'.')-1);
				$_portals=substr($_portals,ins($_portals,'.'));
			}
			*/
		}
		//debugx($_portals.' , '.$_portal);
		if(ins($act,'=')>0){
			$act='&'.$act;
			//debugx($act);
			$treeAct=utilString::toTree($act,'&','=');
			//debugTree($treeAct);
			if($treeAct->isItem('portals')){
				$_portals=$treeAct->getItem('portals');
				$act=r($act,'&portals='.$_portals,'');
			}
			if($treeAct->isItem('channel')){
				$_portals=$treeAct->getItem('channel');
				$act=r($act,'&channel='.$_portals,'');
			}
			if($treeAct->isItem('chn')){
				$_portals=$treeAct->getItem('chn');
				$act=r($act,'&chn='.$_portals,'');
			}
			if($treeAct->isItem('portal')){
				$_portal=$treeAct->getItem('portal');
				$act=r($act,'&portal='.$_portal,'');
			}
			if($treeAct->isItem('p')){
				$_portal=$treeAct->getItem('p');
				$act=r($act,'&p='.$_portal,'');
			}
			if($treeAct->isItem('module')){
				$_module=$treeAct->getItem('module');
				$act=r($act,'&module='.$_module,'');
			}
			if($treeAct->isItem('m')){
				$_module=$treeAct->getItem('m');
				$act=r($act,'&m='.$_module,'');
			}
			if($treeAct->isItem('mi')){
				$_modulei=$treeAct->getItem('mi');
				$act=r($act,'&mi='.$_modulei,'');
			}
			if($treeAct->isItem('extend')){
				$_extend=$treeAct->getItem('extend');
				$act=r($act,'&extend='.$_extend,'');
			}
			if($treeAct->isItem('x')){
				$_extend=$treeAct->getItem('x');
				$act=r($act,'&x='.$_extend,'');
			}
		}
		//debugx($_portals.','.$_portal.','.$_module.','.$_modulei.','.$_extend);
		$act=trim($act,'&');
		if(!$_portals) $_portals=$this->_chn_;
		if(!$_portal) $_portal=$this->_p_;
		if($_portal=='null') $_portal='';
		//debugx($_module);
		if(!$_module) $_module=$this->_m_;
		if($_module=='null') $_module='';
		if(!$_modulei) $_modulei=$this->_mi_;
		if($_modulei=='null') $_modulei='';
		//debugx($_portals.','.$_portal.','.$_module.','.$_modulei.','.$_extend);
		//if($this->chn->portal!=$this->chn->channel) $_channel=$this->chn->channel;
		$re=ManageCommon::getURL('portal');
		//$re.=ins($re,'?')<1 ? '?' : '&';
		$re.=$_portals;
		if($_portal) $re.='/'.$_portal;
		if($_module) $re.='/'.$_module;
		if($_modulei) $re.='/'.$_modulei;
		if($_extend) $re.='.'.$_extend;
		$re.='?';
		if(ins($acts,'ip')<1) $re.=$this->_var['AppendURL'];
		if($ctl->channel && ins($act,'&channel=')<1) $re.='channel='.$ctl->channel.'&';
		if($ctl->module && ins($act,'&module=')<1) $re.='module='.$ctl->module.'&';
		if($ctl->subchannel && ins($act,'&subchannel=')<1) $re.='subchannel='.$ctl->subchannel.'&';
		if($ctl->sort && ins($act,'&sort=')<1) $re.='sort='.$ctl->sort.'&';		// && inp(params,'sort')>0
		if($ctl->mode && ins($act,'&mode=')<1) $re.='mode='.$ctl->mode.'&';		// && inp(params,'mode')>0
		if($ctl->taxis && ins($act,'&taxis=')<1) $re.='taxis='.$ctl->taxis.'&';		// && inp(params,'taxis')>0
		if($ctl->classid!=0 && ins($act,'&classid=')<1) $re.='classid='.$ctl->classid.'&';
		if(ins($acts,'s')>0){		//inp(params,'s')>0
			if($this->s->keyword && ins($act,'&keyword=')<1) $re.='keyword='.$this->s->keyword.'&';
			if($this->s->field && ins($act,'&sea_field=')<1) $re.='sea_field='.$this->s->field.'&';
			if($this->s->term && ins($act,'&sea_term=')<1) $re.='sea_term='.$this->s->term.'&';
			if($this->s->time1 && ins($act,'&sea_time1=')<1) $re.='sea_time1='.$this->s->time1.'&';
			if($this->s->time2 && ins($act,'&sea_time2=')<1) $re.='sea_time2='.$this->s->time2.'&';
		}
		switch($acts){
			case 'act':
			case 'action':
				$re.='action='.$this->action.'&';
				if($this->id>0) $re.='id='.$this->id.'&';
				break;
		}
		if($act){
			switch($act){
				case 'action':
					$re.='action='.$this->action.'&';
					if($this->id>0) $re.='id='.$this->id.'&';
					break;
				default:
					$re.=$act;
					//if(ins($act,'module=')) re=r(re,'module='.$_module.'&','')
					break;
			}
		}
		return $re;
	}
	
	public function toURLCommon($act,$params=null)
	{
		if(is_null($params)){
			$params=$act;
			$act='url';
		}
		return urlLink($this->cfg->getLinkURL('common',$act),$params);
	}
	
	
}
?>