<?
class ChannelSupportAnnounceView extends ChannelSupportAnnounce
{
	public $view;
	
	public function isView(){return $this->view->isDat();}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		parent::doLoad();
		$this->doLoadView();
	}
	public function doParse()
	{
		$this->doParseView();
	}
	public function doThemeCache()
	{
		parent::doThemeCache();
		$this->doThemeCacheView();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadView()
	{
		$this->view=new WebPageView();
		$this->view->setStruct('table.name',$this->TableName);
		$this->view->setStruct('table.px',$this->TablePX);
		$this->view->setStruct('query','{$table.px}status=1 and {$table.px}id={$id}');
		$this->view->setStruct('sql.update','update {$table.name} set {$table.px}total_view={$table.px}total_view+1 where {$table.px}id={$id}');
		$this->view->setAuthMode(0);
		$this->view->doLoad();
	}
	
	public function doParseView()
	{
		$this->theme->setModule('view');
		
		if(!$this->view->isDat()){
			go($this->_var['url.root']);
			return;
		}
		$this->id=$this->view->getVar('id');
		$this->view->doParse();
		
		$this->cfg->setTitle('sub',$this->view->getDatas('topic'));
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doThemeCacheView()
	{
		global $theme,$cfg;
		$theme->output=$this->view->toDTMLCache($theme->output,'cpo.view');
	}
	
}
?>