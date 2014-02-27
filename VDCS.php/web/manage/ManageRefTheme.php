<?
trait ManageRefTheme
{
	
	/*
	########################################
	########################################
	*/
	public function doThemeCachePrePortal()
	{
		$this->theme->doCacheFilterLoop('menu','mpo.tableMenu');
		$this->theme->doCacheFilterTree('view','mpo.treeView');
		$this->theme->doCacheFilterLoop('list','mpo.tableList');
		//##########
		if($this->chn){
			$treeConfigure=$this->chn->getConfigureTree($this->chn->module);
			if(!$this->_trees['v']) $this->_trees['v']=newTree();
			$this->_trees['v']->doAppendTree($treeConfigure->getFilterTree('config.'));
			$this->_trees['v']->doAppendTree($treeConfigure->getFilterTree('lang.'));
			$this->theme->output=utilRegex::toReplaceRegex($this->theme->output,$this->_trees['v'],'<v:([^<>]*)>');
			unset($treeConfigure);
		}
		//##########
		//$this->theme->output=$this->toDTMLExec($this->theme->output);
	}
	public function doThemeCachePosPortal()
	{
		if($this->chn && $this->chn->is()){
			//$this->theme->output=utilRegex::toReplacePre($this->theme->output,$this->chnPre);
		}
		//debugx($this->theme->output);
		$this->theme->output=$this->toDTMLExec($this->theme->output);
		$this->theme->output=$this->toDTMLExec($this->theme->output);
		$this->theme->output=$this->toDTMLExec($this->theme->output);
		if($this->_isBox){
			$preChn=$this->chnPre->getArray();
			$this->theme->output=preg_replace('/{@'.PATTERN_FLAG_VAR.'}/ies','array_key_exists(\'$1\',\$preChn)?\$preChn[\'$1\']:\'{@-$1}\'',$this->theme->output);
			$this->theme->output=$this->box->toDTMLCache($this->theme->output,'mp.box');
		}
	}
	
	public function doThemePrePortal()
	{
		
	}
	public function doThemePosPortal()
	{
		
	}
	
	public function toDTMLExec($re)
	{
		$_matches=utilRegex::toMatches($re,VDCSDTML::PATTERN_DTML_EXEC);
		//debuga($_matches);
		$params=array();
		for($m=0;$m<count($_matches[0]);$m++){
			$params[0]=$_matches[0][$m];
			$params[1]=$_matches[1][$m];
			$params[2]=$_matches[2][$m];
			$params[3]=$_matches[4][$m];
			$params[4]=$_matches[6][$m];
			$params[5]=$_matches[8][$m];
			$re=r($re,$params[0],$this->toParseExecValue($params));
		}
		unset($_matches,$params);
		return $re;
	}
	public function toParseExecValue($params)
	{
		global $mpFrame;
		$re='';
		//debuga($params);
		switch($params[1]){
			case 'url':		$re=len($params[3])>0?$this->getURL($params[2],$params[3]):$this->getURL($params[2]); break;
			case 'langs':		$re=$this->getLangs($params[2]); break;
			case 'config':		$re=len($params[3])>0?$this->getConfig($params[2],$params[3]):$this->getConfig($params[2]); break;
			case 'lang':		$re=len($params[3])>0?$this->getLang($params[2],$params[3]):$this->getLang($params[2]); break;
			//case 'menu':		$re=$mpFrame->toDTML('doFrameMenu');break;
		}
		return $re;
	}
	
}
?>