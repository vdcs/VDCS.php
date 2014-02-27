<?
class ManageAppModel
{
	public $model,$channel;
	public $treeChannel,$tableChannel;
	public $classid=-1,$isclass=false,$isclassi=false,$treeClass;
	private $isshowother=false,$isunite=false;
	private $_MenuLinks='';
	
	public function __construct()
	{
	}
	public function __destruct()
	{
		unset($this->treeChannel,$this->tableChannel);
		unset($this->treeClass);
	}
	
	
	public function setModel($s){$this->model=$s;}
	public function setShowOther($s){$this->isshowother=$s;}
	public function setUnite($s){$this->isunite=$s;}
	
	public function setClass($s){$this->isclass=$s;}
	public function isClass(){return $this->isclass?$this->isclassi:false;}
	
	Public function getMenuLinks(){return $this->_MenuLinks;}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoadFrame(&$chn,&$channel=null,&$module=null)
	{
		$this->treeChannel=newTree();
		$this->tableChannel=CommonChannelExtend::getStructTermTable('config.model',$this->model,'like');
		//debugTable($this->tableChannel);
		utilString::lists($chn,$channel,$module,'.');
		$this->channel=$channel;
		$this->loadClass();
		$tmp_channel=$channel;$channel='';
		$this->tableChannel->doBegin();
		while($this->tableChannel->isNext()){
			if(inp($this->tableChannel->getItemValue('model'),$this->model)>0){
				$tmpchannel=$this->tableChannel->getItemValue('channel');
				if(toLower($tmpchannel)==toLower($tmp_channel)){
					$this->treeChannel=$this->tableChannel->getItemTree();
					$channel=$tmp_channel;
					$this->_MenuLinks.=('<li><a href="'.$mp->getURL('action=list').'">'.$this->treeChannel->getItem('name').$mp->getLang('title.name').'</a></li>');
					if($this->isunite){
						$this->_MenuLinks.=('<li><a href="'.$mp->getURL('action=unite').'">数据合并</a></li>');
					}
					if($this->isClass()){
						$this->_MenuLinks.=('<li><a href="'.$mp->getURL('action=list').'">'.$this->classname.'</a></li>');
					}
					$this->_MenuLinks.=('<li><a href="'.$mp->getURL('action=add').'">'.$mp->getLang('title.add').'</a></li>');
					$this->isshowother=false;
					break;
				}
			}
		}
		if($this->isshowother){
			$tmpchannel=$channel;
			$this->tableChannel->doBegin();
			while($this->tableChannel->isNext()){
				if(inp($this->tableChannel->getItemValue('model'),$this->model)>0){
					$channel=$this->tableChannel->getItemValue('channel');
					if($tmpchannel!=$channel){
						$this->_MenuLinks.=('<li><a href="'.$mp->getURL('action=list').'">'.$this->tableChannel->getItemValue('name').$mp->getLang('title.name').'</a></li>');
					}
				}
			}
			$channel=$tmpchannel;
		}
	}
	
	public function loadClass($channel='')
	{
		if(!$this->isclass) return;
		if(!$channel) $channel=$this->channel;
		if($this->classid<0) $this->classid=queryi('classid');
		if($this->classid>0){
			$this->treeClass=$cfg->clas->getChannelTree($channel,$this->classid);
			if($this->treeClass->getCount()>0){
				$this->classname=$this->treeClass->getItem('name');
				$this->isclassi=true;
			}
		}
	}
	
}
?>