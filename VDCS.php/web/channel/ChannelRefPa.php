<?
trait ChannelRefPa
{
	use WebRefQuery;
	
	public function inited()
	{
		parent::inited();
		$this->_p_=queryx('ap');
		$this->_m_=queryx('am');
		$this->_mi_=queryx('ami');
		$this->action=queryx('action');
		if(!$this->_p_) $this->_p_=APP_PORTAL_DEFAULT;
		if(!$this->_m_){
			$mdefault=$this->cfg->cfg('m.default');
			$n=ins(';'.$mdefault.';',';'.$this->_p_.'=');
			if($n>0){
				$_m_=toSubstr($mdefault,$n+len($this->_p_)+1).';';
				//debugx($_m_);
				$_m_=ins($_m_,';')>1?toSubstr($_m_,1,ins($_m_,';')-1):'';
				//debugs($_m_);
				$this->_m_=$_m_;
			}
		}
		//debugx('PA: _p_='.$this->_p_.',_m_='.$this->_m_.',action='.$this->action);
		
		$objectpa=ucfirst($this->_chn_).'Pa'.ucfirst($this->_p_).ucfirst($this->_m_).ucfirst($this->_mi_);
		define('APP_OBJECTPA',$objectpa);
		if(_autoload_::isReal($objectpa)){
			$this->pam=new $objectpa();
			$this->pam->cfg=&$this->cfg;
			$this->pam->theme=&$this->theme;
			$this->pam->ua=&$this->ua;
			$this->pam->_chn_=&$this->_chn_;
			$this->pam->_p_=&$this->_p_;
			$this->pam->_m_=&$this->_m_;
			$this->pam->_mi_=&$this->_mi_;
			$this->pam->_x_=&$this->extend;
			$this->pam->action=&$this->action;
			$this->pam->mode=&$this->mode;
			$this->pam->_var=&$this->_var;
			$this->pam->treeVar=&$this->treeVar;
			$this->pam->treeDat=&$this->treeDa;
			$this->pam->treeData=&$this->treeData;
			$this->pam->treeDTML=&$this->treeDTML;
		}
	}
	
	public function doInit()
	{
		if($this->pam) $this->pam->doInit($this);
	}
	public function doLoad()
	{
		if($this->pam) $this->pam->doLoad($this);
	}

	public function parseCan()
	{
		if($this->pam) return $this->pam->parseCan($this);
		return true;
	}
	public function doParse()
	{
		if($this->pam) $this->pam->doParse($this);
	}

	public function doTheme()
	{
		if($this->pam) $this->pam->doTheme($this);
	}
	public function doThemeCache()
	{
		if($this->pam) $this->pam->doThemeCache($this);
	}
	public function doThemer()
	{
		if($this->pam) $this->pam->doThemer($this);
	}
	
	/*
	public function doThemePre(){}				//模板前置输出处理
	public function doTheme(){}				//模板输出处理
	public function doThemePos(){}				//模板后置输出处理
	public function doThemeCacheBasic(){}			//模板基础缓存处理
	public function doThemeCachePre(){}			//模板前置缓存处理
	public function doThemeCache(){}			//模板缓存处理
	public function doThemeCachePos(){}			//模板后置缓存处理
	public function doThemer(){}				//模板输出处理
	*/

}
