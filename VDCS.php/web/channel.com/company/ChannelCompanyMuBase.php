<?
class ChannelCompanyMuBase extends WebPortalBase
{
	public $treeBase;
	protected $MU_CHANNEL		= 'company';
	protected $MU_UAKey		= 'company';
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->treeBase);
	}
	
	public function uiData($k){return $this->uai->getData($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->theme->setTheme('muc');
		$this->theme->setDir('company');
		$this->page=$this->_p_?$this->_p_:'index';
		if($this->_m_) $this->page.='.'.$this->_m_;
		$this->theme->setPage($this->page);
		$this->theme->setPre('mu','mu');
		$this->theme->setPre('channelc',$this->MU_CHANNEL);
		
		$this->treeBase=newTree();
		$this->initBase1();
		
		$this->cfg->setTitle('page',$this->this->treeBase->getItem('name'));
	}
	
	public function initBase1()
	{
		$this->muid=queryi('muid');
		$this->uaid=$this->muid;
		$this->keyid=$this->muid;
		$this->_var['sqlquery']='comid='.$this->keyid;
		$this->_var['sqlquery.corp']='corpid='.$this->uaid;
		
		$this->uai=&Ua::instance($this->MU_UAKey);
		//$this->uai->init();
		$this->uai->setID($this->muid);
		$this->uai->dataLoader();
		$this->treeBase=$this->uai->getDataTree();
		//debugTree($this->treeBase);
		if($this->treeBase->getItemInt('id')<1){
			go($this->cURL('no'));
			return;
		}
		$this->_var['is']=true;
		$this->_var['sqlquery.key']='keyid='.$this->keyid;
		//##########
		$this->treeBase->addItem('muid',$this->muid);
		$this->treeBase->addItem('uaid',$this->uaid);
		//$this->treeBase->addItem('id',$this->keyid);
		$this->treeBase->addItem('desc',$this->treeBase->getItem('name'));
		$urls=DCS::url($this->cfg->toLinkURL('mu','muid='.$this->keyid));
		$this->treeBase->addItem('urls',$urls);
		//##########
		$this->treeBase->addItem('names',$this->Names);
		/*
		$this->treeBase->addItem('names.index',$this->IndexNames);
		$this->treeBase->addItem('names.infos','信息');
		$this->treeBase->addItem('names.about',$this->AboutNames);
		$this->treeBase->addItem('names.contact',$this->ContactNames);
		*/
		//debugTree($this->treeBase);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCachePre()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
		$this->theme->doCacheFilterTree('base','cpo.treeBase');
		$this->theme->doCacheFilterTree('userd','cpo.treeBase');
		
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
			case 'no':	$re=$this->cfg->getLinkURL($this->MU_CHANNEL,'index');break;
			default:	$re=$this->cfg->getLinkURL($this->MU_CHANNEL,'mu.'.$page,'muid='.$this->muid.'&'.$keys);break;
		}
		return $re;
	}
	
}
?>