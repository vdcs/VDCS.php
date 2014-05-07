<?
trait ManageRefConfig
{
	
	public function initConfig(&$file='config')
	{
		if(!$file) $file='config';
		if(empty($this->_trees[$file])){
			//debugx(appFilePath('vdcs.mconfig/'.$file));
			//debugx(appFilePath('manage.config/'.$file));
			//debugx(appFilePath('manage.config/'.$file.FILE_EXTEND_DEFINE));
			$this->_trees[$file]=VDCSDTML::getConfigCacheTree('vdcs.mconfig/'.$file);
			$this->_trees[$file]->doAppendTree(VDCSDTML::getConfigCacheTree('manage.config/'.$file));
			$this->_trees[$file]->doAppendTree(VDCSDTML::getConfigCacheTree('manage.config/'.$file.FILE_EXTEND_DEFINE));
			//debugTree($this->_trees[$file]);
			//if($file=='language'){
			//	debugTree($this->_trees[$file]);
			//}
		}
	}
	public function cfg($key,$file='config',$ar=null,$def=''){return $this->config($key,$file,$ar,$def);}
	public function setting($key){return $this->config('setting.'.$key);}
	public function config($key,$file='config',$ar=null,$def='')
	{
		$this->initConfig($file);
		$re='';
		if(isTree($this->_trees[$file])) $re=$this->_trees[$file]->getItem($key);
		if(len($re)<1 && $def=='def') $re='[cfg:'.$file.'/'.$key.']';
		if($re=='null') $re='';
		if($ar!=null){
			foreach($ar as $k=>$v){
				$re=rd($re,$k,$v);
			}
		}
		return $re;
	}
	public function configTree($px='',$px2='',$file='config')
	{
		$this->initConfig($file);
		if(len($px)>0){
			return $this->_trees[$file]->getFilterTree($px,$px2);
		}
		else{
			return $this->_trees[$file];
		}
	}
	
}
?>