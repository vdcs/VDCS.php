<?
class ChannelAccountBlogBase extends ChannelAccountBase
{
	public $treeBase;
	protected $channelKey='';
	protected $ngo=true;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->treeBase);
	}
	
	
	public function doLoadPre()
	{
		if(!$this->channelKey) $this->channelKey=$this->_p_;
		
		$this->chn=$this->cfg->getChannelStruct($this->channelKey);
		$this->cfg->setTitle($this->chn->v('title.my'));
		
		$this->Names=$this->chn->vp('names');
		$this->TableName=$this->chn->vp('table.name');
		$this->TablePX=$this->chn->vp('table.px');
		
		$this->DataNames=$this->chn->vp('data:names');
		$this->DataTableNamed=$this->chn->vp('data:table.name');
		$this->DataTablePX=$this->chn->vp('data:table.px');
		
		$this->loadBase();
	}
	
	
	public function loadBase()
	{
		$this->_var['sqlquery']='uuid='.$this->ua->id;
		$sql=DB::sqlSelect($this->TableName,'','*',$this->_var['sqlquery'],'',1);
		//debugx($sql);
		$this->treeBase=newTree();
		$this->treeBaseRS=DB::queryTree($sql);
		if($this->treeBaseRS->getCount()<1){
			if($this->ngo) go($this->cURL('no'));
			return;
		}
		$this->_var['is']=true;
		$this->keyid=$this->treeBase->getItemInt('blogid');
		$this->_var['sqlquery.key']='keyid='.$this->keyid;
		$this->treeBase->setArray($this->treeBaseRS->getArray());
		$this->treeBase->doFilter($this->TablePX);
		$this->treeBase->addItem('id',$this->keyid);
		$this->DataTableName=$this->treeBase->getItem('sp_data');
		if(ise($this->DataTableName)) $this->DataTableName=$this->DataTableNamed;
		//$this->treeBase
	}
	
	
	public function cURL($page,$keys='')
	{
		global $cfg;
		switch($page){
			case 'no':	$re=$cfg->getLinkURL('account','p','p='.$this->channelKey.'&action=no');break;
			case 'm':	$re=$cfg->getLinkURL('account','p','p='.$this->channelKey.'&'.$keys);break;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCachePre()
	{
		parent::doThemeCachePre();
		$this->theme->doCacheFilterTree('base','cpo.treeBase');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doMessage($type,$state,$msg='',$url='')
	{
		parent::doMessage($type,$state,$msg,$url);
		switch($type){
			case 'common':
			case 'none':
				break;
			default:
				$this->theme->setPage($this->portal);
				break;
		}
		$this->theme->setPageEnd('');
	}
}
?>