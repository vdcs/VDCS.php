<?
class ChannelSnsMuBase extends WebPortalBase
{
	use WebPortalRefControl;
	
	public $treeBase,$treeBlog,$tableBlogSort;
	public $treeUser;
	protected $_channel='blog';
	public function __destruct()
	{
		parent::__destruct();
		unsetr($this->treeBase,$this->treeBlog,$this->tableBlogSort);
		unsetr($this->treeUser);
	}
	
	public function uInit()
	{
		if($this->isuInit)return;$this->isuInit=true;
		$this->treeUser=$this->ua->queryTree($this->uid,1);
		//debugTree($this->treeUser);
	}
	public function uData($k){$this->uInit();return $this->treeUser->getItem($k);}
	
	
	/*
	########################################
	########################################
	*/
	public function initBasic()
	{
		$this->theme->setTheme('sns');
		$this->theme->setDir('default');
		
		$this->page=$this->_p_?$this->_p_:'index';
		if($this->_m_) $this->page.='.'.$this->_m_;
		$this->theme->setPage($this->page);
		$this->theme->setPre('mu','mu');
		
		$this->chnBlog=$this->cfg->getChannelStruct('blog');
		
		$this->BlogNames=$this->chnBlog->vp('names');
		$this->BlogTableName=$this->chnBlog->vp('table.name');
		$this->BlogTablePX=$this->chnBlog->vp('table.px');
		
		$this->BlogDataNames=$this->chnBlog->vp('data:names');
		$this->BlogDataTableNamed=$this->chnBlog->vp('data:table.name');
		$this->BlogDataTablePX=$this->chnBlog->vp('data:table.px');
		$this->BlogDataFieldID='d_id';
		
		$this->BlogSortNames=$this->chnBlog->vp('sort:names');
		$this->BlogSortTableName=$this->chnBlog->vp('sort:table.name');
		$this->BlogSortTablePX=$this->chnBlog->vp('sort:table.px');
		
		$this->IndexNames='主页';
		$this->ProfileNames='个人资料';
		
		$this->treeBase=newTree();
		$this->loadBase();
		$this->loadBlogSort();
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
		$this->_var['sqlquery.blog']='blogid='.$this->keyid;
		$sql=DB::sqlSelect($this->BlogTableName,'','*',$this->_var['sqlquery.blog'],'',1);
		$this->treeBlog=DB::queryTree($sql);
		if($this->treeBlog->getCount()<1){
			go($this->cURL('no'));
			return;
		}
		$this->_var['is']=true;
		$this->_var['sqlquery.key']='keyid='.$this->keyid;
		$this->treeBlog->doFilter($this->BlogTablePX);
		$this->treeBlog->addItem('id',$this->keyid);
		$this->BlogDataTableName=$this->treeBase->getItem('sp_data');
		if(ise($this->BlogDataTableName)) $this->BlogDataTableName=$this->BlogDataTableNamed;
		//##########
		$this->treeBase->addItem('muid',$this->muid);
		$this->treeBase->addItem('uid',$this->uid);
		$this->treeBase->addItem('id',$this->keyid);
		$this->treeBase->addItem('name',$this->treeBlog->getItem('name'));
		$this->treeBase->addItem('desc',$this->treeBlog->getItem('desc'));
		$urls=DCS::url($cfg->toLinkURL('mu','muid='.$this->keyid));
		$this->treeBase->addItem('urls',$urls);
		//##########
		$this->treeBase->addItem('names',$this->Names);
		$this->treeBase->addItem('names.index',$this->IndexNames);
		$this->treeBase->addItem('names.blog',$this->BlogDataNames);
		$this->treeBase->addItem('names.blog.sort',$this->BlogSortNames);
		$this->treeBase->addItem('names.profile',$this->ProfileNames);
		//debugTree($this->treeBase);
	}
	public function loadBlogSort()
	{
		$sql=DB::sqlSelect($this->BlogSortTableName,'','*',$this->_var['sqlquery.key'],'orderid,sortid');
		//debugx($sql);
		$this->tableBlogSort=DB::queryTable($sql);
		$treeUn=newTree();
		$treeUn->addItem('sortid','0');
		$treeUn->addItem('name','未分类');
		$this->tableBlogSort->addItem($treeUn);
		$this->doBlogSortFilter($this->tableBlogSort);
		//debugTable($this->tableBlogSort);
	}
	public function doBlogSortFilter(&$tableData)
	{
		$tableData->doAppendFields('linkurl');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('linkurl',$this->cURL('blog.sort','sortid='.$tableData->getItemValue('sortid')));
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheBasic()
	{
		$this->theme->doCacheFilterVar('{@id}','cpo.id');
		$this->theme->doCacheFilterTree('base','cpo.treeBase');
		$this->theme->doCacheFilterTree('blog','cpo.treeBlog');
		$this->theme->doCacheFilterTree('userd','cpo.','uData');
		$this->theme->doCacheFilterLoop('blogsort','cpo.tableBlogSort');
		
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
			case 'no':	$re=$this->cfg->getLinkURL('club','index');break;
			default:	$re=$this->cfg->getLinkURL('club','mu.'.$page,'muid='.$this->muid.'&'.$keys);break;
		}
		return $re;
	}
	
}
?>