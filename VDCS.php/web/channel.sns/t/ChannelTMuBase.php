<?
class ChannelTMuBase extends ChannelTBase
{
	public $treeBase,$treeUa;
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->treeBase,$this->treeUa);
	}
	
	public function uInit()
	{
		if($this->isuInit)return;$this->isuInit=true;
		//$this->treeUa=$this->ua->queryTree($this->uid,1);
		//debugTree($this->treeUa);
	}
	public function uData($k){$this->uInit();return $this->treeUa->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		parent::initBasic();
		
		$this->IndexNames='主页';
		$this->ProfileNames='个人资料';
		
		$this->treeBase=newTree();
		//$this->loadBase();
	}
	
	public function doInitPre()
	{
		parent::doInitPre();
		$this->loadBase();
	}

	public function doIniter()
	{
		parent::doIniter();
		//debugx('do.initer');
		//$this->theme->setTheme($this->_chn_);
		//$this->theme->setDir('default');
		
		//$this->theme->setDir($this->theme->getDir().'/'.PAGE_PX);
		//debugx($this->theme->getDir());
		//$this->page=$this->_p_?$this->_p_:'index';
		//if($this->_m_) $this->page.='.'.$this->_m_;
		//$this->theme->setPage($this->page);
		//$this->theme->setPre('mu','mu');
		
		$this->page=$this->_m_?$this->_m_:'index';
		$this->theme->setModule($this->page);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function loadBase()
	{
		$this->muid=queryi('muid');
		$this->uid=$this->muid;
		$this->keyid=$this->muid;
		$this->treeUa=$this->ua->queryTree($this->uid,1);
		//debugTree($this->treeUa);
		if($this->treeUa->getCount()<1){
			go($this->cURL('no'));
			return;
		}
		$this->_var['is']=true;
		$this->_var['sqlquery.key']='keyid='.$this->keyid;
		//##########
		$this->treeBase->addItem('muid',$this->muid);
		$this->treeBase->addItem('uid',$this->uid);
		$this->treeBase->addItem('id',$this->keyid);
		$this->treeBase->addItem('name',$this->treeUa->getItem('name'));
		$this->treeBase->addItem('desc',$this->treeUa->getItem('sign'));
		$urls=DCS::url($this->cfg->toLinkURL('mu','muid='.$this->keyid));
		//debugx($urls);
		$this->treeBase->addItem('urls',$urls);
		//##########
		$this->treeBase->addItem('names',$this->Names);
		$this->treeBase->addItem('names.index',$this->IndexNames);
		$this->treeBase->addItem('names.profile',$this->ProfileNames);
		//debugTree($this->treeBase);
		//##########
		$this->follow=UcContactsFollow::is($this->ua,$this->uid);
		//debugx($this->uid);
		//debugx($this->follow);
		$this->treeBase->addItem('follow',$this->follow);
		//##########
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
		$this->theme->doCacheFilterTree('base','cpo.treeBase');
		$this->theme->doCacheFilterTree('uad','cpo.','uData');
		$this->theme->doCacheFilterTree('userd','cpo.','uData');
		
		$this->theme->doCacheFilterLoop('list','cpo.tableList');
		$this->theme->doCacheFilterPaging($this->p,'cpo.p');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function cURL($page,$keys='')
	{
		switch($page){
			case 'no':	$re=$this->cfg->getLinkURL($this->channel,'index');break;
			default:	$re=$this->cfg->getLinkURL($this->channel,'mu.'.$page,'muid='.$this->muid.'&'.$keys);break;
		}
		return $re;
	}
	
}
?>