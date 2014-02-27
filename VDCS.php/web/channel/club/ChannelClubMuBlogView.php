<?
class ChannelClubMuBlogView extends ChannelClubMuBase
{
	public $view;
	public function __destruct()
	{
		parent::__destruct();
		unset($this->view);
	}
	
	
	public function doLoad()
	{
		$this->view=new PageView();
		$this->view->doInit();
		$this->_var['query']=$this->_var['sqlquery.key'];
		$this->_var['query']=DB::sqla($this->_var['query'],'d_status=1');
		$this->_var['query']=DB::sqla($this->_var['query'],$this->BlogDataFieldID.'='.$this->view->id);
		$this->view->setStruct('table.name',$this->BlogDataTableName);
		$this->view->setStruct('table.px',$this->BlogDataTablePX);
		$this->view->setStruct('id',$this->BlogDataFieldID);
		$this->view->setStruct('query',$this->_var['query']);
		$this->view->setStruct('sql.update','update '.$this->BlogDataTableName.' set d_total_view=d_total_view+1 where '.$this->_var['query']);
		$this->view->setAuthMode(0);
		$this->view->doLoad();
	}
	
	public function doParse()
	{
		global $cfg;
		if(!$this->view->isDat()){
			go('./');
			return;
		}
		$this->view->doParse();
		$this->view->doParsePic();
		$cfg->setTitle('sub',$this->view->getDatas('topic'));
		$this->classid=$this->view->getVar('classid');
		$this->id=$this->view->getVar('id');
	}
	
	
	public function doThemeCache()
	{
		$this->theme->output=$this->view->toDTMLCache($this->theme->output,'cpo.view');
	}
	
}
?>